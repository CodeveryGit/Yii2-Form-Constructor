<?php

namespace app\modules\testnay\models;

use Yii;

/**
 * This is the model class for table "forms".
 *
 * @property integer $id
 * @property string $name
 * @property string $short_code
 * @property integer $status
 */
class Forms extends \yii\db\ActiveRecord
{

    const ENABLE_FORM = 1;
    const DISABLE_FORM = 0;


    /**
     * @var  \yii\db\Transaction - transaction object
     */
    private $transaction;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'forms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'short_code'], 'required'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [['short_code'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'short_code' => 'Short Code',
            'status' => 'Status',
        ];
    }

    /**
     * This method deleting relation objects.
     * @return bool
     */
    public function beforeDelete() {
        /// возможно следует перенести
        $fields = $this->fields;
        foreach ($fields as $field){
            $field->delete();
        }

        $settings = $this->settings;
        $settings->delete();

        //////////////////////////////

        return parent::beforeDelete();
    }

    /**
     * This method returning "settings" as relations.
     * @return \yii\db\ActiveQuery
     */
    public function getSettings()
    {
        return $this->hasOne(FormSettings::className(), ['form_id' => 'id']);
    }

    /**
     * This method returning "fields" as relations.
     * @return \yii\db\ActiveQuery
     */
    public function getFields()
    {
        return $this->hasMany(Field::className(), ['form_id' => 'id']);
    }
}
