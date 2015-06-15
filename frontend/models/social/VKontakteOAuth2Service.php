<?php
/**
 * An example of extending the provider class.
 *
 * @author Maxim Zemskov <nodge@yandex.ru>
 * @link http://github.com/Nodge/yii2-eauth/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace frontend\models\social;

use yii\helpers\ArrayHelper;
class VKontakteOAuth2Service extends \nodge\eauth\services\VKontakteOAuth2Service
{

	const SCOPE_FRIENDS2 = 'email, wall';
	protected $scopes = array(self::SCOPE_FRIENDS2);
	//protected $scope = 'email,video,offline';
	

	
	protected function fetchAttributes()
	{
		
		$tokenData = $this->getAccessTokenData();
		$info = $this->makeSignedRequest('users.get.json', array(
			'query' => array(
				'uids' => $tokenData['params']['user_id'],
				//'fields' => '', // uid, first_name and last_name is always available
				'fields' => 'nickname, sex, bdate, city, country, timezone, photo, photo_medium, photo_big, photo_rec',
			),
		));
		
		//$this->fooApiMethod($tokenData['params']['user_id']);
		
		/*var_dump($tokenData);
		var_dump($info);
		die();*/
		

		$info = $info['response'][0];
	
		$this->attributes = $info;
		$this->attributes['id'] = $info['uid'];
		$this->attributes['email'] = $tokenData['params']['email'];
		$this->attributes['name'] = $info['first_name'] . ' ' . $info['last_name'];
		$this->attributes['link'] = 'http://vk.com/id' . $info['uid'];
		$this->attributes['image'] = $info['photo_medium']; 
		//$this->attributes['link'] = 'http://vk.com/'.$info['username']; 

		if (!empty($info['nickname'])) {
			$this->attributes['username'] = $info['nickname'];
		} else {
			$this->attributes['username'] = 'id' . $info['uid'];
		}

		$this->attributes['gender'] = $info['sex'] == 1 ? 'F' : 'M';

		if (!empty($info['timezone'])) {
			$this->attributes['timezone'] = timezone_name_from_abbr('', $info['timezone'] * 3600, date('I'));
		}

		return true;
	}
}
