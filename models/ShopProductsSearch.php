<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ShopProducts;

/**
 * ShopProductsSearch represents the model behind the search form about `app\models\ShopProducts`.
 */
class ShopProductsSearch extends ShopProducts
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'opencart_id', 'category_id', 'quantity', 'status'], 'integer'],
            [['name', 'sku', 'image', 'date_added', 'date_modified'], 'safe'],
            [['price'], 'number'],
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
        $query = ShopProducts::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if($this->category_id) {
            //get all child category id
            $childIdsCategory = ShopCategories::getNestedChildsCategoryIds($this->category_id);//array($this->category_id);

            $query->andFilterWhere(['in','category_id',$childIdsCategory]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'opencart_id' => $this->opencart_id,
//            'category_id' => $this->category_id,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'date_added' => $this->date_added,
            'date_modified' => $this->date_modified,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }


    public function searchSelectionProducts($params,$defaultPageSize)
    {
        $query = ShopProducts::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>[
                'enableMultiSort'=>true
            ],
            'pagination' => [
                'pageSize' => $defaultPageSize,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if($this->category_id) {
            //get all child category id
            $childIdsCategory = ShopCategories::getNestedChildsCategoryIds($this->category_id);//array($this->category_id);

            $query->andFilterWhere(['in','category_id',$childIdsCategory]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'opencart_id' => $this->opencart_id,
//            'category_id' => $this->category_id,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'date_added' => $this->date_added,
            'date_modified' => $this->date_modified,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }
}
