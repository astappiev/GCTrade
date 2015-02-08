<?php

namespace app\modules\auction\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\auction\models\Lot as LotModel;

/**
 * Lot represents the model behind the search form about `app\models\Lot`.
 */
class Lot extends LotModel
{
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['name', 'description', 'logo', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = LotModel::find()->where(['status' => [Lot::STATUS_PUBLISHED, Lot::STATUS_STARTED, Lot::STATUS_FINISHED, Lot::STATUS_CLOSED]]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => [
                'time_elapsed' => SORT_DESC,
            ]]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'logo', $this->logo]);

        return $dataProvider;
    }
}
