<?php

use common\models\Nomenclature;
use common\models\Tender;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;

/** @var yii\web\View $this */
/** @var common\models\Tender $model */
/** @var common\models\Nomenclature[] $nomenclatureModels */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tender-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'budget')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList(Tender::STATUS_MAP) ?>

    <div class="row nomenclature-form">
        <div class="panel panel-default">
            <div class="panel-heading"><h4><i class="glyphicon glyphicon-envelope"></i>Номенклатури</h4></div>
            <div class="panel-body">
                <?php DynamicFormWidget::begin([
                    'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                    'widgetBody' => '.container-items', // required: css class selector
                    'widgetItem' => '.item', // required: css class
                    'limit' => 10, // the maximum times, an element can be cloned (default 999)
                    'min' => 1, // 0 or 1 (default 1)
                    'insertButton' => '.add-item', // css class
                    'deleteButton' => '.remove-item', // css class
                    'model' => $nomenclatureModels[0],
                    'formId' => 'dynamic-form',
                    'formFields' => [
                        'description',
                        'count',
                        'measure',
                    ],
                ]); ?>

                <div class="container-items"><!-- widgetContainer -->
                    <?php foreach ($nomenclatureModels as $i => $nomenclatureModel): ?>
                        <div class="item panel panel-default"><!-- widgetBody -->
                            <div class="panel-heading">
                                <div class="pull-right">
                                    <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                                    <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-body">
                                <?php
                                // necessary for update action.
                                if (! $nomenclatureModel->isNewRecord) {
                                    echo Html::activeHiddenInput($nomenclatureModel, "[{$i}]id");
                                }
                                ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <?= $form->field($nomenclatureModel, "[{$i}]description")->textInput(['maxlength' => true]) ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?= $form->field($nomenclatureModel, "[{$i}]count")->textInput(['maxlength' => true]) ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?= $form->field($nomenclatureModel, "[{$i}]measure")->dropDownList(Nomenclature::MEASURE_MAP) ?>
                                    </div>
                                </div><!-- .row -->
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php DynamicFormWidget::end(); ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php $this->registerJs("
    $(function () {
        $('body').on('submit', 'form', function() {
            $(this).find('button[type!=\"button\"],input[type=\"submit\"]').attr('disabled',true);
            setTimeout(function(){
                $(this).find('.has-error').each(function(index, element) {
                    $(this).parents('form:first').find(':submit').removeAttr('disabled');
                });
            },1000);
        });
    });
", yii\web\View::POS_END, 'prevent-double-form-submit'); ?>

    <?php ActiveForm::end(); ?>

</div>
