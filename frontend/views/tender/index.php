<?php

use yii\grid\SerialColumn;
use common\models\Tender;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var yii\data\ActiveDataProvider $dataProviderMyDraftTenders */

$this->title = 'Закупівлі';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tender-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Створити Закупівлю', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => SerialColumn::class],

            'id',
            'name',
            'description:ntext',
            'budget',
            [
                'attribute' => 'status',
                'value' => static function ($data) {
                    return Tender::STATUS_MAP[$data['status']];
                }
            ],
            [
                'class' => ActionColumn::class,
                'urlCreator' => static function ($action, Tender $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]) ?>

    <h2>Мої чернетки</h2>
    <?= GridView::widget([
        'dataProvider' => $dataProviderMyDraftTenders,
        'columns' => [
            ['class' => SerialColumn::class],

            'id',
            'name',
            'description:ntext',
            'budget',
            [
                'attribute' => 'status',
                'value' => static function ($data) {
                    return Tender::STATUS_MAP[$data['status']];
                }
            ],
            [
                'class' => ActionColumn::class,
                'urlCreator' => static function ($action, Tender $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]) ?>


</div>
