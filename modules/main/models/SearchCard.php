<?php

namespace app\modules\main\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\main\models\Card;

/**
 * SearchCard represents the model behind the search form of `app\modules\main\models\Card`.
 */
class SearchCard extends Card
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'flags', 'zone'], 'integer'],
            [['code', 'granted', 'share', 'created_at', 'updated_at'], 'safe'],
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
        $query = Card::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'flags' => $this->flags,
            'zone' => $this->zone,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'granted', $this->granted])
            ->andFilterWhere(['like', 'share', $this->share]);

        return $dataProvider;
    }
}
