<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProjectEquipmentsView;

/**
 * ProjectEquipmentsViewSearch represents the model behind the search form of `\app\models\ProjectEquipmentsView`.
 */
class ProjectEquipmentsViewSearch extends ProjectEquipmentsView
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'project_id', 'ts', 'equipment_id', 'quantity'], 'integer'],
            [['project_name', 'office', 'equipment', 'e_desc', 'pe_desc'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = ProjectEquipmentsView::find();

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
            'project_id' => $this->project_id,
            'ts' => $this->ts,
            'equipment_id' => $this->equipment_id,
            'quantity' => $this->quantity,
        ]);

        $query->andFilterWhere(['ilike', 'project_name', $this->project_name])
            ->andFilterWhere(['ilike', 'office', $this->office])
            ->andFilterWhere(['ilike', 'equipment', $this->equipment])
            ->andFilterWhere(['ilike', 'e_desc', $this->e_desc])
            ->andFilterWhere(['ilike', 'pe_desc', $this->pe_desc]);

        return $dataProvider;
    }
}
