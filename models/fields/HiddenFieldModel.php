<?php namespace app\modules\testnay\models\fields;

use yii\bootstrap\ActiveForm;

class HiddenFieldModel extends BaseFieldModel{ // in dev

    public function rules()
    {
        return [
            [[$this->firstAttribut()], 'string', 'max' => 8],
        ];
    }

    /**
     * Calling as InstanceModel->getActiveField() - getActiveField is parent method
     * @param ActiveForm $form
     * @return $this
     */
    public function createActiveField(ActiveForm $form)
    {
        $field = $this->field;

        $conf = [
            'class'=>$field['class'],
            'id'=>$field['ID'],
            'placeholder'=>$field['placeholder'],
            'alt'=>$field['desc'],
        ];

        /** @var ActiveForm $activField */
        $activeField = $form->field($this, $this->firstAttribut())->textInput($conf);

        $this->activeField = $activeField;
        return $activeField;
    }

}
