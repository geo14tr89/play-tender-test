<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Nomenclature $model */

$this->title = 'Create Nomenclature';
$this->params['breadcrumbs'][] = ['label' => 'Nomenclatures', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nomenclature-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
