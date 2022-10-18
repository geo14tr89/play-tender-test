<?php

namespace frontend\controllers;

use common\models\Model;
use common\models\Nomenclature;
use common\models\Tender;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * TenderController implements the CRUD actions for Tender model.
 */
class TenderController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'only' => ['create', 'update', 'index', 'view'],
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@']
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Tender models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Tender::find()->where(['status' => Tender::STATUS_ACTIVE]),
        ]);

        $dataProviderMyDraftTenders = new ActiveDataProvider([
            'query' => Tender::find()
                ->where(['status' => Tender::STATUS_DRAFT])->andWhere(['created_by' => Yii::$app->user->id]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'dataProviderMyDraftTenders' => $dataProviderMyDraftTenders
        ]);
    }

    public function beforeAction($action): bool
    {
        if ($action->id === 'view') {
            $id = (int)Yii::$app->request->get('id');
            $model = $this->findModel($id);
            if ($model->created_by !== Yii::$app->user->id && $model->status === Tender::STATUS_DRAFT) {
                $permissionName = $this->id . ucfirst($action->id);
                throw new ForbiddenHttpException($permissionName);
            }
        }

        return parent::beforeAction($action);
    }

    /**
     * Displays a single Tender model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        $model = $this->findModel($id);

        $dataProvider = new ActiveDataProvider([
            'query' => Nomenclature::find()->where(['tender_id' => $id]),
        ]);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new Tender model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $tenderModel = new Tender();
        $nomenclatureModels = [new Nomenclature()];

        if ($tenderModel->load($this->request->post())) {
            $tenderModel->created_by = Yii::$app->user->id;
            $nomenclatureModels = Model::createMultiple(Nomenclature::class);
            Model::loadMultiple($nomenclatureModels, Yii::$app->request->post());

            // validate all models
            $valid = $tenderModel->validate();
            $valid = Model::validateMultiple($nomenclatureModels) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $tenderModel->save(false)) {

                        /** @var Nomenclature $nomenclatureModel */
                        foreach ($nomenclatureModels as $nomenclatureModel) {
                            $nomenclatureModel->tender_id = $tenderModel->id;
                            if (!($flag = $nomenclatureModel->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $tenderModel->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        } else {
            $tenderModel->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $tenderModel,
            'nomenclatureModels' => (empty($nomenclatureModels)) ? [new Nomenclature()] : $nomenclatureModels
        ]);
    }

    /**
     * Updates an existing Tender model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException|Exception if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $tenderModel = $this->findModel($id);
        $nomenclatureModels = $tenderModel->nomenclatures();

        if ($tenderModel->load($this->request->post())) {
            $tenderModel->validate();
            $oldIDs = ArrayHelper::map($nomenclatureModels, 'id', 'id');
            $nomenclatureModels = Model::createMultiple(Nomenclature::class, $nomenclatureModels->all());
            Model::loadMultiple($nomenclatureModels, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($nomenclatureModels, 'id', 'id')));

            // validate all models
            $valid = $tenderModel->validate();
            $valid = Model::validateMultiple($nomenclatureModels) && $valid;

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $tenderModel->save(false)) {
                        if (! empty($deletedIDs)) {
                            Nomenclature::deleteAll(['id' => $deletedIDs]);
                        }

                        /** @var Nomenclature $nomenclatureModel */
                        foreach ($nomenclatureModels as $nomenclatureModel) {
                            $nomenclatureModel->tender_id = $tenderModel->id;
                            if (! ($flag = $nomenclatureModel->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();

                        return $this->redirect(['view', 'id' => $tenderModel->id]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'model' => $tenderModel,
            'nomenclatureModels' => (empty($nomenclatureModels->all())) ? [new Nomenclature()] : $nomenclatureModels->all()
        ]);
    }

    /**
     * Deletes an existing Tender model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Tender model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Tender the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Tender
    {
        if (($model = Tender::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
