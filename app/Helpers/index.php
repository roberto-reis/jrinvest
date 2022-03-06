<?php

if (!function_exists('numberFormatterToSave')) {
    function numberFormatterToSave($value)
    {
        $findCaractere = ['.', ','];
        $replaceCaractere = ['', '.'];
        
        return str_replace($findCaractere, $replaceCaractere, $value);
    }
}