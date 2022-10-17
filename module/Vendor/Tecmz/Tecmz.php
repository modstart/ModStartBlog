<?php

namespace Module\Vendor\Tecmz;

use Illuminate\Support\Facades\Log;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\CurlUtil;
use ModStart\Core\Util\SignUtil;

class Tecmz
{
    public static $API_BASE = 'https://api.tecmz.com/open_api';

    private $appId;
    private $appSecret;

    private $debug = false;

    public function __construct($appId, $appSecret = null)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    
    public function signCheck($param)
    {
        if (empty($param['sign']) || empty($param['timestamp']) || empty($param['app_id'])) {
            return false;
        }
        if ($param['app_id'] != $this->appId) {
            return false;
        }
        if (($param['timestamp'] < time() - 1800 || $param['timestamp'] > time() + 1800)) {
            return false;
        }
        $sign = $param['sign'];
        unset($param['sign']);
        $signCalc = SignUtil::common($param, $this->appSecret);
        if ($sign != $signCalc) {
            return false;
        }
        return true;
    }

    
    public static function instance($appId, $appSecret = null)
    {
        static $map = [];
        if (!isset($map[$appId])) {
            $map[$appId] = new self($appId, $appSecret);
        }
        return $map[$appId];
    }

    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    private function request($gate, $param = [])
    {
        $param['app_id'] = $this->appId;
        if ($this->appSecret) {
            $param['timestamp'] = time();
            $param['sign'] = SignUtil::common($param, $this->appSecret);
        }
        $url = self::$API_BASE . $gate;
                if ($this->debug) {
            Log::debug('TecmzApi -> ' . $url . ' -> ' . json_encode($param, JSON_UNESCAPED_UNICODE));
        }
        return CurlUtil::postJSONBody($url, $param);
    }

    
    public function ping()
    {
        $ret = $this->request('/ping');
        if ($ret['code']) {
            return Response::generate(-1, 'PING失败');
        }
        return Response::generate(0, 'ok');
    }

    
    public function payOfflineCreate($bizSn, $money, $notifyUrl, $returnUrl)
    {
        return $this->request('/pay_offline/create', [
            'biz_sn' => $bizSn,
            'money' => $money,
            'notify_url' => $notifyUrl,
            'return_url' => $returnUrl,
        ]);
    }

    
    public function captchaVerify($action, $key, $data, $runtime, $types)
    {
        return $this->request('/captcha/verify', [
            'action' => $action,
            'key' => $key,
            'data' => $data,
            'runtime' => $runtime,
            'types' => $types,
        ]);
    }

    
    public function captchaValidate($key)
    {
        return $this->request('/captcha/validate', [
            'key' => $key,
        ]);
    }

    
    public function smsSend($phone, $templateId, $params = [])
    {
        $post = [];
        foreach ($params as $k => $v) {
            $post["param_$k"] = $v;
        }
        return $this->request('/sms/send', array_merge([
            'phone' => $phone,
            'template_id' => $templateId,
        ], $post));
    }

    
    public function audioConvert($from, $to, $contentBase64)
    {
        $post = [];
        $post['from'] = $from;
        $post['to'] = $to;
        $post['content'] = $contentBase64;
        return $this->request('/audio_convert', $post);
    }

    
    public function asr($type, $contentBase64)
    {
        $post = [];
        $post['type'] = $type;
        $post['content'] = $contentBase64;
        return $this->request('/asr', $post);
    }

    
    public function express($type, $no)
    {
        $post = [];
        $post['type'] = $type;
        $post['no'] = $no;
        return $this->request('/express', $post);
    }

    
    public function censorImage($imageBase64, $imageUrl)
    {
        $post = [];
        $post['imageBase64'] = $imageBase64;
        $post['imageUrl'] = $imageUrl;
        return $this->request('/censor_image', $post);
    }

    
    public function censorText($text)
    {
        $post = [];
        $post['text'] = $text;
        return $this->request('/censor_text', $post);
    }

    
    public function ipToLocation($ip)
    {
        $post = [];
        $post['ip'] = $ip;
        return $this->request('/ip_to_location', $post);
    }

    
    public function docToImage($docPath, $pageLimit = 0)
    {
        $post = [];
        $post['docPath'] = $docPath;
        $post['pageLimit'] = $pageLimit;
        return $this->request('/doc_to_image', $post);
    }

    
    public function docToImageQueue($docPath, $pageLimit = 0, $imageQuality = '')
    {
        $post = [];
        $post['docPath'] = $docPath;
        $post['pageLimit'] = $pageLimit;
        $post['imageQuality'] = $imageQuality;
        return $this->request('/doc_to_image/queue', $post);
    }

    
    public function docToImageQuery($jobId)
    {
        $post = [];
        $post['jobId'] = $jobId;
        return $this->request('/doc_to_image/query', $post);
    }

    
    public function imageCompress($format, $imageData)
    {
        $ret = $this->request('/image_compress/prepare', []);
        if (Response::isError($ret)) {
            return $ret;
        }
        $post = [];
        $post['format'] = $format;
        $post['imageData'] = base64_encode($imageData);
        $server = $ret['data']['server'];
                $ret = CurlUtil::postJSONBody($server, $post);
                if (Response::isError($ret)) {
            return $ret;
        }
        return Response::generate(0, 'ok', [
            'imageOriginalSize' => $ret['data']['originalSize'],
            'imageCompressSize' => $ret['data']['compressSize'],
            'imageUrl' => $ret['data']['url'],
        ]);
    }

    
    public function randomAvatar()
    {
        $ret = $this->request('/random_avatar/prepare', []);
        if (Response::isError($ret)) {
            return $ret;
        }
        if ('png' == $ret['data']['format']) {
            $imageData = @base64_decode($ret['data']['imageData']);
        } else {
            $post = [];
            $post['format'] = $ret['data']['format'];
            $post['imageData'] = $ret['data']['imageData'];
            $post['toFormat'] = 'png';
            $server = $ret['data']['server'];
                        $ret = CurlUtil::postJSONBody($server, $post);
                        if (Response::isError($ret)) {
                return $ret;
            }
            $imageData = CurlUtil::getRaw($ret['data']['url']);
        }
        if (empty($imageData)) {
            return Response::generateError('图片数据为空');
        }
        return Response::generate(0, 'ok', [
            'size' => strlen($imageData),
            'imageData' => $imageData,
        ]);
    }

    
    public function ocr($format, $imageData)
    {
        $post = [];
        $post['format'] = $format;
        $post['imageData'] = base64_encode($imageData);
        return $this->request('/ocr', $post);
    }

    
    public function personVerifyIdCard($name, $idCardNumber)
    {
        $post = [];
        $post['name'] = $name;
        $post['idCardNumber'] = $idCardNumber;
        return $this->request('/person_verify_id_card', $post);
    }

}
