<?php

namespace app\modules\testnay\models;

use app\modules\testnay\models\Options;
use app\modules\testnay\models\Forms;

use Yii;

/**
 * This is the model class for table "items".
 *
 * @property integer $form_id
 * @property integer $type
 * @property string $label
 * @property string $placeholder
 * @property string $ID
 * @property string $class
 * @property string $desc
 * @property integer $status
 * @property integer $required
 * @property integer $editable
 */
class Field extends \yii\db\ActiveRecord
{
    const FIELD_STATUS_ENABLE = 1;
    const FIELD_STATUS_DISABLE = 0;
    const FIELD_REQUIRED_REQUIRE = 1;
    const FIELD_REQUIRED_NOT_REQUIRE = 0;
    const FIELD_EDITABLE_ENABLE = 1;
    const FIELD_EDITABLE_DISABLE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'items';
    }

    public static function getStatusMap()
    {
        return [ self::FIELD_STATUS_ENABLE => 'enable', self::FIELD_STATUS_DISABLE => 'disable' ];
    }

    public static function getRequiredMap()
    {
        return [ self::FIELD_REQUIRED_REQUIRE => 'require', self::FIELD_REQUIRED_NOT_REQUIRE => 'not required' ];
    }

    public static function getEditableMap()
    {
        return [ self::FIELD_EDITABLE_ENABLE => 'editabl', self::FIELD_EDITABLE_DISABLE => 'static' ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['form_id'], 'required'],
            [['form_id', 'type', 'status', 'required', 'editable'], 'integer'],
            [['label', 'placeholder', 'desc'], 'string', 'max' => 255],
            [['ID', 'class'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'field_id' => 'Field ID',
            'form_id' => 'Form ID',
            'type' => 'Type',
            'label' => 'Label',
            'placeholder' => 'Placeholder',
            'ID' => 'ID',
            'class' => 'Class',
            'desc' => 'Desc',
            'status' => 'Status',
            'required' => 'Required',
            'editable' => 'Editable',
        ];
    }

    /**
     * This method deleting relation objects.
     * @return bool
     */
    public function beforeDelete() {

        // stop delete field and option if transaction == false
        if ( !$asd = Yii::$app->db->transaction->isActive) return false;
        /// возможно следует перенести в поведение.
        /// Already in the transaction, because FieldController->actionDeleteFiel() in a transaction.
        $options = $this->options;
        foreach ($options as $option){
           $res = $option->delete();
        }
        //////////////////////////////

        return parent::beforeDelete();
    }

    /**
     * This method returning "form" as relations.
     * @return \yii\db\ActiveQuery
     */
    public function getForm()
    {
        return $this->hasOne(Forms::className(), ['id' => 'form_id']);
    }

    /**
     * This method returning "options" as relations.
     * @return \yii\db\ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasMany(Options::className(), ['field_id' => 'field_id']);
    }
}
