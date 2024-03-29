<?php

/*
 * This file is part of the overtrue/wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace EasyWeChat\Work\OA;

use EasyWeChat\Kernel\BaseClient;
/**
 * Class Client.
 *
 * @author mingyoung <mingyoungcheung@gmail.com>
 */
class Client extends BaseClient
{
    /**
     * Get the checkin data.
     *
     * @param int   $startTime
     * @param int   $endTime
     * @param array $userList
     * @param int   $type
     *
     * @return mixed
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function checkinRecords($startTime, $endTime, array $userList, $type = 3)
    {
        $params = ['opencheckindatatype' => $type, 'starttime' => $startTime, 'endtime' => $endTime, 'useridlist' => $userList];
        return $this->httpPostJson('cgi-bin/checkin/getcheckindata', $params);
    }
    /**
     * Get the checkin rules.
     *
     * @param int   $datetime
     * @param array $userList
     *
     * @return mixed
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function checkinRules($datetime, array $userList)
    {
        $params = ['datetime' => $datetime, 'useridlist' => $userList];
        return $this->httpPostJson('cgi-bin/checkin/getcheckinoption', $params);
    }
    /**
     * Get approval template details.
     *
     * @param $templateId
     *
     * @return mixed
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function approvalTemplate($templateId)
    {
        $params = ['template_id' => $templateId];
        return $this->httpPostJson('cgi-bin/oa/gettemplatedetail', $params);
    }
    /**
     * Submit an application for approval.
     *
     * @param array $data
     *
     * @return mixed
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createApproval(array $data)
    {
        return $this->httpPostJson('cgi-bin/oa/applyevent', $data);
    }
    /**
     * Get Approval number.
     *
     * @param int   $startTime
     * @param int   $endTime
     * @param int   $nextCursor
     * @param int   $size
     * @param array $filters
     *
     * @return mixed
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function approvalNumbers($startTime, $endTime, $nextCursor = 0, $size = 100, array $filters = [])
    {
        $params = ['starttime' => $startTime, 'endtime' => $endTime, 'cursor' => $nextCursor, 'size' => $size > 100 ? 100 : $size, 'filters' => $filters];
        return $this->httpPostJson('cgi-bin/oa/getapprovalinfo', $params);
    }
    /**
     * Get approval detail.
     *
     * @param int $number
     *
     * @return mixed
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function approvalDetail($number)
    {
        $params = ['sp_no' => $number];
        return $this->httpPostJson('cgi-bin/oa/getapprovaldetail', $params);
    }
    /**
     * Get Approval Data.
     *
     * @param int $startTime
     * @param int $endTime
     * @param int $nextNumber
     *
     * @return mixed
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function approvalRecords($startTime, $endTime, $nextNumber = null)
    {
        $params = ['starttime' => $startTime, 'endtime' => $endTime, 'next_spnum' => $nextNumber];
        return $this->httpPostJson('cgi-bin/corp/getapprovaldata', $params);
    }
    /**
     * 获取公费电话拨打记录.
     *
     * @param int $startTime
     * @param int $endTime
     * @param int $offset
     * @param int $limit
     *
     * @return mixed
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function dialRecords($startTime, $endTime, $offset = 0, $limit = 100)
    {
        $params = ['start_time' => $startTime, 'end_time' => $endTime, 'offset' => $offset, 'limit' => $limit];
        return $this->httpPostJson('cgi-bin/dial/get_dial_record', $params);
    }
}