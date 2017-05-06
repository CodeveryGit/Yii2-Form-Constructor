<?php namespace app\modules\testnay\base;

use yii\base\Component;
use app\modules\testnay\models\Forms;
use yii\db\ActiveRecord;

abstract class BaseConfigGenerator extends Component
{
    /**
     * Returns array with configuration. $key => $value.
     * @param ActiveRecord $form
     * @return mixed
     */
    abstract public function getConfig(ActiveRecord $form);

    /**
     * Return name of generator.
     * As example: name for KEY in config array.
     * @return String
     */
    abstract public function getSetName();

}
