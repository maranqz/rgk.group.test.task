<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $preview
 * @property string $description
 * @property string $img
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

    const DATE_SEPARATE = ' - ';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
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
                'updatedByAttribute' => false,
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
            [['active', 'created_at'], 'integer'],
            [['title', 'preview', 'img'], 'string', 'max' => 255],
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
            'active' => 'Active',
            'created_at' => 'Created At',
        ];
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
}
