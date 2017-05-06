<?php namespace app\modules\testnay\builder;

use app\modules\testnay\models\Forms;
use app\modules\testnay\builder\generators\settings_generators\OptionsConfigGenerator;
use app\modules\testnay\builder\generators\settings_generators\FieldsConfigGenerator;
use app\modules\testnay\base\BaseConfigGenerator;
use app\modules\testnay\base\IConfigGenerator;
use yii\base\Component;
use app\modules\testnay\Testnay;
use Yii;

class ConfigGenerator extends Component implements IConfigGenerator
{
    /**
     * @var Forms
     */
    public $form = 'test data';

    /**
     * @var Array
     */
    private $config = [];

    /**
     * Configs generators
     * @var BaseConfigGenerator[] - [ fields , form , options , settings ]
     */
    private $generators = [];

    public function init()
    {
        parent::init();

        $this->form = Testnay::$fm->form;

    }

    public function getFormConfig(){
        return $this->config['forms'];
    }

    public function getSettingsConfig(){
        return $this->config['settings'];
    }

    public function getFieldsConfig(){
        return $this->config['fields'];
    }

    /**
     * @param int $id - field_id
     * @return array - options
     */
    public function getOptionsConfig($id){
        return $this->config['fields'][$id]['options'];
    }


    /**
     * Returns array with configuration $key => $value.
     * @return Array
     */
    public function getConfig()
    {
        if ( !count($this->config) ){
            /** @var BaseConfigGenerator $generator */
            foreach ($this->generators as $generator){
                if ($generator instanceof OptionsConfigGenerator) continue;
                $this->config[$generator->getSetName()] = $generator->getConfig($this->form); // arrayPUSH
            }
        }
        return $this->config;
    }

    /** FOR TEST */
    public function getForm()
    {
        return $this->form;
    }
    
    /**
     * 
     * @param String $key - generator name
     * @return BaseConfigGenerator|null
     */
    public function __get( $key )
    {
        if (key_exists($key, $this->generators)) {
            return $this->generators[ $key ];
        }
        return null;
    }
    
    /**
     * 
     * @param String $key - generator name
     * @param BaseConfigGenerator $value
     * @return Boolean Description
     */
    public function __set(  $key, $value )
    {
        if ($key === 'generators'){
            foreach ($value as $index => $generator){
                if ($generator instanceof BaseConfigGenerator){
                    $this->generators[ $index ] = $generator;
                }
            }
            return true;
        }
            return false;
    }
}