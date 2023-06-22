<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProjectSitexView;

/**
 * ProjectSitexViewSearch represents the model behind the search form of `app\models\ProjectSitexView`.
 */
class ProjectSitexViewSearch extends ProjectSitexView
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'project_id', 'sitex_id', 'area', 'center_id', 'phase', 'weight'], 'integer', 'message'=>''],
            [['name', 'abbr', 'type', 'center_name', 'center_abbr', 'address'], 'safe'],
            [['done'], 'boolean'],
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
        $query = ProjectSitexView::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $this->load($params);

        // convert area persian number to english integer
        if(!empty($this->area))
        {
            if(!is_numeric($this->area))
            {
                $area = \app\components\Jdf::tr_num($this->area);
                $area = intval($area);
                $this->area = $area;
            }
        }

        if(!empty($this->phase))
        {
            if(!is_numeric($this->phase))
            {
                $phase = \app\components\Jdf::tr_num($this->phase);
                $phase = intval($phase);
                $this->phase = $phase;
            }
        }


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'project_id' => $this->project_id,
            'sitex_id' => $this->sitex_id,
            'area' => $this->area,
            'center_id' => $this->center_id,
            'done' => $this->done,
            'phase' => $this->phase,
            'weight' => $this->weight,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'abbr', $this->abbr])
            ->andFilterWhere(['ilike', 'type', $this->type])
            ->andFilterWhere(['ilike', 'address', $this->address]);

        $query->andFilterWhere(["or", ['ilike', 'center_name', $this->center_name],
                       ['and', ['type'=>'مرکز'],['ilike', 'name', $this->center_name]]] );

        $query->andFilterWhere(["or", ['ilike', 'center_abbr', $this->center_abbr],
            ['and', ['type'=>'مرکز'],['ilike', 'abbr', $this->center_abbr]]] );

        return $dataProvider;
    }
}
