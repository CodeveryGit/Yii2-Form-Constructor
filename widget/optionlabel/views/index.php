<?php

use yii\grid\GridView;

?>
<div class="raw">
    <div class="raw">
        <p>
        <?= GridView::widget([
            'dataProvider' => $options,
            'layout'=>"{items}",
            'options' => ['id'=>'options_grid'],
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'options' => ['width' => '10']
                ],

                [
                    'attribute' => 'name',
                    'format' => 'html',
                    'label' => 'name',
                    'contentOptions' => ['contenteditable' => 'true'],
                    'value' => function ($model) {
                        return $model->name;
                    },
                    'options' => ['width' => '100']

                ],
                [
                    'attribute' => 'value',
                    'format' => 'text',
                    'label' => 'value',
                    'contentOptions' => ['contenteditable' => 'true'],
                    'value' => function ($model) {
                        return $model->value;
                    },
                    'options' => ['width' => '100']
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '',
                    'template' => '{delete-option}',
                    'buttons' => [
                        'delete-option' => $widgetInstance->deleteOptionButton(),
                    ],
                    'options' => ['width' => '10']
                ],
            ],
        ]); ?>
        </p>
    </div>
</div>