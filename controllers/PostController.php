<?php

namespace app\controllers;

use app\models\user\User;
use izumi\longpoll\Event;
use Yii;
use app\models\Post;
use app\models\PostSearch;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['viewPost'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['createPost'],
                    ],
                    [
                        'actions' => ['update', 'active'],
                        'allow' => true,
                        'roles' => ['updatePost', 'updateOwnPost'],
                        'roleParams' => self::roleParamsIsAuthor(),
                    ],
                    ['actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['deletePost', 'deleteOwnPost'],
                        'roleParams' => self::roleParamsIsAuthor(),
                    ],

                ],

            ],
        ];
    }

    /**
     * Model are returned for check access own Post.
     * @return \Closure
     */
    static public function roleParamsIsAuthor()
    {
        return function () {
            $id = Yii::$app->request->get('id') ? Yii::$app->request->get('id') : Yii::$app->request->get('item_id');
            return ['model' => Post::findOne($id)];
        };
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {

        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->getSort()->route = 'post/index';

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Post();
        $event = $this->getEvent($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_CREATE, $event);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->trigger(self::EVENT_AFTER_CREATE, $event);

            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $event = $this->getEvent($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_UPDATE, $event);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->trigger(self::EVENT_AFTER_UPDATE, $event);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Active or deactivate a Post model.
     * @return mixed
     */
    public function actionActive($item_id)
    {

        $model = $this->findModel($item_id);
        $event = $this->getEvent($model);

        if ($model->active) {
            $this->trigger(self::EVENT_BEFORE_DEACTIVATE, $event);
            $model->deactivate();
            $this->trigger(self::EVENT_AFTER_ACTIVATE, $event);
            \Yii::$app->getSession()->setFlash('success', 'Post has been deactivated');
        } else {
            $this->trigger(self::EVENT_BEFORE_DEACTIVATE, $event);
            $model->activate();
            $this->trigger(self::EVENT_AFTER_DEACTIVATE, $event);
            \Yii::$app->getSession()->setFlash('success', 'Post has been activated');
        }


        return $this->actionIndex();
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
