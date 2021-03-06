<?php namespace app\modules\testnay\builder\generators\mixins;

use yii\base\Behavior;
use app\modules\testnay\builder\MainBuilder;
use yii\bootstrap\ActiveForm;

class FormBuilder extends Behavior{

    /**
     * @var MainBuilder $parent
     */
    private $parent;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        //$this->parent = $this->owner;
    }

    public function createForm(array $conf){
        $form = new ActiveForm();
        $form->action = $conf['action'];

        $this->owner->activeForm = $form;
        return $form;
    }
}