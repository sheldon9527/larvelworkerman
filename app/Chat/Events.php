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

       return ;
   }
}
