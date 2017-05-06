<?php namespace app\modules\testnay\builder\generators\settings_generators;

use app\modules\testnay\base\BaseConfigGenerator;
use app\modules\testnay\models\Forms;
use yii\db\ActiveRecord;

class OptionsConfigGenerator extends BaseConfigGenerator
{

    /**
     * Returns array with configuration $key => $value.
     * @param ActiveRecord $form - formModels
     * @return Array
     */
    public function getConfig(ActiveRecord $field)
    {
        $result = [];

        foreach( $field->options as $option )
            $result[$option->id] = $option->attributes ;

        return $result;
    }


    /**
     * Return name of generator.
     * As example: name for KEY in config array.
     * @return String
     */
    public function getSetName()
    {
        return 'options';
    }
}