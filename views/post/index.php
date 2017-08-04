<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use \kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;


$filterParams = \Yii::$app->request->getQueryParams();
array_unshift($filterParams, 'active');

$pjaxId = 'postIndex';
$eventModal = \Yii::$app->params['modalEventPjax']($pjaxId);

$user = \Yii::$app->user;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= $user->can('createPost') ? \yii\bootstrap\Modal::widget([
            'id' => 'postCreate-modal',
            'toggleButton' => [
                'data-target' => '#postCreate-modal',
                'label' => 'Create Post',
                'tag' => 'a',
                'class' => 'btn btn-success',
                'href' => \yii\helpers\Url::to(['create']),
            ],
            'size' => \yii\bootstrap\Modal::SIZE_LARGE,
            'clientEvents' => $eventModal
        ]) : '' ?>
    </p>
    <?php Pjax::begin([
        'id' => $pjaxId,
        'formSelector' => '#postIndex form[data-pjax]:not(#postForm form[data-pjax])',
        'clientOptions' => [
            'skipOuterContainers' => true
        ],
    ]); ?>
    <?= GridView::widget([
        'filterUrl' => '/post/index?' . \Yii::$app->request->getQueryString(),
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'filter' => false
            ],
            'title',
            'preview',
            [
                'attribute' => 'active',
                'filter' => \app\models\Post::STATUS_LIST,
                'value' => function ($model) use ($filterParams) {
                    $title = 'Not active';
                    $class = 'btn-danger';
                    $confirm = 'Are you sure you want to active this post?';
                    $options = [
                        'class' => 'btn btn-xs btn-block ' . $class,
                        'data-pjax' => '#postIndex',
                        'data-confirm' => $confirm,
                    ];


                    $filterParams['item_id'] = $model->id;

                    if ($model->active) {
                        $title = 'Active';
                        $class = 'btn-success';
                        $confirm = 'Are you sure you want to deactivate this post?';
                    }

                    return Html::a($title, $filterParams, [
                        'class' => 'btn btn-xs btn-block ' . $class,
                        'data-pjax' => '#postIndex',
                        'data-confirm' => $confirm,
                    ]);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'format' => \Yii::$app->formatter->datetimeFormat,
                        'timePicker'=>true,
                        'timePickerIncrement'=>15,
                        'locale' => [
                            'format'=>'Y-m-d h:i A',
                            'separator' => \app\models\Post::DATE_SEPARATE,
                        ],
                    ]
                ]),
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model, $id) use ($eventModal) {
                        return \yii\bootstrap\Modal::widget([
                            'id' => 'postUpdate-modal',
                            'toggleButton' => [
                                'data-target' => '#postUpdate-modal',
                                'class' => 'glyphicon glyphicon-pencil',
                                'label' => '',
                                'tag' => 'a',
                                'href' => $url,
                            ],
                            'size' => \yii\bootstrap\Modal::SIZE_LARGE,
                            'clientEvents' => $eventModal
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
