<?php namespace app\modules\testnay\controllers;

use yii\web\Controller;
use yii\web\Response;
use Yii;

/**
 * Form controller for the `form` module
 */
class BaseController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public function init()
    {
        parent::init();
        // disable short_code replace in controller of module .
        Yii::$app->response->off(Response::EVENT_AFTER_PREPARE, ['app\modules\testnay\Testnay', 'shortTagHandler']);
    }

    /**
     * @return string ModuleName (as id)
     */
    public function getThisModuleName()
    {
        return Yii::$app->controller->module->id;
    }
}