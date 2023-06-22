<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BaseProjects;

/**
 * ProjectsSearch represents the model behind the search form of `app\models\Projects`.
 */
class BaseProjectsSearch extends BaseProjects
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'ts', 'project_weight'], 'integer'],
            [['project_name', 'office'], 'safe'],
            [['enabled', 'visible'], 'boolean'],
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
        $query = BaseProjects::find();

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
            'ts' => $this->ts,
            'enabled' => $this->enabled,
            'visible' => $this->visible,
            'project_weight' => $this->project_weight,
        ]);

        $query->andFilterWhere(['ilike', 'project_name', $this->project_name])
            ->andFilterWhere(['ilike', 'office', $this->office]);

        return $dataProvider;
    }
}
