<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Tender $model */
/** @var common\models\Nomenclature[] $nomenclatureModels */

$this->title = 'Створити закупівлю';
$this->params['breadcrumbs'][] = ['label' => 'Закупівлі', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tender-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'nomenclatureModels' => $nomenclatureModels,
    ]) ?>

</div>
