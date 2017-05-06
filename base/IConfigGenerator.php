<?php namespace app\modules\testnay\base;

interface IConfigGenerator
{
    /**
     * Returns array with configuration for a form builder.
     * @return Array
     */
     public function getFormConfig();

    /**
     * Returns array with configuration for a formSettings builder.
     * @return Array
     */
     public function getSettingsConfig();

    /**
     * Returns array with configuration for a fields builder.
     * @return Array
     */
     public function getFieldsConfig();

    /**
     * Returns array with configuration for a options builder.
     * @param int $id - field_id.
     * @return Array
     */
     public function getOptionsConfig($id);
}