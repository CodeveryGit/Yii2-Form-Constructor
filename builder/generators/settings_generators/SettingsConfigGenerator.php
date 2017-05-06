<?php namespace app\modules\testnay\builder\generators\settings_generators;

use app\modules\testnay\base\BaseConfigGenerator;
use app\modules\testnay\models\FormSettings;
use app\modules\testnay\models\Forms;
use yii\db\ActiveRecord;

class SettingsConfigGenerator extends BaseConfigGenerator
{

    /**
     * Returns array with configuration $key => $value.
     * @param ActiveRecord $form - formModels
     * @return Array
     */
    public function getConfig(ActiveRecord $form)
    {
        /** @var FormSettings $settings */
        $settings = $form->settings;
        return $settings->attributes ;
    }

    /**
     * Return name of generator.
     * As example: name for KEY in config array.
     * @return String
     */
    public function getSetName()
    {
        return 'settings';
    }
}