<?php

namespace app\modules\testnay\models;

use Yii;

/**
 * This is the model class for table "form_settings".
 *
 * @property integer $form_id
 * @property string $title
 * @property string $action
 * @property string $desc
 * @property string $trigger
 */
class FormSettings extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 7;
    const SCENARIO_EDIT = 8;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'form_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['form_id'], 'required', 'on'=> self::SCENARIO_CREATE],
            [['action', 'trigger', 'form_id'], 'required', 'on'=> self::SCENARIO_EDIT ],
            [['form_id'], 'integer'],
            [['desc', 'trigger'], 'string'],
            [['title'], 'string', 'max' => 25],
            [['action'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'form_id' => 'Form ID',
            'title' => 'Title',
            'action' => 'Action',
            'desc' => 'Description',
            'trigger' => 'Trigger (.js)',
        ];
    }

    /**
     * This method returning "form" as relations.
     * @return \yii\db\ActiveQuery
     */
    public function getForm()
    {
        return $this->hasOne(Forms::className(), ['id' => 'form_id']);
    }
}
