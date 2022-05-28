<?php


namespace ModStart\App\OpenApi\Util;


use ModStart\Core\Input\Response;
use ModStart\Core\Util\CurlUtil;
use ModStart\Core\Util\SignUtil;

class ModStartOpenApi
{
    
    private $apiBase;
    
    private $key;
    
    private $secret;

    public static function create($apiBase, $key = null, $secret = null)
    {
        $api = new ModStartOpenApi();
        return $api
            ->setApiBase($apiBase)
            ->setKey($key)
            ->setSecret($secret);
    }

    public function setApiBase($apiBase)
    {
        $this->apiBase = $apiBase;
        return $this;
    }

    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    public function setSecret($secret)
    {
        $this->secret = $secret;
        return $this;
    }

    public function post($url, $data)
    {
        $param = [];
        $param['key'] = $this->key;
        $param['timestamp'] = time();
        $param['sign'] = SignUtil::common($param, $this->secret);
        $ret = CurlUtil::postJSON($this->apiBase . $url, json_encode(array_merge($param, $data)), [
            'header' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
        if (Response::isError($ret)) {
            return Response::generateError($ret['msg']);
        }
        return $ret['data'];
    }

}