<?php

/** FIELDS GRID IN MODAL WINDOW */
/**
 * @var View $this
 */
use yii\web\View;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use app\modules\testnay\models\Forms;
use \yii\grid\GridView;

/** @var \app\modules\testnay\Yii2form $module  */
$module = \Yii::$app->controller->module;
?>

<div class="container">

    <div class="row top-buffer">
    <h1> Module > Yii2FormBuilder </h1>
    </div>

    <div class="row">

        <!--<div class="col-md-1"></div>-->
        <div class="col-md-8 col-md-offset-2">
            <?php $form = ActiveForm::begin([
                    'method' => 'POST',
                    'action' =>  \Yii::$app->urlManager->createUrl("{$module->id}/{$this->context->id}/add-form"),
                    'layout' => 'inline',
                    'fieldConfig' => [
                        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                        'horizontalCssClasses' => [
                            'label' => 'col-sm-4',
                            //'input' => 'col-sm-4',
                            'offset' => 'col-sm-offset-6',
                            'wrapper' => 'col-sm-1',
                            'error' => '',
                            'hint' => '',
                        ],
                    ],
            ]); ?>

            <?= $form->field($formModel, 'name') ?>

            <?= $form->field($formModel, 'status')->dropDownList($status) ?>

            <?= $form->field($formModel, 'short_code') ?>

            <div class="form-group">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
        <!--<div class="col-md-1"></div>-->

    </div>

    <div class="row top-buffer">
        <div class="col-md-8 col-md-offset-2">
            <?= GridView::widget([
                'dataProvider' => $formDataProvider,
                'columns' => [
                    //['class' => \yii\grid\SerialColumn::className()],
                    [
                        'attribute' => 'id',
                        'format' => 'text',
                        'label' => '',
                    ],
                    [
                        'attribute' => 'name',
                        'format' => 'text',
                        'label' => 'Name',
                        'contentOptions' =>  function ($model, $key, $index, $grid) {
                                        return ['data-id' => $model->id, 'id'=>'pointer_cell', 'class' => 'pointer'];} //contentOptions' => ['class' => 'text-center'],
                    ],
                    [
                        'attribute' => 'short_code',
                        'format' => 'text',
                        'label' => 'code',
                        'value' => function($model){
                            return "{form_short_id=$model->short_code}";
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '',
                        'template' => '{enableform}{disableform}{remove-form}',
                        'buttons' => [
                            'enableform' => function($url, $model){
                                if (!$model->status) return "" ;
                                return Html::a(
                                    '<span class="glyphicon glyphicon-plus"></span> ',
                                    $url);
                            },
                            'disableform' => function($url, $model){
                                if ($model->status) return "" ;
                                return Html::a(
                                    '<span class="glyphicon glyphicon-minus"></span> ',
                                    $url);
                            },
                            'remove-form' => function($url, $model){
                                //if (!$model->status) return "" ;
                                return Html::a(
                                    '<span class="glyphicon glyphicon-remove"></span> ',
                                    $url);
                            },
                        ],
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>


<style>

    .top-buffer { margin-top: 40px; }
    .pointer {cursor: pointer;}

</style>

<?php

$this->registerJs("
    //  edit form options ...
    $('td#pointer_cell').click(function (e) {
        var id = $(this).data('id');
        console.log(id);
        if (typeof id != \"undefined\") {
            location.href = '" . \yii\helpers\Url::to(['form/edit']) . "&id=' + id;
        }
           
    });

");

