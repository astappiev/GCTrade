<?php

namespace app\modules\shop\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\shop\models\Item as ItemModel;

/**
 * Item represents the model behind the search form about `app\modules\shop\models\Item`.
 */
class Item extends ItemModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'string', 'min' => 3, 'max' => 90],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ItemModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 50,
                'forcePageParam' => false,
            ],
            'sort'=> ['defaultOrder' => ['id_primary' => SORT_ASC, 'id_meta' => SORT_ASC]],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere([
            'like', 'name', $this->name
        ]);

        return $dataProvider;
    }
}
