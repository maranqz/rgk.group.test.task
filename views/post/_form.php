<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \app\models\Post;

/* @var $this yii\web\View */
/* @var $model app\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => true,//true
        'enableClientValidation' => false,//false
        'id' => 'postForm-' . time(),
        'options' => ['data-pjax' => true]
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'preview')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'img')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'active')->dropDownList(Post::STATUS_LIST) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [
            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
            //'data-pjax' => '#postForm',
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
