<?php namespace app\modules\testnay;


use app\modules\testnay\builder\generators\mixins\FieldBuilder;
use app\modules\testnay\builder\generators\mixins\FormBuilder;
use app\modules\testnay\builder\MainBuilder;
use Yii;
use yii\bootstrap\Nav;
use yii\web\Response;
use yii\base\BootstrapInterface;
use app\modules\testnay\builder\ConfigGenerator;
use app\modules\testnay\builder\generators\settings_generators\FieldsConfigGenerator;
use app\modules\testnay\builder\generators\settings_generators\FormConfigGenerator;
use app\modules\testnay\builder\generators\settings_generators\SettingsConfigGenerator;
use app\modules\testnay\builder\generators\settings_generators\OptionsConfigGenerator;

class FormBuilderBootstrapClass implements BootstrapInterface
{
    public function bootstrap($app)
    {

        Yii::$app->response->on(Response::EVENT_AFTER_PREPARE, ['app\modules\testnay\Testnay', 'shortTagHandler']);
        Yii::$app->response->on(Nav::EVENT_BEFORE_RUN, ['app\modules\testnay\Testnay', 'insertItenInNavMenu']);



       Yii::$container->set(MainBuilder::className(), self::getConfig('mainBuilder'));
       Yii::$container->set(ConfigGenerator::className(), self::getConfig('configGen'));

        /*Yii::$container->set('yii\bootstrap\Nav', [
            'items' => [
                ['label' => 'Custom label', 'url' => ['/gii']]
            ]
        ]);
        /*
        $app->on(Application::EVENT_BEFORE_REQUEST, function () {
            // do parse, when output layuot. Serching short_tag {yii2form_id=<d+>}

            Yii::$container->set('yii\bootstrap\Nav', [
                'items' => [
                    ['label' => 'GII', 'url' => ['/gii']],
                    ],
            ]);
        });*/
    }

    /**
     * @param $index
     * @return mixed
     */
    private static function getConfig($index)
    {
        $config =  [
            'configGen' => [
                'class' => ConfigGenerator::className(),
                'generators' => [
                    'fields' => Yii::$container->get(FieldsConfigGenerator::className()) ,
                    'forms' => Yii::$container->get(FormConfigGenerator::className()),
                    'settings' => Yii::$container->get(SettingsConfigGenerator::className()),
                    'options' => Yii::$container->get(OptionsConfigGenerator::className())
                ]
            ],

            'mainBuilder' => [
                'as fieldBuilder' => [ 'class' => FieldBuilder::className() ],
                'as formBuilder' => [ 'class' => FormBuilder::className() ],
               // 'fieldBuilder' => Yii::$container->get(FormBuilder::className()),  as mixin
               // 'formBuilder' => Yii::$container->get(FieldBuilder::className()),   as mixin
            ],
        ];

        return $config[$index];
    }
}