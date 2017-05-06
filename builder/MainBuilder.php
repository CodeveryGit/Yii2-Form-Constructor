<?php namespace app\modules\testnay\builder;

use app\modules\testnay\Testnay;
use yii\base\Component;
use app\modules\testnay\builder\ConfigGenerator;

class MainBuilder extends Component
{

    /**
     * @var Testnay
     */
    private $module;

    public $activeForm;

    /**
     * MainBuilder constructor.
     * @param array $config
     */
    public function __construct( array $config = [])
    {
        parent::__construct($config);
    }

    public function init()
    {
        parent::init();

        //$this->setComponents();
    }

    /**
     * set in $this->module Testnay Instance.
     * @param Testnay $value
     */
    public function setModule($value){
        if ($value instanceof Testnay && $this->module === null){
            $this->module = $value;
        }
    }

    public function getModule(){
        return $this->module;
    }

}