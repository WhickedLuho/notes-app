<?php
// config/helpers.php
use App\Helpers\DebugHelper;

if (!function_exists('debug')) {
    function debug($data, ?string $label = null,bool $fileLog = false, bool $return = false): ?string
    {
        return DebugHelper::debug($data, $label, $return);
    }
}