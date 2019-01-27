<?php namespace App\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class DateTimeType extends FormField {

    protected function getTemplate()
    {
        // At first it tries to load config variable,
        // and if fails falls back to loading view
        // resources/views/fields/image.blade.php
        return 'fields.date-time';
    }

    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        return parent::render($options, $showLabel, $showField, $showError);
    }
}