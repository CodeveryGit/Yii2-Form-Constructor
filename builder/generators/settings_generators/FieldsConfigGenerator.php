<?php namespace app\modules\testnay\builder\generators\settings_generators;

use app\modules\testnay\builder\generators\settings_generators\OptionsConfigGenerator;
use app\modules\testnay\base\BaseConfigGenerator;
use app\modules\testnay\models\Field;
use app\modules\testnay\models\fields\BaseFields;
use app\modules\testnay\models\Forms;
use app\modules\testnay\Testnay;
use yii\db\ActiveRecord;
use Yii;

class FieldsConfigGenerator extends BaseConfigGenerator
{

    /**
     * Returns array with configuration $key => $value.
     * @param ActiveRecord $form - formModels
     * @return Array
     */
    public function getConfig(ActiveRecord $form)
    {
        $result = [];

        /** @var Field $field */
        foreach ($form->fields as $field){
            if ($field->status === Field::FIELD_STATUS_DISABLE) continue;

            $result[$field->field_id] = $field->attributes;

            if (in_array( $field->type, BaseFields::getTypeWithOptions())){
                $options = Testnay::$fm->configGenerator->options->getConfig($field);
                $result[$field->field_id]['options'] = $options;
            }
        }

        return $result;
    }

    /**
     * Return name of generator.
     * As example: name for KEY in config array.
     * @return String
     */
    public function getSetName()
    {
        return 'fields';
    }
}