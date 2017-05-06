<?php namespace app\modules\testnay\builder\generators\settings_generators;

use app\modules\testnay\base\BaseConfigGenerator;
use yii\db\ActiveRecord;

class FormConfigGenerator extends BaseConfigGenerator
{

    /**
     * Returns array with configuration $key => $value.
     * @param ActiveRecord $form - formModels
     * @return Array
     */
    public function getConfig(ActiveRecord $form)
    {
        return $form->attributes;
    }


    /**
     * Return name of generator.
     * As example: name for KEY in config array.
     * @return String
     */
    public function getSetName()
    {
        return 'forms';
    }
}