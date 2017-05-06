<?php namespace app\modules\testnay\models\fields;

use yii\base\DynamicModel;
use yii\bootstrap\ActiveField;
use yii\bootstrap\ActiveForm;

class TextFieldModel extends DynamicModel{ // in dev

    private $field;
    private $activeField;

    /** @var ActiveForm $form */
    private $form;

    private $_attributes;

    public function __construct( $field, $form, $config = [])
    {
        $attributes = [ '_'.$field['field_id'] => $field['label'] ];
        $this->field = $field;

        $this->form = $form;
        foreach ($attributes as $name => $value) {
            if (is_int($name)) {
                $this->_attributes[$value] = null;
            } else {
                $this->_attributes[$name] = $value;
            }
        }

        $this->createActiveField();

        parent::__construct($config);
    }

    public function rules(){
        var_dump($this);die();
        return [
            [$this->attributes], 'string', ['max' => 8],
        ];
    }

    public function getActivField(){
        return $this->activField;
    }


    private function createActiveField(){

        $field = $this->field;

        $conf = [
            //'form'=> new ActiveForm(),
            'options'=>[
                'class'=>$field['class'],
                'id'=>$field['ID'],
                'placeholder'=>$field['placeholder'],
                'alt'=>$field['desc'],
            ]
        ];

        /** @var ActiveForm $activField */
        $activeField = $this->form->field($this,  $this->field['label'])->textInput($conf);

        $this->activeField = $activeField;

    }


    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return array_keys($this->_attributes);
    }
}