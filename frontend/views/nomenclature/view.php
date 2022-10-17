<?php

use common\models\Nomenclature;
use common\models\Tender;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Nomenclature $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Nomenclatures', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="nomenclature-view">

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
            'tender_id',
            'description:ntext',
            'count',
            [
                'attribute' => 'measure',
                'value' => static function ($data) {
                    return Nomenclature::MEASURE_MAP[$data['measure']];
                }
            ],
        ],
    ]) ?>

</div>
