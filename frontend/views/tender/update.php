<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Tender $model */
/** @var common\models\Nomenclature[] $nomenclatureModels */

$this->title = 'Оновити Закупівлю: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Закупівлі', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tender-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'nomenclatureModels' => $nomenclatureModels
    ]) ?>

</div>
