<?php namespace app\modules\testnay\builder\generators\mixins;

use app\modules\testnay\builder\MainBuilder;
use app\modules\testnay\models\fields\BaseFields;
use app\modules\testnay\models\Field;
use app\modules\testnay\models\fields\TextFieldModel;
use yii\base\Behavior;
use yii\bootstrap\ActiveField;
use yii\bootstrap\ActiveForm;
use yii\base\DynamicModel;
use yii\helpers\Html;

class FieldBuilder extends Behavior{

    /**
     * @var MainBuilder $parent
     */
    private $parent;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->parent = $this->owner;
    }

    public function getFields(array $fields)
    {
        /** @var ActiveForm $form */
        $form = $this->owner->activeForm;

        foreach ($fields as $field){

            if($field['status'] === Field::FIELD_STATUS_DISABLE ) continue;

            if(in_array($field['type'], BaseFields::getTypeWithMultipleOptions())){
                echo $this->getOptionsMultipleField($field);
            }else{
                echo $this->getField($field);
            }
        }
    }

    public function getActiveFields(array $fields, $form)
    {
        foreach ($fields as $field) {
            if ($field['type'] === BaseFields::TYPE_FIELD_TEXT) {
                //$model = $this->getDynamicModel($field);

                $model = new TextFieldModel($field, $form);
                echo $model->getActivField();

            }
        }
    }

    private function getOptionsMultipleField($fieldConf)
    {
        //var_dump($fieldConf);die();

        $htmlOption = [
            'class'=>$fieldConf['class'],
            'id'=>$fieldConf['ID'],
            'placeholder'=>$fieldConf['placeholder'],
            'alt'=>$fieldConf['desc'],
            'multiple'=> true,
        ];

        $activField = BaseFields::getHtmlInput($fieldConf, $htmlOption);

        return $activField;
    }

    private function getField($fieldConf)
    {
        $htmlOption = [
            'class'=>$fieldConf['class'],
            'id'=>$fieldConf['ID'],
            'placeholder'=>$fieldConf['placeholder'],
            'alt'=>$fieldConf['desc'],
        ];

        $activField = BaseFields::getHtmlInput($fieldConf, $htmlOption);

        return $activField;
    }


    private function getDynamicModel($field)
    {
        $attributes = [
            '_'.$field['field_id'] => $field['label'],
        ];
        $model = new DynamicModel($attributes);

        $model->addRule(['_'.$field['field_id']], 'string', ['max' => 8]);
        return $model;
    }

    private function getActiveField($model, $field)
    {

        $conf = [
            'form'=> new ActiveForm(),
            'options'=>[
                'class'=>$field['class'],
                'id'=>$field['ID'],
                'placeholder'=>$field['placeholder'],
                'alt'=>$field['desc'],
            ]
        ];

        $field = new ActiveField($model,  $field['label']);

        return $field;

    }


}