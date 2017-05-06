<?php namespace app\modules\testnay\models\fields;

use yii\bootstrap\ActiveField;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class BaseFields extends ActiveField{///// remake AS trait OR MIXIN !! // or extends Object

    /**
     * Fields - constant type.
     */
    const TYPE_FIELD_TEXT = 1;
    const TYPE_FIELD_EMAIL = 2;
    const TYPE_FIELD_DATE = 3;
    const TYPE_FIELD_TEXTAREA = 4;
    const TYPE_FIELD_URL = 5;
    const TYPE_FIELD_CHECKBOX = 6;
    const TYPE_FIELD_SELECT_BOX = 7;
    const TYPE_FIELD_MULTIPLY_SELECT_BOX = 8;
    const TYPE_FIELD_RADIO =9;
    const TYPE_FIELD_HIDDEN = 10;
    const TYPE_FIELD_BUTTON = 11;

    public static function getTypeMap()
    {
        $parentClass = new \ReflectionClass('\yii\db\ActiveRecord');
        $constantArrayParent = $parentClass->getConstants();
        $thisClass = new \ReflectionClass(__CLASS__);
        $constantArray = $thisClass->getConstants();
        $result = array_diff_key($constantArray, $constantArrayParent);
        return array_flip($result);
    }

    public static function getTypeWithOptions(){
        return array(7, 8, 9 );//6
    }

    public static function getTypeWithMultipleOptions(){
        return array(8);
    }

    public static function getHtmlInput($fieldConf, $htmlOption, $items = null)
    {
        $options = ( isset($fieldConf['options']) )? $fieldConf['options']: [];

        switch ($fieldConf['type']){
            case self::TYPE_FIELD_TEXT:
                return  Html::input('text', $fieldConf['label'], $fieldConf['label'], $htmlOption);
            case self::TYPE_FIELD_URL:
                return  Html::input('text', $fieldConf['label'], $fieldConf['label'], $htmlOption);
            case self::TYPE_FIELD_EMAIL:
                return  Html::input('text', $fieldConf['label'], $fieldConf['label'], $htmlOption);
            case self::TYPE_FIELD_DATE:
                return  Html::input('text', $fieldConf['label'], $fieldConf['label'], $htmlOption);
            case self::TYPE_FIELD_TEXTAREA:
                return  Html::textarea('text', $fieldConf['label'], null, $htmlOption);
            case self::TYPE_FIELD_CHECKBOX:
                return  Html::checkbox($fieldConf['label'], false, self::getItemsArray($options));
            case self::TYPE_FIELD_SELECT_BOX:
                return  Html::dropDownList($fieldConf['label'], null, self::getItemsArray($options), $htmlOption);
            case self::TYPE_FIELD_MULTIPLY_SELECT_BOX:
                return  Html::dropDownList($fieldConf['label'], null, self::getItemsArray($options), $htmlOption);
            case self::TYPE_FIELD_RADIO:
                return  Html::radioList($fieldConf['label'], null, self::getItemsArray($options), $htmlOption);
            case self::TYPE_FIELD_HIDDEN:
                return  Html::hiddenInput($fieldConf['label'], false, $htmlOption);
            case self::TYPE_FIELD_BUTTON:
                return  Html::button($fieldConf['placeholder']);

        };
    }

    public static function getItemsArray($options){
        return ArrayHelper::map($options, 'value', 'name');
    }
}