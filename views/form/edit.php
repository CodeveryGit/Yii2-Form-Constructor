<?php
/** Edit field form CONTROLLER = FormController , ACTION = Edit*/
/**
 * @var View $this
 */
use yii\web\View;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use app\modules\testnay\models\Forms;
use \yii\grid\GridView;
use yii\helpers\Url;
use \yii\widgets\Pjax;

/** @var \app\modules\testnay\Testnay $module  */
$module = \Yii::$app->controller->module;
$path = $module->alias;
$urlFormSetting = \Yii::$app->urlManager->createUrl(["{$module->id}/form/edit", 'id'=>$formID]);
?>

    <div class="container">
        <div class="row ">
            <div class="col-md-12">
                <?php $form = ActiveForm::begin([
                    'method' => 'POST',
                    'id' => 'form_settings',
                    'action' =>  \Yii::$app->urlManager->createUrl("{$module->id}/form/edit"),
                    'layout' => 'horizontal' ]);
                ?>

            <?= $form->field($formSettingModel, 'form_id')->hiddenInput(['value' => $formID])->label(false); ?>

                <div class="row">
                    <div class="col-md-6 col-md-offset-4">
                        <button id="add_to_form_field" class="btn btn-success" type="button"> + New field</button>
                        <button id="save_form_setting" class="btn btn-primary" type="button"> ! Save </button>
                        <?php //echo Html::submitButton('! Save', ['class' => 'btn btn-primary']) ?>
                        <a href="<?= Url::to(['form/index'])?>" class="btn btn-default" type="submit"> < Cancel</a>
                    </div>
                </div>

                <div class="row top-buffer">
                    <div class="col-md-6"><?= $form->field($formSettingModel, 'title') ?></div>
                    <div class="col-md-6"><?= $form->field($formSettingModel, 'action') ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6"><?= $form->field($formSettingModel, 'desc')->textarea() ?></div>
                    <div class="col-md-6"><?= $form->field($formSettingModel, 'trigger')->textarea() ?></div>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
            <div class=" row top-buffer">
                <?php Pjax::begin(['options'  =>  ['id' => 'in_edit_modal'],]); ?>
                <?= Html::a("get item list", Url::to(['','id'=>$formID], true), ['id'=>'reload_field_grid', 'class' => 'btn btn-lg btn-primary hidden']) ?>
                <?= $fieldGrid ?>
                <?php Pjax::end(); ?>
            </div>
         </div>


    </div>


    <style>

        .top-buffer { margin-top:40px; }
        .pointer {cursor: pointer;}

    </style>

<?php
$javascript = "var url_form_index = '". Url::to(['form/index']) ."';";

$javascript .= <<<JS
        // edit field
        $('td','#fields_grid').click(function (e) {
            var id = $(this).data('id');
            if (typeof id != "undefined") {
                location.href = url_form_index + '&id=' + id;
            }
        });

        $('#save_form_setting').on('click', function(event){
                var that = this;
                var url = '$urlFormSetting';
                var form = $('#form_settings');//.serialize();
                var handler = function(){ return false;};
                $.ajax(url , {
                    type:'POST',
                    data: form.serialize(),
                    success: $.proxy(handler, that),
            });
                return false;
         });
JS;

$this->registerJs($javascript);
