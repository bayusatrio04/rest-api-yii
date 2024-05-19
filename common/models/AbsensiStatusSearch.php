<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AbsensiLog;

/**
 * AbsensiStatusSearch represents the model behind the search form of `common\models\AbsensiLog`.
 */
class AbsensiStatusSearch extends AbsensiLog
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_absensi_type', 'id_absensi_status', 'created_by'], 'integer'],
            [['tanggal_absensi', 'waktu_absensi', 'latitude', 'longitude', 'bukti_hadir', 'created_at', 'updated_at'], 'safe'],
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
        $query = AbsensiLog::find();

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
            'id_absensi_type' => $this->id_absensi_type,
            'id_absensi_status' => $this->id_absensi_status,
            'tanggal_absensi' => $this->tanggal_absensi,
            'waktu_absensi' => $this->waktu_absensi,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'latitude', $this->latitude])
            ->andFilterWhere(['like', 'longitude', $this->longitude])
            ->andFilterWhere(['like', 'bukti_hadir', $this->bukti_hadir])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'updated_at', $this->updated_at]);

        return $dataProvider;
    }
}
