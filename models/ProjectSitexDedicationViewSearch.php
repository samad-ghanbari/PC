<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProjectSitexDedicationView;

/**
 * ProjectSitexDedicationViewSearch represents the model behind the search form of `app\models\ProjectSitexDedicationView`.
 */
class ProjectSitexDedicationViewSearch extends ProjectSitexDedicationView
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'project_sitex_id', 'area', 'phase', 'project_id', 'project_dedication_id', 'quantity'], 'integer'],
            [['name', 'abbr', 'type', 'address', 'description'], 'safe'],
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
        $query = ProjectSitexDedicationView::find();

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
            'project_sitex_id' => $this->project_sitex_id,
            'area' => $this->area,
            'phase' => $this->phase,
            'project_id' => $this->project_id,
            'project_dedication_id' => $this->project_dedication_id,
            'quantity' => $this->quantity,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'abbr', $this->abbr])
            ->andFilterWhere(['ilike', 'type', $this->type])
            ->andFilterWhere(['ilike', 'address', $this->address])
            ->andFilterWhere(['ilike', 'description', $this->description]);

        return $dataProvider;
    }
}
