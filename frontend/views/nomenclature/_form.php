<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Nomenclature $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="nomenclature-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tender_id')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'count')->textInput() ?>

    <?= $form->field($model, 'measure')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
