<?php namespace app\modules\testnay\models\fields;

use yii\base\DynamicModel;
use yii\bootstrap\ActiveForm;

abstract class BaseFieldModel extends DynamicModel // in dev
{

    private $field;
    private $activeField;

    private $_attributes;

    public function __construct( $field, $config = [])
    {
        $attributes = [ '_'.$field['field_id'] => $field['label'] ];
        $this->field = $field;

        foreach ($attributes as $name => $value) {
            if (is_int($name)) {
                $this->_attributes[$value] = null;
            } else {
                $this->_attributes[$name] = $value;
            }
        }

        $firstAttributeValue = $this->firstAttribut();
        $this->$firstAttributeValue = $field['placeholder'];

        parent::__construct($config);
    }

    abstract function createActiveField(ActiveForm $form);

    /**
     * @inheritdoc
     */
    public function firstAttribut()
    {
        return (string) key($this->_attributes);
    }
/*
    public function attributes(){
        $key = $this->firstAttribut();
        var_dump($this->$key);die();
        return $this->$key;
    }
*/
    public function __get($name)
    {
        if ($this->firstAttribut() === $name) {
            return $this->firstAttribut();
        }

        if ($name === 'field') { return $this->field; }
        if ($name === 'activeField') { return $this->activeField; }

        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        if ($this->firstAttribut() === $name)
        {
            $nameAttr = $this->firstAttribut();
            return $this->$nameAttr = $value;
        }
        if ($name === 'activeField') { return $this->activeField = $value; }

        return parent::__set($name, $value);
    }
}

