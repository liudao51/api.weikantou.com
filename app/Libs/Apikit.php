<?php
/**
 * Created by PhpStorm.
 * User: liuzw
 * Date: 2016/12/21
 * Time: 10:41
 */

namespace App\Libs;

/**
 * Api工具包
 *
 * Class Apikit
 * @package App\Libs
 */
class Apikit
{
    /**
     * api接口请求(http)
     *
     * @param string $action api请求路由
     * @param array $data 请求数据
     * @param array $header 请求的头部数据
     * @param int $timeout 请求超时时间(默认30秒)
     * @return object|null
     */
    public static function api_post($action, $data = array(), $header = array(), $timeout = 30)
    {
        if (!is_string($action) || !is_array($data) || !is_array($header) || !is_int($timeout)) {
            return null;
        }

        return self::curl_post($action, $data, $header, $timeout, 0);
    }

    /**
     * api接口请求(https)
     *
     * @param string $action api请求路由
     * @param array $data 请求数据
     * @param array $header 请求的头部数据
     * @param int $timeout 请求超时时间(默认30秒)
     * @return object|null
     */
    public static function api_post_ssl($action, $data = array(), $header = array(), $timeout = 30)
    {
        if (!is_string($action) || !is_array($data) || !is_array($header) || !is_int($timeout)) {
            return null;
        }

        return self::curl_post($action, $data, $header, $timeout, 1);
    }

    /**
     * api接口请求
     *
     * @param string $action api请求路由
     * @param array $data 请求数据
     * @param array $header 请求的头部数据
     * @param int $timeout 请求超时时间(默认30秒)
     * @param int $protocol 请求协议(0:http, 1:https)
     * @return object|null
     */
    private static function curl_post($action, $data = array(), $header = array(), $timeout = 30, $protocol = 0)
    {
        if (!is_string($action) || !is_array($data) || !is_array($header) || !is_int($timeout) || !is_int($protocol)) {
            return null;
        }
        if (($action = trim($action)) == '') {
            return null;
        }

        if (substr($action, 1) == '/' || substr($action, 1) == '\\') {
            $action = substr($action, 1, count($action) - 1);
        }
        $url = config('sites.api_host') . '/' . $action;

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if (!empty($data) && count($data) > 0) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }
        if (!empty($header) && count($header) > 0) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        } else {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'X-Forwarded-For:' . Toolkit::get_client_ip()));  //默认以json格式请求
        }
        if ($protocol == 1) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  //是否检测服务器的证书是否由正规浏览器认证过的授权CA颁发的
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  //是否检测服务器的域名与证书上的是否一致
            curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'PEM');  //证书类型: "PEM" (default)、"DER"、"ENG"
            curl_setopt($curl, CURLOPT_SSLCERT, '/data/cert/php.pem');  //证书存放路径
            curl_setopt($curl, CURLOPT_SSLCERTPASSWD, '123456');  //证书密码
            curl_setopt($curl, CURLOPT_SSLKEYTYPE, 'PEM');  //私钥类型："PEM" (default)、"DER"、"ENG"
            curl_setopt($curl, CURLOPT_SSLKEY, '/data/cert/php_private.pem');  //私钥存放路径
        }

        $http_response = curl_exec($curl);
        //$curl_info = curl_getinfo($curl);

        curl_close($curl);

        $post_data = json_decode($http_response, false);

        if (!isset($post_data) || empty($post_data)) {
            return null;
        }

        return $post_data;
    }
}