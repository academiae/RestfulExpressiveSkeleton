<?php

use Zend\Expressive\ConfigManager\ConfigManager;

/**
 * Use Fully Qualified Namespace to enable the expressive configuration
 */
$modules = [
    CodingMatters\Rest\ConfigProvider::class,
    CodingMatters\Student\ConfigProvider::class,
    //Append module namespace here
];

return (new ConfigManager($modules))->getMergedConfig();
