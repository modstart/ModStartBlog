<?php


namespace ModStart\Module;

use Illuminate\Support\Facades\Artisan;
use ModStart\Core\Exception\BizException;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\ArrayUtil;
use ModStart\Core\Util\FileUtil;
use ModStart\Core\Util\VersionUtil;

class ModuleManager
{
    
    const MODULE_ENABLE_LIST = 'ModuleList';
    
    const MODULE_SYSTEM_OVERWRITE_CONFIG = 'ModuleSystemOverwriteConfig';

    
    public static function getModuleBasic($name)
    {
        static $basic = [];
        if (array_key_exists($name, $basic)) {
            return $basic[$name];
        }
        if (file_exists($path = self::path($name, 'config.json'))) {
            $config = json_decode(file_get_contents($path), true);
            $basic[$name] = array_merge([
                'name' => 'None',
                'title' => 'None',
                'version' => '1.0.0',
                'env' => [
                    'laravel5'
                ],
                'types' => [],
                'require' => [
                                                                                                                                        ],
                'suggest' => [
                                                                                                                                        ],
                                'conflicts' => [
                                                                                                                                        ],
                'modstartVersion' => '*',
                'author' => 'Author',
                'description' => 'Description',
                'config' => [],
                'providers' => [],
            ], $config);
        } else {
            $basic[$name] = null;
        }
        return $basic[$name];
    }

    private static function callCommand($command, $param = [])
    {
        try {
            $exitCode = Artisan::call($command, $param);
            $output = trim(Artisan::output());
            if (0 !== $exitCode) {
                return Response::generate(-1, "ERROR:$exitCode", ['output' => $output]);
            }
            return Response::generateSuccessData(['output' => $output]);
        } catch (BizException $e) {
            return Response::generateError($e->getMessage());
        }
    }

    public static function clean($module)
    {
        $path = self::path($module);
        if (file_exists($path)) {
            FileUtil::rm($path, true);
        }
    }

    
    public static function install($module, $force = false)
    {
        $param = ['module' => $module];
        if ($force) {
            $param['--force'] = true;
        }
        return self::callCommand('modstart:module-install', $param);
    }

    
    public static function uninstall($module)
    {
        return self::callCommand('modstart:module-uninstall', ['module' => $module]);
    }

    
    public static function enable($module)
    {
        return self::callCommand('modstart:module-enable', ['module' => $module]);
    }

    
    public static function disable($module)
    {
        return self::callCommand('modstart:module-disable', ['module' => $module]);
    }

    
    public static function isExists($name)
    {
        return file_exists(self::path($name, 'config.json'));
    }

    
    public static function path($module, $path = '')
    {
        return base_path(self::relativePath($module, $path));
    }

    
    public static function relativePath($module, $path = '')
    {
        return "module/$module" . ($path ? "/" . trim($path, '/') : '');
    }

    
    public static function isSystemModule($module)
    {
        $modules = config('module.system', []);
        return isset($modules[$module]);
    }

    
    public static function isModuleInstalled($name)
    {
        if (!self::isExists($name)) {
            return false;
        }
        $modules = self::listAllInstalledModules();
        return isset($modules[$name]);
    }

    
    public static function isModuleEnabled($name)
    {
        $modules = self::listAllInstalledModules();
        return !empty($modules[$name]['enable']);
    }


    
    public static function isModuleEnableMatch($name, $version)
    {
        if (!self::isModuleEnabled($name)) {
            return false;
        }
        $basic = self::getModuleBasic($name);
        if (!$basic) {
            return false;
        }
        return VersionUtil::match($basic['version'], $version);
    }

    
    public static function listModules()
    {
        $files = FileUtil::listFiles(base_path('module'));
        $modules = [];
        foreach ($files as $v) {
            if (!$v['isDir']) {
                continue;
            }
            if (starts_with($v['filename'], '_delete_.') || starts_with($v['filename'], '_')) {
                continue;
            }
            $modules[$v['filename']] = [
                'enable' => false,
                'isSystem' => false,
                'isInstalled' => false,
                'config' => [],
            ];
        }
        foreach (self::listSystemInstalledModules() as $m => $config) {
            if (isset($modules[$m])) {
                $modules[$m]['isInstalled'] = true;
                $modules[$m]['isSystem'] = true;
                $modules[$m]['enable'] = !empty($config['enable']);
            }
        }
        foreach (self::listUserInstalledModules() as $m => $config) {
            if (isset($modules[$m])) {
                $modules[$m]['isInstalled'] = true;
                $modules[$m]['enable'] = !empty($config['enable']);
            }
        }
        return $modules;
    }

    
    public static function listSystemInstalledModules()
    {
        $modules = array_build(config('module.system', []), function ($k, $v) {
            $v['isSystem'] = true;
            if (!isset($v['enable'])) {
                $v['enable'] = false;
            }
            return [$k, $v];
        });
        if (config('env.MS_MODULES')) {
            foreach (explode(',', config('env.MS_MODULES')) as $m) {
                if (!empty($m)) {
                    $modules[$m] = [
                        'isSystem' => true,
                        'enable' => true,
                    ];
                }
            }
        }
        try {
            $systemConfig = modstart_config()->getArray(self::MODULE_SYSTEM_OVERWRITE_CONFIG, []);
            if (!empty($systemConfig)) {
                foreach ($systemConfig as $m => $config) {
                    if (empty($modules[$m]) || !is_array($config)) {
                        continue;
                    }
                    if (!isset($modules[$m]['config'])) {
                        $modules[$m]['config'] = [];
                    }
                    $modules[$m]['config'] = array_merge($modules[$m]['config'], $config);
                }
            }
        } catch (\Exception $e) {
        }
                return $modules;
    }

    
    public static function listUserInstalledModules()
    {
        try {
            return array_build(modstart_config()->getArray(self::MODULE_ENABLE_LIST), function ($k, $v) {
                $v['isSystem'] = false;
                if (!isset($v['enable'])) {
                    $v['enable'] = false;
                }
                return [$k, $v];
            });
        } catch (\Exception $e) {
            return [];
        }
    }

    
    public static function listAllEnableModuleNames()
    {
        return array_keys(self::listAllEnabledModules());
    }

    
    public static function listAllEnabledModules()
    {
        return array_filter(self::listAllInstalledModules(), function ($item) {
            return $item['enable'];
        });
    }

    
    public static function listAllInstalledModules($forceReload = false)
    {
        static $modules = null;
        if ($forceReload) {
            $modules = null;
        }
        if (null !== $modules) {
            return $modules;
        }
        $modules = array_merge(self::listUserInstalledModules(), self::listSystemInstalledModules());
        return $modules;
    }

    
    public static function saveUserInstalledModules($modules)
    {
        $modules = array_map(function ($item) {
            return ArrayUtil::keepKeys($item, [
                'config', 'enable',
            ]);
        }, array_filter($modules, function ($m) {
            return empty($m['isSystem']);
        }));
        modstart_config()->setArray(self::MODULE_ENABLE_LIST, $modules);
    }

    
    public static function listAllInstalledModulesInRequiredOrder($ignoreError = false)
    {
        $modules = self::listAllInstalledModules();
        $modules = array_keys($modules);
        $moduleInfoMap = [];
        foreach ($modules as $module) {
            $basic = self::getModuleBasic($module);
            if (empty($basic)) {
                continue;
            }
            $moduleInfoMap[$module] = $basic['require'];
        }
        $orderedModules = [];
        for ($i = 0; $i < 100; $i++) {
            foreach ($modules as $module) {
                if (in_array($module, $orderedModules)) {
                    continue;
                }
                $allPassed = true;
                if (!empty($moduleInfoMap[$module])) {
                    foreach ($moduleInfoMap[$module] as $requireModule) {
                        list($m, $v) = VersionUtil::parse($requireModule);
                        if (!in_array($m, $orderedModules)) {
                            $allPassed = false;
                        }
                    }
                }
                if ($allPassed) {
                    $orderedModules[] = $module;
                }
            }
            if (count($orderedModules) == count($modules)) {
                break;
            }
        }
        if (!$ignoreError) {
            if (count($modules) !== count($orderedModules)) {
                list($inserts, $deletes) = ArrayUtil::diff($orderedModules, $modules);
                $errors = [];
                foreach ($inserts as $insert) {
                    $requires = $moduleInfoMap[$insert];
                    foreach ($requires as $one) {
                        if (!in_array($one, $orderedModules)) {
                            $errors[] = L('Module %s Depends On %s', $insert, $one);
                        }
                    }
                }
                if (!empty($errors)) {
                    BizException::throws(L('Module Not Fully Installed') . ' ' . join('; ', $errors));
                } else {
                    BizException::throws(L('Module Not Fully Installed') . ' ' . L('Requires') . '  ' . json_encode($modules));
                }
            }
        }
        return $orderedModules;
    }

    
    public static function getInstalledModuleInfo($module)
    {
        $modules = self::listAllInstalledModules();
        return isset($modules[$module]) ? $modules[$module] : null;
    }

    
    public static function saveUserInstalledModuleConfig($module, $config)
    {
        $modules = self::listUserInstalledModules();
        if (!empty($modules[$module])) {
            if (empty($modules[$module]['config'])) {
                $modules[$module]['config'] = [];
            }
            $modules[$module]['config'] = array_merge($modules[$module]['config'], $config);
        }
        self::saveUserInstalledModules($modules);
    }

    
    public static function saveSystemOverwriteModuleConfig($module, $config)
    {
        $current = modstart_config()->getArray(self::MODULE_SYSTEM_OVERWRITE_CONFIG);
        $current[$module] = $config;
        modstart_config()->setArray(self::MODULE_SYSTEM_OVERWRITE_CONFIG, $current);
    }

    
    public static function getModuleConfig($module, $key, $default = null)
    {
        $moduleInfo = self::getInstalledModuleInfo($module);
        if (isset($moduleInfo['config'][$key])) {
            return $moduleInfo['config'][$key];
        }
        return $default;
    }

    public static function getModuleConfigArray($module, $key, $default = [])
    {
        $value = self::getModuleConfig($module, $key);
        if (is_array($value)) {
            return $value;
        }
        $value = @json_decode($value, true);
        if (empty($value)) {
            $value = $default;
        }
        return $value;
    }

    public static function getModuleConfigBoolean($module, $key, $default = false)
    {
        return !!self::getModuleConfig($module, $key, $default);
    }

    public static function getModuleConfigKeyValueItems($module, $key, $default = [])
    {
        $value = self::getModuleConfigArray($module, $key, $default);
        $result = [];
        if (!empty($value) && is_array($value)) {
            foreach ($value as $item) {
                if (isset($item['k']) && isset($item['v'])) {
                    $result[$item['k']] = $item['v'];
                }
            }
        }
        return $result;
    }

    public static function getModuleConfigKeyValueItem($module, $key, $itemKey, $default = null)
    {
        $items = self::getModuleConfigKeyValueItems($module, $key);
        if (isset($items[$itemKey])) {
            return $items[$itemKey];
        }
        return $default;
    }

    
    public static function hotReloadSystemConfig()
    {
        $configSystem = config('module.system', []);
        $file = base_path('config/module.php');
        if (file_exists($file)) {
            if (function_exists('opcache_invalidate')) {
                opcache_invalidate($file);
            }
            $configModuleContent = (include $file);
            $configSystem = array_merge($configSystem, $configModuleContent['system']);
            config([
                'module.system' => $configSystem,
            ]);
        }
        self::listAllInstalledModules(true);
    }

    
    public static function callHook($module, $method, $args = [])
    {
        $cls = '\\Module\\' . $module . '\\Core\\ModuleHook';
        if (class_exists($cls)) {
            $hook = app($cls);
            if (method_exists($hook, $method)) {
                call_user_func_array([$hook, $method], $args);
            }
        }
    }

    
    public static function getEnv()
    {
        if (PHP_VERSION_ID >= 80000) {
            return 'laravel9';
        }
        return 'laravel5';
    }

}
