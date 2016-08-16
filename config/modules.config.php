<?php

use Zend\Expressive\ConfigManager\ConfigManager;

/**
 * Use Fully Qualified Namespace to enable the expressive configuration
 */
$modules = [
    Zend\Db\ConfigProvider::class,
    CodingMatters\Rest\ConfigProvider::class,
    CodingMatters\Student\ConfigProvider::class,
    CodingMatters\Employee\ConfigProvider::class,    
    //Append module namespace here
];

return (new ConfigManager($modules))->getMergedConfig();
