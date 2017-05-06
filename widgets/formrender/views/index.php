<?php
/**
 * This view of FormRender.
 */

use yii\bootstrap\ActiveField;
use yii\bootstrap\ActiveForm;

/** @var ActiveField field */
/** @var ActiveForm form */
?>



<h2> >>>TEST<<< </h2>

<?php

//echo $form->init();
//echo $form->run();

$form = ActiveForm::begin([]);

foreach ($fields as $field){
   echo $field;
}


ActiveForm::end();
?>


<h2> >>>end_TEST<<< </h2>
