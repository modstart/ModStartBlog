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
        $apiBase = modstart_config('Tecmz_ApiBase', '');
        if ($apiBase) {
            self::$API_BASE = $apiBase;
        }
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
        return CurlUtil::postJSONBody($url, $param, [
            'timeout' => 60 * 10,
        ]);
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

    
    public function asr($type, $contentBase64)
    {
        $post = [];
        $post['type'] = $type;
        $post['content'] = $contentBase64;
        return $this->request('/asr', $post);
    }

    
    public function express($type, $no, $phone = null)
    {
        $post = [];
        $post['type'] = $type;
        $post['no'] = $no;
        $post['phone'] = $phone;
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

    
    public function imageCompress($format, $imageData = null, $imageUrl = null, $name = null, $param = [])
    {
        $ret = $this->request('/image_compress/prepare', []);
                if (Response::isError($ret)) {
            return $ret;
        }
        $post = [];
        $post['format'] = $format;
        if (!empty($imageData)) {
            $post['imageData'] = base64_encode($imageData);
        }
        if (!empty($imageUrl)) {
            $post['imageUrl'] = $imageUrl;
        }
        $post['name'] = $name;
        $post['param'] = json_encode($param, JSON_UNESCAPED_UNICODE);
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

    private function callFileConvertQueue($type, $url, $name = null, $param = [])
    {
        if (is_array($url)) {
            $url = json_encode($url, JSON_UNESCAPED_UNICODE);
        }
        $post = [];
        $post['url'] = $url;
        $post['name'] = $name;
        $post['param'] = json_encode($param, JSON_UNESCAPED_UNICODE);
        return $this->request('/' . $type . '/queue', $post);
    }

    private function callFileConvertQuery($type, $jobId)
    {
        $post = [];
        $post['jobId'] = $jobId;
        return $this->request('/' . $type . '/query', $post);
    }

    
    public function aiToImageQueue($url, $name = null, $param = [])
    {
        return $this->callFileConvertQueue('ai_to_image', $url, $name, $param);
    }

    
    public function aiToImageQuery($jobId)
    {
        return $this->callFileConvertQuery('ai_to_image', $jobId);
    }

    
    public function amrConvertQueue($url, $name = null, $param = [])
    {
        return $this->callFileConvertQueue('amr_convert', $url, $name, $param);
    }

    
    public function amrConvertQuery($jobId)
    {
        return $this->callFileConvertQuery('amr_convert', $jobId);
    }

    
    public function docToPdfQueue($url, $name = null, $param = [])
    {
        return $this->callFileConvertQueue('doc_to_pdf', $url, $name, $param);
    }

    
    public function docToPdfQuery($jobId)
    {
        return $this->callFileConvertQuery('doc_to_pdf', $jobId);
    }

    
    public function epsToImageQueue($url, $name = null, $param = [])
    {
        return $this->callFileConvertQueue('eps_to_image', $url, $name, $param);
    }

    
    public function epsToImageQuery($jobId)
    {
        return $this->callFileConvertQuery('eps_to_image', $jobId);
    }

    
    public function mp3ConvertQueue($url, $name = null, $param = [])
    {
        return $this->callFileConvertQueue('mp3_convert', $url, $name, $param);
    }

    
    public function mp3ConvertQuery($jobId)
    {
        return $this->callFileConvertQuery('mp3_convert', $jobId);
    }

    
    public function wavConvertQueue($url, $name = null, $param = [])
    {
        return $this->callFileConvertQueue('wav_convert', $url, $name, $param);
    }

    
    public function wavConvertQuery($jobId)
    {
        return $this->callFileConvertQuery('wav_convert', $jobId);
    }

    
    public function pdfCollectQueue($url, $name = null, $param = [])
    {
        return $this->callFileConvertQueue('pdf_collect', $url, $name, $param);
    }

    
    public function pdfCollectQuery($jobId)
    {
        return $this->callFileConvertQuery('pdf_collect', $jobId);
    }

    
    public function pdfDecryptQueue($url, $name = null, $param = [])
    {
        return $this->callFileConvertQueue('pdf_decrypt', $url, $name, $param);
    }

    
    public function pdfDecryptQuery($jobId)
    {
        return $this->callFileConvertQuery('pdf_decrypt', $jobId);
    }

    
    public function pdfEncryptQueue($url, $name = null, $param = [])
    {
        return $this->callFileConvertQueue('pdf_encrypt', $url, $name, $param);
    }

    
    public function pdfEncryptQuery($jobId)
    {
        return $this->callFileConvertQuery('pdf_encrypt', $jobId);
    }

    
    public function pdfOptimizeQueue($url, $name = null, $param = [])
    {
        return $this->callFileConvertQueue('pdf_optimize', $url, $name, $param);
    }

    
    public function pdfOptimizeQuery($jobId)
    {
        return $this->callFileConvertQuery('pdf_optimize', $jobId);
    }

    
    public function pdfToImageQueue($url, $name = null, $param = [])
    {
        return $this->callFileConvertQueue('pdf_to_image', $url, $name, $param);
    }

    
    public function pdfToImageQuery($jobId)
    {
        return $this->callFileConvertQuery('pdf_to_image', $jobId);
    }

    
    public function pdfWatermarkQueue($url, $name = null, $param = [])
    {
        return $this->callFileConvertQueue('pdf_watermark', $url, $name, $param);
    }

    
    public function pdfWatermarkQuery($jobId)
    {
        return $this->callFileConvertQuery('pdf_watermark', $jobId);
    }

    
    public function psdToImageQueue($url, $name = null, $param = [])
    {
        return $this->callFileConvertQueue('psd_to_image', $url, $name, $param);
    }

    
    public function psdToImageQuery($jobId)
    {
        return $this->callFileConvertQuery('psd_to_image', $jobId);
    }

    
    public function pdfToWordQueue($url, $name = null, $param = [])
    {
        return $this->callFileConvertQueue('pdf_to_word', $url, $name, $param);
    }

    
    public function pdfToWordQuery($jobId)
    {
        return $this->callFileConvertQuery('pdf_to_word', $jobId);
    }

    
    public function pdfToExcelQueue($url, $name = null, $param = [])
    {
        return $this->callFileConvertQueue('pdf_to_excel', $url, $name, $param);
    }

    
    public function pdfToExcelQuery($jobId)
    {
        return $this->callFileConvertQuery('pdf_to_excel', $jobId);
    }

    
    public function imageToWordQueue($url, $name = null, $param = [])
    {
        return $this->callFileConvertQueue('image_to_word', $url, $name, $param);
    }

    
    public function imageToWordQuery($jobId)
    {
        return $this->callFileConvertQuery('image_to_word', $jobId);
    }

    
    public function imageToExcelQueue($url, $name = null, $param = [])
    {
        return $this->callFileConvertQueue('image_to_excel', $url, $name, $param);
    }

    
    public function imageToExcelQuery($jobId)
    {
        return $this->callFileConvertQuery('image_to_excel', $jobId);
    }

    
    public function imageThumbQueue($url, $name = null, $param = [])
    {
        return $this->callFileConvertQueue('image_thumb', $url, $name, $param);
    }

    
    public function imageThumbQuery($jobId)
    {
        return $this->callFileConvertQuery('image_thumb', $jobId);
    }

    
    public function docToHtmlQueue($url, $name = null, $param = [])
    {
        return $this->callFileConvertQueue('doc_to_html', $url, $name, $param);
    }

    
    public function docToHtmlQuery($jobId)
    {
        return $this->callFileConvertQuery('doc_to_html', $jobId);
    }

    
    public function pdfToTextQueue($url, $name = null, $param = [])
    {
        return $this->callFileConvertQueue('pdf_to_text', $url, $name, $param);
    }

    
    public function pdfToTextQuery($jobId)
    {
        return $this->callFileConvertQuery('pdf_to_text', $jobId);
    }

    
    public function docSmartPreviewQueue($url, $name = null, $param = [])
    {
        return $this->callFileConvertQueue('doc_smart_preview', $url, $name, $param);
    }

    
    public function docSmartPreviewQuery($jobId)
    {
        return $this->callFileConvertQuery('doc_smart_preview', $jobId);
    }


}
