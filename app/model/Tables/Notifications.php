<?php

namespace Model;


/**
 * @author Tomáš Skalický
 */
class Notifications extends Table
{
	/** @var string */
	protected $tableName = 'notifications';
	static $usableLinks = array(
	    'person' => array(
		'table' => 'users',
		'cols' => array(
		    'email',
		),
	    ),
	);
	
	/**
	 * Creates new notification
	 * 
	 * @param id $userid id of user who is going to receive the notification
	 * @param string $link destination
	 * @param array $link_params parameters of destination
	 * @param string $message message of notification
	 * @param array $attr attributes of notification
	 * @throws NotEqualCountException thrown when number of placeholders in message is not same as number of attributes
	 * @return \Nette\Database\Table\ActiveRow created row
	 */
	public function addNotification($userid, $link, $link_params, $message, $attr = array()) {
	    $keys = array_keys(self::$usableLinks);
	    foreach($keys as $key=>$val) {
		$keys[$key] = "\{" . $val . "\}";
	    }
	    $regexp = implode($keys, "|");
	    $res = \Nette\Utils\Strings::matchAll($message, '~'.$regexp.'~');
	    if(count($res) != count($attr))
		throw new NotEqualCountException('Ve zprávě musí být stejný počet holderů jako je atributů');
	    return $this->createRow(array(
		'users_id' => $userid,
		'time' => new \Nette\Database\SqlLiteral("NOW()"),
		'link' => $link,
		'link_params' => json_encode($link_params),
		'message' => $message,
		'attr' => json_encode($attr),
	    ));
	}
        
	/**
	 * Returns user's notifications
	 * 
	 * @param type $userId id of user
	 * @return \Nette\Database\Table\Selection Notifications
	 */
	public function getNotifications($userId){
	    $rows = $this->findBy(array('users_id' => $userId))->order('time DESC');
	    $not = array();
	    foreach($rows as $key=>$val) {
		$attr = json_decode($val['attr']);
		$not[$key]['attr'] = $attr;
		$not[$key]['link_params'] = (array)json_decode($val['link_params']);
		$not[$key]['link'] = $val['link'];
		$not[$key]['message'] = $this->replaceHolder($val['message'], $attr);
		$not[$key]['time'] = $val['time'];
	    }
	    
	    $rows->update(array(
		'seen' => 1,
	    ));
	    return $not;
	}
	
	/** 
	 * Returns count of user's unseen notification 
	 * 
	 * @param type $userId id of user
	 * @return int Count of user's unseen notification 
	 */
	public function getUnseenNotificationsCount($userId) {
	    return $this->findBy(array('users_id' => $userId, 'seen' => 0))->count();
	}
	
	/**
	 * Replaces placeholders in message with informations from database
	 * 
	 * @param string $message message of notification
	 * @param array $attr attributes of notification
	 * @return string final message
	 */
	private function replaceHolder($message, $attr) {
	    $keys = array_keys(self::$usableLinks);
	    foreach($keys as $key=>$val) {
		$keys[$key] = "\{" . $val . "\}";
	    }
	    $regexp = implode($keys, "|");
	    $GLOBALS['iter'] = 0;
	    $message = preg_replace_callback('~'.$regexp.'~', function($matches) use($attr) {
		$info = self::$usableLinks[trim($matches[0], '{}')];
		$data = $this->connection->table($info['table'])->find($attr[$GLOBALS['iter']++])->select(implode($info['cols'], ", "))->fetch()->toArray();
		return implode($data, " ");
	    }, $message);
	    unset($GLOBALS['iter']);
	    return $message;
	}
	
}

class NotEqualCountException extends \Exception{}