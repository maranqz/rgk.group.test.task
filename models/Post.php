<?php

namespace app\models;

use izumi\longpoll\Event;
use PHPUnit\Framework\Exception;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $preview
 * @property string $description
 * @property string $img
 * @property UploadedFile $img_file
 * @property integer $active
 * @property integer $created_at
 */
class Post extends \yii\db\ActiveRecord
{

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_LIST = [
        Post::STATUS_NOT_ACTIVE => 'Not active',
        Post::STATUS_ACTIVE => 'Active'
    ];

    const UPLOADS_DIR = '/' . 'uploads' . '/';

    const DATE_SEPARATE = ' - ';

    public $img_file = null;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * Set created_at and created_by according time and owner id
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => time(),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => false
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'preview', 'description'], 'required'],
            [['description'], 'string'],
            [['active', 'created_at', 'created_by'], 'integer'],
            [['title', 'preview', 'img'], 'string', 'max' => 255],
            [['img_file'], 'file', 'extensions' => 'png, gif, jpg']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'preview' => 'Preview',
            'description' => 'Description',
            'img' => 'Img',
            'img_file' => 'Load img',
            'active' => 'Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    public function insert($runValidation = true, $attributes = null)
    {
        if (parent::insert($runValidation, $attributes)) {
            $notification = new Notification();
            $notification->setAttributes(['model' => 'Post', 'item_id' => $this->id]);
            if (!($notification->validate() && $notification->save())) {
                throw new Exception(implode('\n', array_shift($notification->errors)));
            }

            return true;
        }
        return false;
    }

    /**
     * Activate the post.
     */
    public function activate()
    {
        return (bool)$this->updateAttributes(['active' => 1]);
    }

    /**
     * Deactivate the post.
     */
    public function deactivate()
    {
        return (bool)$this->updateAttributes(['active' => 0]);
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $transaction = $this->getDb()->beginTransaction();

        try {

            $this->img_file = UploadedFile::getInstance($this, 'img_file');

            if (!empty($this->img_file)) {
                $dir = Post::UPLOADS_DIR . $this->id . '.' . $this->img_file->extension;
                if(!($this->validate(['img_file']) && $this->img_file->saveAs(\Yii::getAlias('@webroot') . $dir))){
                    return false;
                }
                $this->img_file = null;

                $this->img = $dir;
            }

            if (!parent::save($runValidation, $attributeNames)) {
                $transaction->rollBack();
                return false;
            }

            $transaction->commit();

            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::warning($e->getMessage());
            throw $e;
        }
    }
}
