<?php

/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 *
 * @link http://www.workerman.net/
 *
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * 聊天主逻辑
 * 主要是处理 onMessage onClose.
 */
namespace  App\Chat;

use \GatewayWorker\Lib\Gateway;
use \GatewayWorker\Lib\Store;
use App\Defara\Lib\Models\ClientUserFavorite;
use App\Defara\Lib\Models\ClientUser;
use App\Defara\Lib\Models\Message;
use App\Models\User;

class Events
{
    public static function debug($client_id, $message, $type='in')
    {
        echo "client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id  session:".json_encode($_SESSION)."  type:$type   onMessage:$message" ."\n";
    }

    /**
    * 有消息时.
    *
    * @param int $client_id
    * @param string $message
    */
   public static function onMessage($client_id, $message)
   {
       // debug

        self::debug($client_id, $message);
       $user = User::find(1);
       \Log::info($user);
       \Log::info($client_id);

        // 客户端传递的是json数据
        $message_data = json_decode($message, true);

       if (!$message_data) {
           return;
       }

       if (!isset($message_data['user_id'])) {
           return ;
       }





        // 根据类型执行不同的业务
        switch ($message_data['type']) {

            //{type:unread, to_user_id:xx, user_id: xxx}

            case 'history':

                // $from_user_id = $message_data['user_id'];
                // $to_user_id = $message_data['to_user_id'];
                //{type:history, to_user_id:xx, user_id: xxx,id:xxxx}
                $list =  Message::history($message_data);
                $new_message = ['type'=>'history','list'=>$list];
                self::debug($client_id, json_encode($new_message), 'out');
                return Gateway::sendToCurrentClient(json_encode($new_message));


            case 'updateunread':
                $from_user_id = $message_data['user_id'];
                $to_user_id = $message_data['to_user_id'];
                return Message::updateUnRead($from_user_id, $to_user_id);
            //{type:unread, to_user_id:xx, user_id: xxx}
            case 'unread':

                $from_user_id = $message_data['user_id'];
                $to_user_id = $message_data['to_user_id'];
                $new_message = ['type'=>'unread'];
                $list = Message::unRead($from_user_id, $to_user_id);
                $new_message['user_id'] = $to_user_id;
                $new_message['from_user'] = ClientUser::getUserInfoById($to_user_id);
                $new_message['to_user'] = ClientUser::getUserInfoById($from_user_id);
                $new_message['list'] = $list;
                self::debug($client_id, json_encode($new_message), 'out');
                return Gateway::sendToCurrentClient(json_encode($new_message));


            case 'list':
                //客户端请求格式{type:list}
                // $store = Store::instance('user');
                // $user_id = $store->get('laravel:client_id_'.$client_id);
                $user_id = $message_data['user_id'];
                $new_message = ClientUserFavorite::getFavoriteListById($user_id);
                $re_ary = array('type' => 'list','user_id' => $user_id,'data' => $new_message,'client_id' => $client_id);
                self::debug($client_id, json_encode($re_ary), 'out');
                return Gateway::sendToCurrentClient(json_encode($re_ary));

            // 客户端回应服务端的心跳
            case 'pong':
                return;
            // 客户端登录 message格式: {type:login, user_id:xxxx} ，添加到客户端，广播给所有客户端xx进入聊天室
            case 'login':
            case 're_login':


            // echo public_path();

                //如果接口中没有 user_id 则不返回数据
                if (!isset($message_data['user_id'])) {
                    return;
                }

                $message_data['user_id'] = trim($message_data['user_id']);
                $userInfo =  ClientUser::getUserInfoById($message_data['user_id']);
                $re_ary = array('type' => $message_data['type'],'user_id' => $message_data['user_id'],'data' => $userInfo,'client_id' => $client_id);
                // echo json_encode($re_ary)."\n";

                $store = Store::instance('user');

                $store->set('laravel:user_id_'.$message_data['user_id'], $client_id);
                $store->set('laravel:client_id_'.$client_id, $message_data['user_id']);

                $_SESSION['uid'] = $message_data['user_id'];

                Gateway::bindUid($client_id, $message_data['user_id']);

                self::debug($client_id, json_encode($re_ary), 'out');
                return ;

            // 客户端发言 message: {type:say, to_user_id:xx, content:xx}
            case 'say':

                // $room_id = $_SESSION['room_id'];
                // $client_name = $_SESSION['client_name'];


                $msg = $message_data['content'];
                $qian=array(" ","　","\t","\n","\r");$hou=array("","","","","");
                $new_msg = str_replace($qian, $hou, $msg);
                if (strlen($new_msg)<1) {
                    return ;
                }


                $store = Store::instance('user');
                $is_online = false;

                $to_client_id = $store->get('laravel:user_id_'.$message_data['to_user_id']);

                if (!empty($to_client_id)) {
                    $is_online_status = Gateway::isOnline($to_client_id);

                    if ($is_online_status) {
                        $is_online = true;
                    }
                }



                $to_user_info = ClientUser::getUserInfoById($message_data['to_user_id']);
                $from_user_info = ClientUser::getUserInfoById($message_data['user_id']);


                // 私聊
                $new_message = array(
                    'type' => 'say',
                    // 'from_client_id' => $client_id,
                    'user_id' => $message_data['user_id'],
                    'from_user' => $from_user_info,
                    // 'to_client_id' => $to_client_id,
                    'to_user' => $to_user_info,
                    'content' => $message_data['content'],
                    'time' => date('Y-m-d H:i:s'),
                );
                $mtype = 'text';

                if (isset($message_data['ext'])) {
                    // $mtype = $message_data['ext'];
                    // $file_path = public_path().'/'.$message_data['content'];
                    // if(file_exists($file_path))
                    // {
                        // $mtype = mime_content_type($file_path);
                    $mtype = $message_data['ext'];
                }




                $ret = Message::sendMessage($new_message, $mtype);

                if ($ret===false) {
                    return ;
                }

                $new_message['message_id'] = $ret['id'];
                $new_message['message_type'] = $ret['type'];
                if ($ret['type']=='text') {
                    $new_message['content'] = nl2br(htmlspecialchars($new_message['content']));
                } else {
                    $file =ClientUserFavorite::getIcon($new_message['content']);
                    $new_message['content'] = nl2br(htmlspecialchars($file));
                }
                // $new_message['content'] = nl2br(htmlspecialchars($new_message['content']));


                if ($is_online) {
                    // Gateway::sendToClient($to_client_id, json_encode($new_message));
                    Gateway::sendToUid($message_data['to_user_id'], json_encode($new_message));

                    $new = [
                        'type' => 'unreadstatus',
                        'user_id' => $message_data['user_id'],
                    ];
                    Gateway::sendToUid($message_data['to_user_id'], json_encode($new));
                }

                self::debug($client_id, json_encode($new_message), 'out');


                return Gateway::sendToCurrentClient(json_encode($new_message));



        }
   }

   /**
    * 当客户端断开连接时.
    *
    * @param int $client_id 客户端id
    */
   public static function onClose($client_id)
   {
       echo "onClose client:{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']} gateway:{$_SERVER['GATEWAY_ADDR']}:{$_SERVER['GATEWAY_PORT']}  client_id:$client_id onClose:''\n";
       GateWay::sendToAll(json_encode(array('type'=>'closed', 'id'=>$client_id)));
       //当客户端断开连接以后.需要删除 redis的数据
       $store = Store::instance('user');
       //选获取用户的 id
       $user_id = $store->get('laravel:client_id_'.$client_id);

       $store->delete('laravel:user_id_'.$user_id);
       $store->delete('laravel:client_id_'.$client_id);
       return ;
   }
}
