<?php  namespace app\modules\testnay\controllers;

use app\modules\testnay\models\FormSettings;
use yii\base\View;
use yii\data\ActiveDataProvider;
use app\modules\testnay\models\Forms;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Form controller for the `form` module
 */
class FormController extends BaseController
{
    public function actions(){
        return [
            'enableform' => 'app\modules\testnay\actions\FormStatusChangeAction',
            'disableform' => 'app\modules\testnay\actions\FormStatusChangeAction',
        ];
    }

    public function actionIndex($id = null)
    {
        /*if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }*/
        Yii::$app->response;

        $query = Forms::find();

        $formDataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'status' => SORT_DESC,
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        $formModel = (is_null($id))
            ? new Forms()
            : Forms::findOne($id);

        $statusArray = [ Forms::ENABLE_FORM => 'enable', Forms::DISABLE_FORM => 'disable'];
        return $this->render('index', [ 'formModel'         => $formModel,
                                        'status'            => $statusArray,
                                        'formDataProvider'  => $formDataProvider
                            ]);
    }

    /**
     * Action adding new form.
     * //@param type $id - formID for new form.
     * @return String - rendered of view temlate.
     */
    public function actionAddForm()
    {
        $formModel = new Forms();
        if($formModel->load(Yii::$app->request->post()) && $formModel->validate()) {
            //return $this->redirect(\Yii::$app->urlManager->createUrl());
            if ($formModel->save()) {
                return $this->actionIndex();
                Yii::$app->end();
            }
        }
    }

    /**
     * @param null|int $id
     * @return string
     */
    public function actionEdit($id = null)
    {
        /*var_dump($id);
        var_dump($form_id = Yii::$app->request->post());
        var_dump($form_id = Yii::$app->request->get()); die();*/

        $form_id = Yii::$app->request->post('FormSettings')['form_id'];

        if (is_null($id) && !$form_id) {
            return $this->actionIndex();
            Yii::$app->end();
        }

        $moduleName = $this->getThisModuleName();
        ////////////////////////////////////////////SCENARIO_CREATE/////////////////////////////////////////////////////
        if ($id && !$form_id) {
            $formSetting = (FormSettings::find()->where(['form_id' => $id])->exists())
                ? FormSettings::findOne(['form_id' => $id])
                : new FormSettings();

            $grid = Yii::$app->runAction("$moduleName/field/index",['id'=>$id]);

            $formSetting->scenario = FormSettings::SCENARIO_CREATE;
            if (!$formSetting->form_id) {
                $formSetting->form_id = $id;
                $formSetting->save();
            }

            $this->registerAddFieldJS($id, "$moduleName/field/add-field");
            //var_dump('Отработало создание');die();
            return $this->render('edit', ['formSettingModel' => $formSetting, 'formID' => $id, 'fieldGrid' => $grid] );
        }
        ////////////////////////////////////////////SCENARIO_EDIT///////////////////////////////////////////////////////
        $is_record = FormSettings::find()->where(['form_id' => $form_id])->exists();

        if ($is_record) {
            $formSetting =  FormSettings::findOne(['form_id' => $form_id]);
        }

        $formData = Yii::$app->request->post();
        if (count($formData)) {
            $formSetting->scenario = FormSettings::SCENARIO_EDIT;
            if($formSetting->load($formData) && $formSetting->validate()){
                $formSetting->save();
            }
            $this->registerAddFieldJS($formSetting->form_id, "$moduleName/field/add-field");
            $grid = Yii::$app->runAction("$moduleName/field/index",['id'=>$formSetting->form_id]);
            //var_dump('Отработало редактирование');die();
            return $this->render('edit', ['formSettingModel' => $formSetting, 'formID' => $formSetting->form_id, 'fieldGrid' => $grid] );
        }

    }

    /**
     * This action for deleting a form
     * @param Integer $id  - formID for delete.
     * @return String - rendered of view/index template.
     * @throws NotFoundHttpException
     */
    public function actionRemoveForm($id)
    {
        if (!Forms::find()->where(['id' => $id])->exists()) throw new NotFoundHttpException('Invalid data');
        $form = Forms::findOne(['id'=>$id]);

        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            if ( !$form->delete()) throw new \Exception( 'Deleting a Form has error' );
            $transaction->commit();
        } catch ( \Exception $e)
        {
            $transaction->rollBack();
        }

        return $this->actionIndex();
    }

    private function registerAddFieldJS($formID, $URL = null)
    {
        $url = Yii::$app->urlManager->createUrl([$URL, 'id'=>$formID]);
        $script = " var formID = $formID ;";
        $script .= " var urlToAJAX = '$url' ;";

        $script .=  <<< JS
        
            $('#add_to_form_field').on('click',function(event){
                    $.ajax(urlToAJAX , {
                    success: function(){
                        $.pjax.reload({container: "#in_edit_modal", async:true, stopMessage:true});
                        //event.stopPropagation();
                        return false;
                    }
                });
                return false;
            });     
JS;
        return Yii::$app->controller->view->registerJs($script, \yii\web\View::POS_READY, 'registerAddFieldJS');
    }

}