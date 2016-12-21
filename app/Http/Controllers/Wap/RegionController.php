<?php
/**
 * Created by PhpStorm.
 * User: liuzw
 * Date: 2016/12/21
 * Time: 15:03
 */

namespace App\Http\Controllers\Wap;

use App\Libs\Apikit;
use App\Libs\ErrorInfo;
use App\Libs\Toolkit;
use Illuminate\Http\Request;

/**
 * 国家地区控制器类
 *
 * Class RegionController
 * @package App\Http\Controllers\Api
 */
class RegionController extends BController
{
    /**
     * 取得某个区域的信息
     *
     * @param $request
     * 必选参数：int|string $id
     * @return object
     */
    public function postReadregion(Request $request)
    {
        $request_data = $this->requestHandle($request);

        $read_type = ($request_data->has('read_type') && Toolkit::is_integer($request_data->get('read_type'))) ? trim($request_data->get('read_type')) : null;
        if (!isset($read_type) || ($read_type = intval($read_type, 10)) < 1) {
            return $this->responseFail('[read_type]' . ErrorInfo::Errors(2001), 2001);
        }

        $region = null;

        /**
         *  TODO: $read_type(1区域id)
         */
        switch ($read_type) {
            case 1: {
                $region_id = ($request_data->has('id') && Toolkit::is_integer($request_data->get('id'))) ? trim($request_data->get('id')) : null;
                if (!isset($region_id) || intval($region_id, 10) < 1) {
                    return $this->responseFail('[id]' . ErrorInfo::Errors(2001), 2001);
                }

                $api_action = 'region/readregion';
                $api_data = array('read_type' => 1, 'id' => $region_id);
                $api_result = Apikit::api_post($api_action, $api_data);
                unset($api_data);

                if (!isset($api_result) || empty($api_result)) {
                    return $this->responseFail(ErrorInfo::Errors(2000), 2000);
                }
                if (($error_code = intval($api_result->responseBody->responseInfo->reasons->code)) > 0) {
                    return $this->responseFail(ErrorInfo::Errors($error_code), $error_code);
                }

                $region = $api_result->responseBody->data->region;

                break;
            }
        }

        $data['region'] = $region;

        return $this->responseSucc($data);
    }
}