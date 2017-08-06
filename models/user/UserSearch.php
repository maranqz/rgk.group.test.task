<?php

namespace app\models\user;

use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about User.
 */
class UserSearch extends \dektrium\user\models\UserSearch
{
    /** @var integer */
    public $id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = array_merge($rules, [
            [['id'], 'integer'],
            [['id'], 'safe'],
        ]);

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();
        $labels['id'] = 'ID';

        return $labels;
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = $this->finder->getUserQuery();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if (!empty($this->created_at)) {
            $date = strtotime($this->created_at);
            $query->andFilterWhere(['>=', 'created_at', $date]);
        }

        if (!empty($this->last_login_at)) {
            $date = strtotime($this->last_login_at);
            $query->andFilterWhere(['>=', 'last_login_at', $date]);
        }

        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['registration_ip' => $this->registration_ip]);

        return $dataProvider;
    }
}
