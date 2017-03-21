<?php
namespace Dingding;


/**
 * User: xiaole
 * Date: 17/3/1
 * Time: 下午3:20
 * Blog: http://www.xiaole88.com
 */

class Dingding {

    public $url = 'https://oapi.dingtalk.com/robot/send';

//    public $access_token = '';

    function __construct($access_token) {
        $this->url = $this->url . '?'.http_build_query(array('access_token'=>$access_token),'','&');
    }

    /**
     * 功能：发送文字消息
     * @author xiaole
     * @time 17/3/1 下午3:30
     */
    public function send_text($content, $atMobiles = array(), $isAtAll = 0) {

        $array = array(
            'msgtype' => 'text',
            'text' => array(
                'content' => $content
            ),
            'at' => array(
                'atMobiles' => $atMobiles
            ),
            'isAtAll' => $isAtAll
        );

        //发送
        return $this->post(json_encode($array));

    }


    /**
     * 功能：钉钉post方法
     * @author xiaole
     * @time 17/3/1 下午3:22
     */
    private function post($data) {
        $defaults = array(
            CURLOPT_POST 			=> 1,
            CURLOPT_HEADER 			=> 0,
            CURLOPT_URL 			=> $this->url,
            CURLOPT_RETURNTRANSFER 	=> 1,
            CURLOPT_TIMEOUT 		=> 5,
            CURLOPT_CONNECTTIMEOUT	=> 5,
            CURLOPT_SSL_VERIFYHOST  =>false,
            CURLOPT_SSL_VERIFYPEER  =>false,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            CURLOPT_POSTFIELDS 		=> is_array($data)?http_build_query($data, '', '&'):$data,
        );

        $ch = curl_init();
        curl_setopt_array($ch, $defaults);
        $result = curl_exec($ch);
        if (curl_error($ch)) {
            trigger_error(curl_error($ch));
        }
        curl_close($ch);

        $result_array = json_decode($result, true);
        if ($result_array['errmsg'] == 'ok') {
            return true;
        } else {
            return $result_array['errcode'];
        }
    }

}