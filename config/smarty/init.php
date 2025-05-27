<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$smarty = new Smarty();

// Template settings
$smarty->setTemplateDir(__DIR__ . '/../../app/Views');
$smarty->setCompileDir(__DIR__ . '/../../storage/cache/smarty_compiled');
$smarty->setCacheDir(__DIR__ . '/../../storage/cache/smarty_cache');
$smarty->setConfigDir(__DIR__ . '/../../config/smarty');

// Security
$smarty->escape_html = true;

// Debugging (disable in production)
$smarty->debugging = false;
$smarty->debug_tpl = __DIR__ . '/smarty_debug.tpl';

return $smarty;