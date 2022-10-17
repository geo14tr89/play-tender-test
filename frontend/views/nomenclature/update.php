<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Nomenclature $model */

$this->title = 'Update Nomenclature: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Nomenclatures', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="nomenclature-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
