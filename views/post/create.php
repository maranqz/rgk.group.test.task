<?php

use yii\helpers\Html;
use \yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = 'Create Post';
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php Pjax::begin([
    'id' => 'postForm',
    'enablePushState' => false,
    'enableReplaceState' => false,
    'clientOptions' => [
        'skipOuterContainers' => true
    ],
]); ?>

<div class="post-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php Pjax::end(); ?>
