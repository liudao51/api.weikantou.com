<?php
/**
 * Created by PhpStorm.
 * User: liuzw
 * Date: 2016/12/21
 * Time: 11:52
 */

/**
 * 站点域名配置
 */
$app_env = env('APP_ENV', 'local');

$site = array();

if ($app_env === 'production') {
    $site['site_token'] = md5('api.weikantou.com');
    $site['site_host'] = 'api.weikantou.com';
    $site['api_host'] = 'sapi.weikantou.com';
} else if ($app_env === 'local' || $app_env === 'test') {
    $site['site_token'] = md5('test.api.weikantou.com');
    $site['site_host'] = 'test.api.weikantou.com';
    $site['api_host'] = 'test.sapi.weikantou.com';
} elseif ($app_env === 'weitest') {
    $site['site_token'] = md5('weitest.api.weikantou.com');
    $site['site_host'] = 'weitest.api.weikantou.com';
    $site['api_host'] = 'weitest.sapi.weikantou.com';
}

return $site;
