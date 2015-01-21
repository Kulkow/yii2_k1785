<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\NomenclatureSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="nomenclature-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'alias') ?>

    <?= $form->field($model, 'category_id') ?>

    <?= $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'image_id') ?>

    <?php // echo $form->field($model, 'sort') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
