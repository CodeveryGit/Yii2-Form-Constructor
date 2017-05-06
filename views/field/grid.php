<?php

/**
 * @var View $this
 */
use yii\web\View;
use \yii\grid\GridView;
use \yii\bootstrap\Modal;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap\Html;
use \yii\bootstrap\ActiveForm;
use app\modules\testnay\models\Field;
use app\modules\testnay\models\Options;
use app\modules\testnay\models\fields\BaseFields as BF;
use app\modules\testnay\widget\optionlabel\OptionFieldGrid;

/** @var \app\modules\testnay\Testnay $module  */
$module = \Yii::$app->controller->module;

$fieldId = \Yii::$app->request->get('field_id', '');

$urlManager = \Yii::$app->urlManager;

$urlDisableField = $urlManager->createUrl(["{$module->id}/field/disable-field", 'field_id'=>$fieldId]);
$urlDeleteField = $urlManager->createUrl(["{$module->id}/field/delete-field", 'field_id'=>$fieldId]);
$urlForAddOption =  $urlManager->createUrl(["{$module->id}/options/add-option", 'field_id'=>$fieldId]);
$urlReloadAfterRemoveField = $urlManager->createUrl(["{$module->id}/form/edit", 'id'=>$form_id]);
$urlOpenFieldToEdit = $urlManager->createUrl(["{$module->id}/form/edit", 'id'=>$form_id]);
?>

    <div class="row top-buffer">
        <div class="col-md-8 col-md-offset-2">
            <?= GridView::widget([
                'options'  =>  ['id' => 'fields_grid'],
                'dataProvider' => $fieldDataProvider,
                'rowOptions' => function ($model, $key, $index, $grid) use ($urlOpenFieldToEdit) {
                                    return ['id' => $model['field_id'],
                                            'data-field-id'=>$model['field_id'],
                                            'data-open-field-url'=>$urlOpenFieldToEdit,
                                            'onclick' => "setDataToModalWindow(". $model['field_id'].");"]; },
                'columns' => [
                    [
                        'attribute' => 'field_id',
                        'format' => 'text',
                        'label' => 'ID',
                    ],
                    [
                        'attribute' => 'form_id',
                        'format' => 'text',
                        'label' => 'formID',
                    ],
                    [
                        'attribute' => 'label',
                        'format' => 'text',
                        'label' => 'Label',
                    ],
                    [
                        'attribute' => 'type',
                        'format' => 'text',
                        'label' => 'Type',
                    ],
                    [
                        'attribute' => 'placeholder',
                        'format' => 'text',
                        'label' => 'Placeholder',
                    ],
                ],
            ]) ?>
        </div>
    </div>
<?php

    $addFieldButton = <<<HTML
        <div class="row">
            <div class="col-md-6 col-md-offset-4">
                <button id="add_options_to_field" data-add-option-url="$urlForAddOption" class="btn btn-success" type="button"> + New options </button>
            </div>
        </div>
HTML;

    $header = <<<HTML
        <div class="row row-centered">
            <div class="col-md-6 col-md-offset-3 col-centered">
                <button id="save_form_field" class="btn btn-success" type="button">Save</button><!-- handler this button in FormController -->
                <button id="disable_form_field" data-disable-url="$urlDisableField" class="btn btn-primary" type="button">Disable</button>
                <button id="remove_form_field" data-remove-pjax-url="$urlReloadAfterRemoveField" data-delete-url="$urlDeleteField" class="btn btn-danger" type="button">Remove</button>
            </div>
            <div class="row row-centered">
                <div class="col-md-6 col-md-offset-3 col-centered">
                    <span class="btn-danger"></span>
                </div>
            </div>
        </div>
HTML;


    Modal::begin([
        'header' => $header,
        'footer' => '',
        'options'  =>  ['id' => 'fields_modal'],
    ]);

    Pjax::begin(['options'  =>  ['id' => 'in_fields_modal'],]);

        //$fieldId = (Yii::$app->request->get('field_id', ''));

        $model = ($fieldId)
                    ? Field::findOne($fieldId)
                    : new Field();


        $form = ActiveForm::begin([
            'method' => 'POST',
            'id' => 'form_fields',
            'enableClientScript' => true,
            //'options' => ['data' => ['pjax' => true]],
            'action' =>  \Yii::$app->urlManager->createUrl(["{$module->id}/field/update-field", 'field_id'=>$fieldId]),
            'layout' => 'horizontal' ]);

        echo $form->field($model, 'field_id')->hiddenInput(['value' => $fieldId])->label(false);
        echo $form->field($model, 'type')->dropDownList(BF::getTypeMap(),['data-field_id'=>$fieldId/*'onchange'=>"setDataToModalWindow($fieldId)"*/]);
        echo $form->field($model, 'label')->input('label');
        echo $form->field($model, 'placeholder')->input('placeholder');
        echo $form->field($model, 'ID')->input('ID');
        echo $form->field($model, 'class')->input('class');
        echo $form->field($model, 'desc')->input('desc');
        echo $form->field($model, 'status')->dropDownList(Field::getStatusMap());
        echo $form->field($model, 'required')->dropDownList(Field::getRequiredMap());
        echo $form->field($model, 'editable')->dropDownList(Field::getEditableMap());
        //echo Html::submitButton('Отправить', ['class' => 'btn btn-primary']);


        $inputTypeArray = [BF::TYPE_FIELD_MULTIPLY_SELECT_BOX,BF::TYPE_FIELD_CHECKBOX,
                            BF::TYPE_FIELD_SELECT_BOX,BF::TYPE_FIELD_RADIO];
        if (in_array($model->type, $inputTypeArray)) {
            echo OptionFieldGrid::widget(['fieldId'=>$fieldId]); // render Grit with options for field.
            echo $addFieldButton; // render button '+options' for field.
        }

        ActiveForm::end();

        echo Html::a("get item list", Url::to(['field/index','id'=>$form_id, 'field_id' => $fieldId], true), ['id'=>'reload_field_grid', 'class' => 'btn btn-lg btn-primary hidden']);

    Pjax::end();

    Modal::end();


$javascript = <<<JS

function setDataToModalWindow(field_id){
          /*
            console.dir(field_id);
            var buttom = $('#reload_field_grid');
            var link = buttom.attr("href")+'&field_id='+field_id;
            buttom.attr("href", link);
            buttom.click();
            */
        };
JS;
$this->registerJs($javascript, \yii\web\View::POS_HEAD);

$urlForAddOption =  \Yii::$app->urlManager->createUrl(["{$module->id}/options/add-option", 'field_id'=>$fieldId]);

//$javascript = "var url_for_add_option = '$urlForAddOption'; ";
$javascript = <<<JS
        
         $(document).on('click','#fields_grid tbody tr', function(event){
                var that = this;
                var field_id = $(event.target).parent().data('field-id');
                var url = $(event.target).parent().data('open-field-url');
                url = url +'&field_id='+ field_id;
                var handler = function(){ $.pjax.reload({container: "#in_fields_modal", async:true}); $('#fields_modal').modal('show');};
                $.pjax({url: url, container: '#in_edit_modal'});
                return false;
         });

        // for "SAVE" item button
        $(document).on('click','#save_form_field', function(event){
                var form = $('#form_fields');
                var options = createFormDataOptions();
                options = ($.param(options));
                _ajax(form, _reloadFieldsModalPJAX, options);
        });

        // for "DISABLE" item button
        $(document).on('click','#disable_form_field', function(event){
                var that = this;
                var handler = addOptionsHandler;
                var url = $(event.target).data('disable-url');
                $.ajax(url , {
                success: $.proxy(handler, that),
            });
            return false;
        });
        
        // for "REMOVE" item button
        $(document).on('click','#remove_form_field', function(event){
                var that = this;
                var reloadUrl = $(event.target).data('remove-pjax-url');
                var handler = function(){window.location.href = reloadUrl; };
                var url = $(event.target).data('delete-url');
                $.ajax(url , {
                success: $.proxy(handler, that),
            });
            return false;
        });
        
         // for "+OPTION" item button
        $(document).on('click','#add_options_to_field',function(event){
                var that = this;
                var handler = addOptionsHandler;
                var url = $(event.target).data('add-option-url');
                $.ajax(url , {
                success: $.proxy(handler, that),
            });
            return false;
        });
          // for "X" - delete option
        $(document).on('click','#options_grid span', function(event){
            var deleteFieldUrl = $(this).data('url');
            var that = this;
            var handler = addOptionsHandler;  
            $.ajax(deleteFieldUrl , {
                success: $.proxy(handler, that),
            });      
            return false;
        });

        // change dropDownList with FIELD-TYPE
        $(document).on('change','#field-type', function(event){
            var field_id = $(event.target).closest('select').data('field_id');
            changeFieldDropDownListHandler(field_id);
        });

        // The handler of any successful query from 'pjax'.
        $(document).on('pjax:success', function(jqEvent,rout,d,c,options) { //  изменитьт на Arguments[4]
            if(options.stopMessage){return false;};
            $('#fields_modal').modal('show');
            console.log('show model');
        });
        
        function addOptionsHandler(){
            console.log('add_options_to_field');
            _reloadFieldsModalPJAX(); /// reload for show field after click "+options"
        }
        
        function changeFieldDropDownListHandler(field_id){
            console.log('changeFieldDropDownList');
            form = $('#form_fields');
            _ajax(form, _reloadFieldsModalPJAX); /// reload for show button "+options"
        };
        
        function _reloadFieldsModalPJAX(){
            $.pjax.reload({container: "#in_fields_modal", async:true});
        };

        
        function _ajax(form , callback, data){
            var xdata    = '&' + data || '';
            var handler = callback || null;
            var that    = this;
            $.ajax({
                    url    : form.attr('action'),
                    type   : 'post',
                    data   : form.serialize() + xdata,
                    
                    success: $.proxy(handler, that),
                    success2: function (response) 
                    {
                       
                                                //var errMess =  [];
                                                //if (!response.success) {}
                                                //    for(var err in response.error)
                                               //     {
                                                //        ///debugger;
                                                //        /// var arr = [];
                                                //        ///var key = err;
                                                //        ///var val = response.error[err][0];
                                                //        /////arr[response.error[err]] =  response.error[err][0];
                                                //        ///$('[for="'+key+'"]',form).parent('.form-group').addClass("error");
                                                //        ///$('.btn-danger', 'span').html(val);
                                                //        /////response.error[err][0];
                                                //    }
                                                //    /*$('.btn-danger', 'span').html(errMess);*/
                    
                    },
                    error  : function () 
                    {
                        console.log('internal server error');
                    }
                });
        };
        
        function createFormDataOptions() {
            var rows = $('#options_grid tbody tr'); 
            if (rows.length !== 0) {
                var res = {options:{}};
                $.each(rows, function (indexTR, value) {
                    var op = {};
                    op.id = $(value).data('key');
                    op.field_id = ($(value).find('span').data('field-id'));
                    var td = ($(value).find('td'));
                    $.each(td, function (indexTD, value) {
                        if (indexTD==1) { op.name = $(value).text(); }
                        if (indexTD==2) { op.value = $(value).text(); }
                        res.options[indexTR]= op;
                    });
                })
                return res;
            }
            return {};
        };

JS;
$this->registerJs($javascript);

?>