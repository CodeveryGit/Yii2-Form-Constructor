<?php  namespace app\modules\testnay\controllers;

use app\modules\testnay\models\FormSettings;
use app\modules\testnay\models\Options;
use numibu\Yii2FormBuilder\FormsModel;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use app\modules\testnay\models\Forms;
use Yii;
use yii\web\View;

/**
 * Form controller for the `form` module
 */
class OptionsController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public function actionAddOption(){

       if (Yii::$app->request->isAjax) {
           $optionItem = new Options();
           $optionItem->field_id = Yii::$app->request->get('field_id');
           if ($optionItem->save()) {
               Yii::$app->response->format = Response::FORMAT_JSON;
               return [ true ];
           }else{
               throw new \yii\web\HttpException();
               Yii::$app->end();
           }
       }
    }

    /**
     * @return bool|Options
     */
    public function actionUpdateOptions(){
        $options = Yii::$app->request->post('options');
        //var_dump($options);die();
        //if(count($options)); return false;
        foreach ($options as $option){ //////////////////////// !!!! Options::loadMultiple()
            $optObj = Options::findOne($option['id']);
            if ($optObj instanceof Options) {
                $optObj->name   = $option['name'];
                $optObj->value  = $option['value'];
                $optObj->save();
                if ($optObj->hasErrors()){return $optObj;}
            }
        }
        return false;
    }

    public function actionUpdateOption(){

    }

    public function actionDeleteOption($option_id){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $option = Options::findOne($option_id);

        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            if ( !$option->delete()) throw new \Exception( 'Deleting a Option has error' );
            $transaction->commit();
            return ['success' => true];
        } catch ( \Exception $e)
        {
            $transaction->rollBack();
            return ['success' => false, 'error' => $e->getMessage() ];
        }
    }


}