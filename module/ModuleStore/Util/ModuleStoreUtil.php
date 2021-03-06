<?php


namespace Module\ModuleStore\Util;


use Chumper\Zipper\Zipper;
use Illuminate\Support\Facades\Cache;
use ModStart\Core\Exception\BizException;
use ModStart\Core\Input\InputPackage;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\CurlUtil;
use ModStart\Core\Util\FileUtil;
use ModStart\Core\Util\VersionUtil;
use ModStart\ModStart;
use ModStart\Module\ModuleManager;

class ModuleStoreUtil
{
    const REMOTE_BASE = 'https://modstart.com';

    public static function remoteModuleData()
    {
        $input = InputPackage::buildFromInput();
        $memberUserId = $input->getInteger('memberUserId');
        $apiToken = $input->getTrimString('apiToken');
        return Cache::remember('ModuleStore_Modules:' . $memberUserId, 60, function () use ($apiToken) {
            $app = 'cms';
            if (class_exists('\App\Constant\AppConstant')) {
                $app = \App\Constant\AppConstant::APP;
            }
            $ret = CurlUtil::getJSONData(self::REMOTE_BASE . '/api/store/module', [
                'app' => $app,
                'api_token' => $apiToken,
            ]);
            return $ret;
        });
    }

    public static function all()
    {
        $storeConfig = [
            'disable' => config('env.MS_MODULE_STORE_DISABLE', false),
        ];
        $result = self::remoteModuleData();
        $categories = [];
        if (!empty($result['data']['categories'])) {
            $categories = $result['data']['categories'];
        }
        $types = [];
        if (!empty($result['data']['types'])) {
            $types = $result['data']['types'];
        }
        $modules = [];
        if (!empty($result['data']['modules'])) {
            foreach ($result['data']['modules'] as $remote) {
                $remote['_isLocal'] = false;
                $remote['_isInstalled'] = false;
                $remote['_isEnabled'] = false;
                $remote['_localVersion'] = null;
                $remote['_isSystem'] = false;
                $remote['_hasConfig'] = false;
                $modules[$remote['name']] = $remote;
            }
        }
        foreach (ModuleManager::listModules() as $m => $config) {
            $info = ModuleManager::getModuleBasic($m);
            if (isset($modules[$m])) {
                $modules[$m]['_isInstalled'] = $config['isInstalled'];
                $modules[$m]['_isEnabled'] = $config['enable'];
                $modules[$m]['_localVersion'] = $info['version'];
                $modules[$m]['_isSystem'] = $config['isSystem'];
                $modules[$m]['_hasConfig'] = !empty($info['config']);
            } else {
                $modules[$m] = [
                    'id' => 0,
                    'name' => $m,
                    'title' => $info['title'],
                    'cover' => null,
                    'categoryId' => null,
                    'latestVersion' => $info['version'],
                    'releases' => [],
                    'url' => null,
                    'isFee' => false,
                    'priceSuper' => null,
                    'priceSuperEnable' => false,
                    'priceYear' => null,
                    'priceYearEnable' => false,
                    'description' => $info['description'],
                    '_isLocal' => true,
                    '_isInstalled' => $config['isInstalled'],
                    '_isEnabled' => $config['enable'],
                    '_localVersion' => $info['version'],
                    '_isSystem' => $config['isSystem'],
                    '_hasConfig' => !empty($info['config']),
                ];
            }
        }
        return [
            'storeConfig' => $storeConfig,
            'categories' => $categories,
            'types' => $types,
            'modules' => array_values($modules),
        ];
    }

    private static function baseRequest($api, $data, $token)
    {
        return CurlUtil::postJSONBody(self::REMOTE_BASE . $api, $data, [
            'header' => [
                'api-token' => $token,
                'X-Requested-With' => 'XMLHttpRequest',
            ]
        ]);
    }

    public static function checkPackage($token, $module, $version)
    {
        $ret = self::baseRequest('/api/store/module_info', [
            'module' => $module,
            'version' => $version,
        ], $token);
        BizException::throwsIfResponseError($ret);
        $config = $ret['data']['config'];
        $packageSize = $ret['data']['packageSize'];
        $requires = [];
        if (!empty($config['modstartVersion'])) {
            $require = [
                'name' => "<a href='https://modstart.com/download' class='ub-text-white tw-underline' target='_blank'>MSCore</a>:" . htmlspecialchars($config['modstartVersion']),
                'success' => VersionUtil::match(ModStart::$version, $config['modstartVersion']),
                'resolve' => null,
            ];
            if (!$require['success']) {
                $require['resolve'] = '????????? MSCore' . $config['modstartVersion'] . ' ?????????';
            }
            $requires[] = $require;
        }
        if (!empty($config['require'])) {
            foreach ($config['require'] as $require) {
                list($m, $v) = VersionUtil::parse($require);
                $require = [
                    'name' => "<a href='https://modstart.com/m/$m' class='ub-text-white tw-underline' target='_blank'>$m</a>:" . htmlspecialchars($v),
                    'success' => true,
                    'resolve' => null,
                ];
                if (ModuleManager::isModuleInstalled($m)) {
                    $basic = ModuleManager::getModuleBasic($m);
                    BizException::throwsIfEmpty("???????????? $m ????????????", $basic);
                    $require['success'] = VersionUtil::match($basic['version'], $v);
                    if (!$require['success']) {
                        $require['resolve'] = "??????????????? " . htmlspecialchars($v) . " ????????? <a href='https://modstart.com/m/$m' class='ub-text-white tw-underline' target='_blank'>$m</a>";
                    }
                } else {
                    $require['success'] = false;
                    $require['resolve'] = "???????????? $require[name] <a href='https://modstart.com/m/$m' class='ub-text-white tw-underline' target='_blank'>[????????????]</a>";
                }
                $requires[] = $require;
            }
        }
        if (empty($config['env'])) {
            $config['env'] = ['laravel5'];
        }
        if (method_exists(ModuleManager::class, 'getEnv')) {
            $env = ModuleManager::getEnv();
            BizException::throwsIf(
                L('Module %s:%s compatible with env %s, current is %s', $module, $config['version'], join(',', $config['env']), $env),
                !in_array($env, $config['env'])
            );
        }

        return Response::generateSuccessData([
            'requires' => $requires,
            'errorCount' => count(array_filter($requires, function ($o) {
                return !$o['success'];
            })),
            'packageSize' => $packageSize,
        ]);
    }

    public static function downloadPackage($token, $module, $version)
    {
        $ret = self::baseRequest('/api/store/module_package', [
            'module' => $module,
            'version' => $version,
        ], $token);
        BizException::throwsIfResponseError($ret);
        $package = $ret['data']['package'];
        $packageMd5 = $ret['data']['packageMd5'];
        $licenseKey = $ret['data']['licenseKey'];
        $data = CurlUtil::getRaw($package);
        BizException::throwsIfEmpty('?????????????????????', $data);
        $zipTemp = FileUtil::generateLocalTempPath('zip');
        file_put_contents($zipTemp, $data);
        BizException::throwsIf('??????MD5????????????', md5_file($zipTemp) != $packageMd5);
        return Response::generateSuccessData([
            'package' => $zipTemp,
            'licenseKey' => $licenseKey,
            'packageSize' => filesize($zipTemp),
        ]);
    }

    public static function cleanDownloadedPackage($package)
    {
        FileUtil::safeCleanLocalTemp($package);
    }

    public static function unpackModule($module, $package, $licenseKey)
    {
        $results = [];
        BizException::throwsIf('??????????????? ' . $package, empty($package) || !file_exists($package));
        $ret = FileUtil::filePathWritableCheck(['module/._write_check_']);
        BizException::throwsIfResponseError($ret);
        $moduleDir = base_path('module/' . $module);
        if (file_exists($moduleDir)) {
            $moduleBackup = '_delete_.' . date('Ymd_His') . '.' . $module;
            BizException::throwsIf('???????????? module/' . $module . ' ???????????????????????????', !is_dir($moduleDir));
            $moduleBackupDir = base_path("module/$moduleBackup");
            try {
                rename($moduleDir, $moduleBackupDir);
            } catch (\Exception $e) {
                BizException::throws("???????????? $module ??? $moduleBackup ?????????????????????????????????????????????????????????");
            }
            BizException::throwsIf('???????????????????????????', !file_exists($moduleBackupDir));
            $results[] = "???????????? $module ??? $moduleBackup";
        }
        BizException::throwsIf('???????????? module/' . $module . ' ???????????????????????????', file_exists($moduleDir));
        $zipper = new Zipper();
        $zipper->make($package);
        if ($zipper->contains($module . '/config.json')) {
            $zipper->folder($module . '');
        }
        $zipper->extractTo($moduleDir);
        $zipper->close();
        BizException::throwsIf('????????????', !file_exists($moduleDir . '/config.json'));
        file_put_contents($moduleDir . '/license.json', json_encode([
            'licenseKey' => $licenseKey,
        ]));
        self::cleanDownloadedPackage($package);
        return Response::generateSuccessData($results);
    }

    public static function removeModule($module, $version)
    {
        $moduleDir = base_path('module/' . $module);
        BizException::throwsIf('????????????????????? ', !file_exists($moduleDir));
        BizException::throwsIf('???????????? module/' . $module . ' ???????????????????????????', !is_dir($moduleDir));
        $moduleBackup = '_delete_.' . date('Ymd_His') . '.' . $module;
        $moduleBackupDir = base_path("module/$moduleBackup");
        try {
            rename($moduleDir, $moduleBackupDir);
        } catch (\Exception $e) {
            BizException::throws("???????????? $module ??? $moduleBackup ???????????????????????? $module ??????????????????????????????");
        }
        BizException::throwsIf('????????????????????????', !file_exists($moduleBackupDir));
        return Response::generateSuccessData([]);
    }

}
