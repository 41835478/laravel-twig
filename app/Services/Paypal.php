<?php
/**
 * Created by PhpStorm.
 * User: river
 * Date: 2017/12/26
 * Time: 17:11
 */

namespace App\Services;


class Paypal
{
    protected $username = '';

    protected $password = '';

    protected $signature = '';

    protected $version = '95.0';

    protected $endPoint = 'https://api-3t.paypal.com/nvp';

    protected $subject = '';

    public function __construct($username, $password, $signature, $subject = '', $sandbox = false)
    {
        $this->username = $username;
        $this->password = $password;
        $this->signature = $signature;
        if (!empty($subject)) {
            $this->subject = trim($subject);
        }
        if ($sandbox) {
            $this->endPoint = 'https://api-3t.sandbox.paypal.com/nvp';
        }
    }

    // 作为第三方访问Paypal
    public function setSubject($email)
    {
        $this->subject = trim($email);
    }

    // 取回交易列表
    //$params = [
    //    'STARTDATE' => $startTime,
    //    'ENDDATE' => $endTime,
    //    'RECEIVER' => '',
    //    'TRANSACTIONCLASS' => 'All'
    //];

    public function getTransactions(array $params)
    {
        $return = ['success' => 0, 'message' => '', 'data' => []];
        if (empty($params['STARTDATE']) || empty($params['ENDDATE'])) {
            $return['message'] = '参数不合法';
            return $return;
        }

        $result = $this->post('TransactionSearch', $params);
        if (false === $result) {
            $return['message'] = 'CURL请求异常';
            return $return;
        }

        $tarr = explode('&', $result);
        $data = [];
        foreach ($tarr as $item) {
            $tmp = explode('=', rawurldecode($item));

            preg_match('/^L_([a-zA-Z\_]+)([0-9]+)/', $tmp[0], $m);
            if (isset($m[0]) && isset($m[1]) && isset($m[2])) {
                $data[$m[1]][$m[2]] = trim($tmp[1]);
            } else {
                $data[$tmp[0]] = trim($tmp[1]);
            }
        }

        // ACK 等于 Warming时，数据返回不齐全（Paypal每次查询最多返回100条）
        if (empty($data['ACK']) || ($data['ACK'] == 'Failure')) {
            $return['message'] = "API调用ACK返回Failure";
        } else {
            $return['success'] = 1;
            $return['data'] = $data;
        }

        return $return;
    }

    // 通用封装
    protected function post($api, array $params)
    {
        $global = [
            'METHOD' => $api,
            'VERSION' => $this->version,
            'USER' => $this->username,
            'PWD' => $this->password,
            'SIGNATURE' => $this->signature
        ];
        if (!empty($this->subject)) {
            $global['SUBJECT'] = $this->subject;
        }

        return $this->doRequest($this->endPoint, array_merge($global, $params));
    }

    // CURL请求
    protected function doRequest($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, '');
        curl_setopt($ch, CURLOPT_URL, trim($url));
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 90);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        if (!empty($data)) {
            if (is_array($data)) {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            } elseif (is_string($data)) {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            }
        }
        if (\PHP_OS === 'WINNT') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:21.0) Gecko/20100101 Firefox/21.0");
        $result = curl_exec($ch);
        $error = curl_errno($ch);
        curl_close($ch);
        if ((int)$error == 0) {
            return $result;
        }
        return false;
    }
}