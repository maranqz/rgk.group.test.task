<?php

use yii\helpers\Html;
use \yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = 'Update Post: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<?php Pjax::begin([
    'id' => 'postForm',
    'enablePushState' => false,
    'enableReplaceState' => false,
    'clientOptions' => [
        'skipOuterContainers' => true
    ],
]); ?>

<div class="post-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'action' => ['post/update', 'id' => $model->id]
    ]) ?>

</div>

<?php Pjax::end(); ?>
