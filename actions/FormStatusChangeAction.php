<?php namespace app\modules\testnay\actions;

use app\modules\testnay\models\Forms;
use yii\base\Action;

/**
 * Class FormStatusChangeAction
 * This action for chang status a form
 * @package app\modules\testnay\actions
 */

class FormStatusChangeAction extends Action
{
    /**
     * @param Integer $id  - formID for change.
     * @return String - rendered of view/index template.
     */
    public function run($id)
    {
        $form = Forms::findOne(['id'=>$id]);
        $form->status = ($form->status === Forms::DISABLE_FORM)
            ? Forms::ENABLE_FORM
            : Forms::DISABLE_FORM;
        $form->save();
        return $this->controller->actionIndex();
    }
}