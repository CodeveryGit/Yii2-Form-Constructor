<?php namespace app\modules\testnay;

use app\modules\testnay\builder\ConfigGenerator;
use app\modules\testnay\models\Forms;
use app\modules\testnay\builder\MainBuilder;
use yii\base\Event;
use \yii\base\Module;
use Yii;
use yii\bootstrap\Nav;
use yii\di\ServiceLocator;
use app\modules\testnay\builder\generators\settings_generators\FieldsConfigGenerator;
use app\modules\testnay\builder\generators\settings_generators\FormConfigGenerator;
use app\modules\testnay\builder\generators\settings_generators\OptionsConfigGenerator;
use app\modules\testnay\builder\generators\settings_generators\SettingsConfigGenerator;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class Testnay extends Module{

    /**
     * @var \app\modules\testnay\models\Forms
     */
    public $form;

    /**
     * @var Testnay instance
     */
    public static $fm;

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\testnay\controllers';

    /**
     * @var string Alias for module
     */
    public $alias = "@testnay";

    /**
     * @var string Module version
     */
    protected $version = "1.0";

    /**
     * Testnay constructor. /// Override $id as default = 'testnay'
     * @param string $id
     * @param null $parent
     * @param array $config
     */

    public function init()
    {
        parent::init();

        ///$this->setComponents( [ 'mainBuilder' => ['class'=>MainBuilder::className()]] );

        self::$fm = $this;

        // Add module URL rules.
        Yii::$app->getUrlManager()->addRules(
            [
                [
                    'pattern' => '<module>/<controller>/<action><id:\d+>', ///
                    'route' => '<module>/<controller>/<action>',
                    'suffix' => ''
                ]
            ],
            false
        );

        // set alias
        $this->setAliases([
            $this->alias => __DIR__,
        ]);

        Yii::configure( $this->mainBuilder, ['module' => $this] );

    }

    public function run(){
        //$this->mainBuilder->t();
        $config = $this->configGenerator->getConfig();
        $form = $this->mainBuilder->createForm($config['settings']);
        //$this->mainBuilder->getFields($config['fields']);//die();
        echo $this->mainBuilder->getActiveFields($config['fields'], $form);//die();

        ///var_dump($config['fields']);

    }

    /**
     * @param Event $event
     */
    public static function insertItenInNavMenu($event) // in developing !!!
    {

        $nav = $event->sender;

        var_dump($nav); die();
        /**
         * @var Nav $nav menuWidget
         */
        $nav->items[] = ['label' => 'Custom label', 'url' => ['/gii']];

    }

    public static function shortTagHandler($event)
    {
        $data = Forms::find()->all();
        $x = ArrayHelper::toArray($data, [ 'app\modules\testnay\models\Forms' => ['short_code', 'name' ] ]);
        $array = ArrayHelper::map($x, 'short_code', 'name');

        $content  = $event->sender->content;

        $replaced = preg_replace_callback( '/{form_short_id=(.*)}/U', function ( $matches ) use ($array) {
            $shortName = $matches[1];
            if (array_key_exists($shortName, $array)) {
                $form = Forms::findOne(['short_code'=>$shortName]);
                $module = self::buildModule($form);
                $module->run();
                return '<h1>'. $array[$shortName] .'</h1>';
            }
            return implode(' ', $matches);
        }, $content );

        Yii::$app->response->content =  $replaced;
    }

    private static function buildModule($form)
    {
        //$config = ['class' => self::className(), 'id' => self::className()];
        //Yii::$container->setSingleton(self::className(), $config, ['form'=>$form,'sdad'] );
        //$module = Yii::$container->get(self::className(),[],[]);

        $module = Yii::$app->getModule( 'testnay' );
        //$confGen = Yii::$container->get( ConfigGenerator::className() );
        //$builder = Yii::$container->get( MainBuilder::className(), [], ['configGen' => $confGen] );

        Yii::configure($module, ['form' => $form /*, /*'builder'=>$builder*/]);
       
        return $module;
    }
    
    private static function getConfig()
    {
        return [];
    }

}