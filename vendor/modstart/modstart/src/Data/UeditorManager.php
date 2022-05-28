<?php


namespace ModStart\Data;


use Illuminate\Support\Facades\Input;
use ModStart\Admin\Auth\AdminPermission;
use ModStart\Core\Assets\AssetsUtil;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Input\InputPackage;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\CurlUtil;
use ModStart\Core\Util\FileUtil;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UeditorManager
{
    private static function basicConfig()
    {
        $dataUploadConfig = config('data.upload', []);
        $config = [
                        "imageActionName" => "image",
            "imageFieldName" => "file",
            "imageMaxSize" => $dataUploadConfig['image']['maxSize'],
            "imageAllowFiles" => array_map(function ($v) {
                return '.' . $v;
            }, $dataUploadConfig['image']['extensions']),
            "imageCompressEnable" => true,
            "imageCompressBorder" => 5000,
            "imageInsertAlign" => "none",
            "imageUrlPrefix" => "",

                        "scrawlActionName" => "crawl",
            "scrawlFieldName" => "file",
            "scrawlMaxSize" => $dataUploadConfig['image']['maxSize'],
            "scrawlUrlPrefix" => "",
            "scrawlInsertAlign" => "none",

                        "snapscreenActionName" => "snap",
            "snapscreenUrlPrefix" => "",
            "snapscreenInsertAlign" => "none",

                        "catcherLocalDomain" => ["127.0.0.1", "localhost"],
            "catcherActionName" => "catch",
            "catcherFieldName" => "source",
            "catcherUrlPrefix" => "",
            "catcherMaxSize" => $dataUploadConfig['image']['maxSize'],
            "catcherAllowFiles" => array_map(function ($v) {
                return '.' . $v;
            }, $dataUploadConfig['image']['extensions']),

                        "videoActionName" => "video",
            "videoFieldName" => "file",
            "videoUrlPrefix" => "",
            "videoMaxSize" => $dataUploadConfig['video']['maxSize'],
            "videoAllowFiles" => array_map(function ($v) {
                return '.' . $v;
            }, $dataUploadConfig['video']['extensions']),

                        "fileActionName" => "file",
            "fileFieldName" => "file",
            "fileUrlPrefix" => "",
            "fileMaxSize" => $dataUploadConfig['file']['maxSize'],
            "fileAllowFiles" => array_map(function ($v) {
                return '.' . $v;
            }, $dataUploadConfig['file']['extensions']),

                        "imageManagerActionName" => "listImage",
            "imageManagerListSize" => 20,
            "imageManagerUrlPrefix" => "",
            "imageManagerInsertAlign" => "none",
            "imageManagerAllowFiles" => array_map(function ($v) {
                return '.' . $v;
            }, $dataUploadConfig['image']['extensions']),

                        "fileManagerActionName" => "listFile",
            "fileManagerUrlPrefix" => "",
            "fileManagerListSize" => 20,
            "fileManagerAllowFiles" => array_map(function ($v) {
                return '.' . $v;
            }, $dataUploadConfig['file']['extensions'])

        ];
        return $config;
    }

    private static function saveToUser($uploadTable, $userId, $data)
    {
        ModelUtil::insert($uploadTable, [
            'category' => $data['category'],
            'dataId' => $data['id'],
            'uploadCategoryId' => 0,
            'userId' => $userId,
        ]);
    }

    private static function resultError($result = null, $error = 'ERROR')
    {
        if (null == $result) {
            $result = [
                'state' => '',
            ];
        }
        $result['state'] = $error;
        return Response::jsonRaw($result);
    }

    public static function handle($uploadTable, $uploadCategoryTable, $userId, $option = null)
    {
        $config = self::basicConfig();
        $input = InputPackage::buildFromInput();
        $action = $input->getTrimString('action');
        if (in_array($action, ['image', 'catch'])) {
            set_time_limit(60);
            if ($uploadTable == 'admin_upload' && AdminPermission::isDemo()) {
                return self::resultError();
            }
        }
        switch ($action) {
            case 'config':
                return Response::jsonRaw($config);
            case 'image':
                $editorRet = [
                    'state' => 'SUCCESS',
                    'url' => null
                ];
                
                $file = Input::file('file');
                if (empty($file)) {
                    return self::resultError($editorRet, 'File Empty');
                }
                $filename = $file->getClientOriginalName();
                $content = file_get_contents($file->getRealPath());
                $ret = DataManager::upload('image', $filename, $content, $option);
                if ($ret['code']) {
                    return self::resultError($editorRet, $ret['msg']);
                }
                self::saveToUser($uploadTable, $userId, $ret['data']['data']);
                $editorRet['url'] = $ret['data']['fullPath'];
                return Response::jsonRaw($editorRet);
            case 'catch':
                $editorRet = [
                    'state' => '',
                    'list' => null
                ];
                $saveList = [];
                $list = $input->getArray($config ['catcherFieldName']);
                if (empty ($list)) {
                    return self::resultError($editorRet);
                }
                $editorRet ['state'] = 'SUCCESS';
                $ignores = array_filter([
                    trim(AssetsUtil::cdn(), '/') ? AssetsUtil::cdn() : null,
                ]);
                foreach ($list as $f) {
                    $ignoreCatch = false;
                    foreach ($ignores as $ignore) {
                        if (str_contains($f, $ignore)) {
                            $ignoreCatch = true;
                            break;
                        }
                    }
                    if (!$ignoreCatch && preg_match('/^(http|ftp|https):\\/\\//i', $f)) {
                        $ext = FileUtil::extension($f);
                        if (in_array('.' . $ext, $config ['catcherAllowFiles'])) {
                            $imageContent = CurlUtil::getRaw($f);
                            if ($imageContent) {
                                $ret = DataManager::upload('image', L('Image') . '.' . $ext, $imageContent, $option);
                                if ($ret['code']) {
                                    $ret['state'] = $ret['msg'];
                                } else {
                                    self::saveToUser($uploadTable, $userId, $ret['data']['data']);
                                    $saveList [] = [
                                        'state' => 'SUCCESS',
                                        'url' => $ret['data']['fullPath'],
                                        'size' => strlen($imageContent),
                                        'title' => '',
                                        'original' => '',
                                        'source' => htmlspecialchars($f)
                                    ];
                                }
                            } else {
                                $ret ['state'] = 'Get remote file error';
                            }
                        } else {
                            $ret ['state'] = 'File ext not allowed';
                        }
                    } else {
                        $saveList [] = array(
                            'state' => 'not remote image',
                            'url' => '',
                            'size' => '',
                            'title' => '',
                            'original' => '',
                            'source' => htmlspecialchars($f)
                        );
                    }
                }
                $editorRet ['list'] = $saveList;
                return Response::jsonRaw($editorRet);
        }
    }
}
