<?php

namespace app\modules\testnay\models;

use Yii;

/**
 * This is the model class for table "options".
 *
 * @property integer $id
 * @property integer $field_id
 * @property string $name
 * @property string $value
 */
class Options extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'options';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['field_id'], 'required'],
            [['field_id'], 'integer'],
            [['name', 'value'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'field_id' => 'Field ID',
            'name' => 'Name',
            'value' => 'Value',
        ];
    }

    /**
     * This method returning "field" as relations.
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(Field::className(), ['field_id' => 'field_id']);
    }
}
