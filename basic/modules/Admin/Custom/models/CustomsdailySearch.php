<?php

namespace app\modules\Admin\Custom\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\Admin\Custom\models\Customsdaily;

/**
 * CustomsdailySearch represents the model behind the search form about `app\modules\Admin\Custom\models\Customsdaily`.
 */
class CustomsdailySearch extends Customsdaily
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'custom_id', 'daily_type_id'], 'integer'],
            [['daily_contents', 'createtime'], 'safe'],
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
        $query = Customsdaily::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'custom_id' => $this->custom_id,
            'daily_type_id' => $this->daily_type_id,
            'createtime' => $this->createtime,
        ]);

        $query->andFilterWhere(['like', 'daily_contents', $this->daily_contents]);

        return $dataProvider;
    }
}
