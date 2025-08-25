<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "preguntas".
 *
 * @property int $id
 * @property string $pregunta
 * @property int $status
 */
class Preguntas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'preguntas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pregunta'], 'required'],
            [['status'], 'integer'],
            [['pregunta'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pregunta' => 'Pregunta',
            'status' => 'Status',
        ];
    }
}
