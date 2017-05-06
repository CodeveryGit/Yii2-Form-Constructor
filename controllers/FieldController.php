<?php  namespace app\modules\testnay\controllers;

use yii\bootstrap\ActiveForm;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use app\modules\testnay\models\Field;
use Yii;

/**
 * Form controller for the `form` module
 */
class FieldController extends BaseController
{

    /**
     * Action returning fieldInstanse list
     */
    public function actionIndex($id)
    {
        $query = Field::find()->where(['form_id'=>$id]);
        $fieldDataProvider = new ActiveDataProvider(['query' => $query]);
        return $this->renderPartial('grid', ['fieldDataProvider' => $fieldDataProvider, 'form_id' => $id]);
    }

    /**
     * Action adding new field.
     * @return String - rendered of view temlate.
     */
    public function actionAddField($id)
    {
        $field = new Field();
        $field->setAttribute('form_id', $id);
        if ($field->save()){
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [ true ];
        }
        //throw new \yii\web\NotFoundHttpException;
        //Yii::$app->end();
    }

    /**
     * Action edit field.
     * @param null|Integer $id  - fieldID for update.
     * @return string
     */
    public function actionUpdateField($field_id)
    {

        $data = null;
        Yii::$app->response->format = Response::FORMAT_JSON;

        $moduleName = $this->getThisModuleName();
        $optionError = null;
        if (Yii::$app->request->post('options')) {
            $optionError = Yii::$app->runAction("$moduleName/options/update-options");
        }

        if ($optionError!==null && $optionError!==false) {$data = ['success' => false, 'error' => $optionError->validate() ];}

        if (!$field_id) {$data = ['success' => false, 'error' => '$field_id not defined'];}
        //if (!Yii::$app->request->isAjax) {$data = ['success' => false, 'error' => 'not ajax'];}
        if ($data) { return $data; }

        $field = Field::findOne($field_id);
        if ($field->load(Yii::$app->request->post()) && $field->validate()) {
            $field->save();
            $data = ['success' => true];
            return $data;
        }

        $data = ['success' => false, 'error' => ActiveForm::validate($field) ];
        return $data;
    }

    public function actionDisableField($field_id){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $field = Field::findOne(['field_id'=>$field_id]);
        $field->status = Field::FIELD_STATUS_DISABLE;
        if ($field->save()){
            $data = ['success' => true];
            return $data;
        }else{
            $data = ['success' => false, 'error' => ActiveForm::validate($field) ];
            return $data;
        }
    }

    /**
     * Action deleting field.
     * @param null|Integer $id  - fieldID for delete.
     * @return String - rendered of view temlate.
     */
    public function actionDeleteField($field_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $field = Field::findOne(['field_id'=>$field_id]);

        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            if ( !$field->delete()) throw new \Exception( 'Deleting a Field has error' );
            $transaction->commit();
            return ['success' => true];
        } catch ( \Exception $e)
        {
            $transaction->rollBack();
            return ['success' => false, 'error' => $e->getMessage() ];
        }
    }
}