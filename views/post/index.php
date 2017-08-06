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

    <div class="pull-left">
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
    </div>
    <div class="pull-right">
        <?= \yii\bootstrap\Html::activeDropDownList(
            $searchModel,
            'page_size',
            [10 => 10, 20 => 20, 30 => 30, 50 => 50, 100 => 100],
            [
                'class' => 'form-control',
                'value' => $dataProvider->pagination->pageSize
            ]
        ); ?>
    </div>
    <div class="clearfix"></div>
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
        'filterSelector' => 'select[name*="page_size"]',
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
                'value' => function ($model) use ($filterParams, $user) {
                    $title = 'Not active';
                    $class = 'btn-danger';
                    $confirm = 'Are you sure you want to active this post?';


                    $filterParams['item_id'] = $model->id;

                    if ($model->active) {
                        $title = 'Active';
                        $class = 'btn-success';
                        $confirm = 'Are you sure you want to deactivate this post?';
                    }

                    $options = [
                            'class' => 'disabled '
                    ];

                    if ($user->can('updatePost') || $user->can('updateOwnPost', ['model' => $model])) {
                        $options = [
                            'data-pjax' => '#postIndex',
                            'data-confirm' => $confirm,
                        ];
                    }

                    $options['class'] .= 'btn btn-xs btn-block ' . $class;;

                    return Html::a($title, $filterParams, $options);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'filterOptions' => ['class' => 'daterangepicker-parent'],
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'format' => \Yii::$app->formatter->datetimeFormat,
                        'timePicker' => true,
                        'timePickerIncrement' => 15,
                        'locale' => [
                            'format' => 'Y-m-d h:i A',
                            'separator' => \app\models\Post::DATE_SEPARATE,
                        ],
                        'parentEl' => "td:has(#postsearch-created_at)",
                    ]
                ]),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => ($user->can('updatePost') ? '{view} ' : '') . '{update} {delete}',
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
                ],
                'visibleButtons' => [
                    'update' => function ($model, $key, $index) use ($user) {
                        return $user->can('updatePost') || $user->can('updateOwnPost', ['model' => $model]) ? true : false;
                    },
                    'delete' => function ($model, $key, $index) use ($user) {
                        return $user->can('deletePost') || $user->can('deleteOwnPost', ['model' => $model]) ? true : false;
                    }
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
