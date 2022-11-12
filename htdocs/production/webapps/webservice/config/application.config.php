<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-skeleton for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-skeleton/blob/master/LICENSE.md New BSD License
 */

/* get file in directory plugins */
$plugins = [];
$plugDir = __DIR__ . '/plugins';
if(file_exists($plugDir)) {
    $files = scandir($plugDir);
    foreach($files as $f) {
        if(!is_dir($plugDir."/".$f) && $f != "README.md") $plugins = array_merge($plugins, include $plugDir."/".$f);
    }
}

return [
    // Retrieve the list of modules for this application.
    'modules' => array_merge(
        include __DIR__ . '/modules.config.php',
        $plugins
    ),
    // This should be an array of paths in which modules reside.
    // If a string key is provided, the listener will consider that a module
    // namespace, the value of that key the specific path to that module's
    // Module class.
    'module_listener_options' => [
        'module_paths' => [
            './module',
            './plugins',
            './vendor'
        ],
        // Using __DIR__ to ensure cross-platform compatibility. Some platforms --
        // e.g., IBM i -- have problems with globs that are not qualified.
        'config_glob_paths' => [
            realpath(__DIR__) . '/autoload/{,*.}{global,local}.php',
            realpath(__DIR__) . '/autoload/plugins/*.php'
        ],
        'config_cache_key' => 'application.config.cache',
        'config_cache_enabled' => true,
        'module_map_cache_key' => 'application.module.cache',
        'module_map_cache_enabled' => true,
        'cache_dir' => 'data/cache/',
    ],
];