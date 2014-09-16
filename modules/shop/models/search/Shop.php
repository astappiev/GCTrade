<?php

namespace app\modules\shop\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\shop\models\Shop as ShopModel;

/**
 * Shop represents the model behind the search form about `app\modules\shop\models\Shop`.
 */
class Shop extends ShopModel
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
        $query = ShopModel::find()->where(['status' => ShopModel::STATUS_ACTIVE])->orWhere(['status' => ShopModel::STATUS_DEPENDS]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 10,
                'forcePageParam' => false,
            ],
            'sort'=> ['defaultOrder' => ['updated_at' => SORT_DESC]],
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
