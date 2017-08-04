<?php

namespace app\models\user;

use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about User.
 */
class UserSearch extends \dektrium\user\models\UserSearch
{

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

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['registration_ip' => $this->registration_ip]);

        return $dataProvider;
    }
}
