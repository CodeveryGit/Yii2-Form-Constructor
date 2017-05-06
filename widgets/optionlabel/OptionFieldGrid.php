<?php namespace app\modules\testnay\widget\optionlabel;


use yii\base\Widget;
use app\modules\testnay\models\Options;
use yii\data\ActiveDataProvider;
use yii\bootstrap\Html;


class OptionFieldGrid extends Widget
{
    /**
     * @var int
     */
    public $fieldId;

    public function init()
    {
        parent::init();
    }

    /**
     *  This Method returning rendered 'view'.
     * @return string
     */
    public function run() {
        $config = ['query' => Options::find()->where(['field_id'=> $this->fieldId])];
        $optionsActivDataProvider = new ActiveDataProvider($config);
        return $this->render('index', [
            'options' => $optionsActivDataProvider,
            'widgetInstance' => $this
        ]);
    }

    /**
     * This method returning a template for 'delete-option' button
     * @return string
     */
    public function deleteOptionButton()
    {
        $fieldID = $this->fieldId;

        /** @var \app\modules\testnay\Yii2form $module  */
        $module = \Yii::$app->controller->module;

        return  function($url, $model) use ($fieldID, $module){
            $url = \Yii::$app->urlManager->createUrl(["{$module->id}/options/delete-option", 'option_id'=>$model->id]);
            return Html::a(
                '<span class="glyphicon glyphicon-remove" data-url="'.$url.'" data-field-id="'.$fieldID.'" data-option-id="'.$model->id.'"></span>',
                $url);
        };
    }




}