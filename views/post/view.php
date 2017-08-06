<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'preview',
            'description:ntext',
            [
                'attribute' => 'img',
                'format'=> 'raw',
                'value' => function ($model) {
                    return Html::img('@web' . $model->img, ['class' => 'image']);
                },
                'visible' => !empty($model->img)
            ],
            'active',
            'created_at',
        ],
    ]) ?>

</div>
