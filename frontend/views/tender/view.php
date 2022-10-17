<?php

use yii\grid\SerialColumn;
use common\models\Nomenclature;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Tender $model */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Tenders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="tender-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'description:ntext',
            'budget',
            'status',
        ],
    ]) ?>

    <h2>Номенклатури</h2>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => SerialColumn::class],

            'id',
            'tender_id',
            'description:ntext',
            'count',
            [
                'attribute' => 'measure',
                'value' => static function ($data) {
                    return Nomenclature::MEASURE_MAP[$data['measure']];
                }
            ],
            [
                'class' => ActionColumn::class,
                'urlCreator' => static function ($action, Nomenclature $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]) ?>

</div>
