<?php
/**
 * An example of extending the provider class.
 *
 * @author Maxim Zemskov <nodge@yandex.ru>
 * @link http://github.com/Nodge/yii2-eauth/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace frontend\models\social;

class FacebookOAuth2Service extends \nodge\eauth\services\FacebookOAuth2Service
{

	protected $scopes = array(
		self::SCOPE_EMAIL,
		self::SCOPE_USER_BIRTHDAY,
		self::SCOPE_USER_HOMETOWN,
		self::SCOPE_USER_LOCATION,
		self::SCOPE_USER_PHOTOS,
	);

	/**
	 * http://developers.facebook.com/docs/reference/api/user/
	 *
	 * @see FacebookOAuth2Service::fetchAttributes()
	 */
	protected function fetchAttributes()
	{
		$this->attributes = $this->makeSignedRequest('me');
		
		//var_dump($this->attributes);
		//die;
		if(!$this->attributes['email']){
			$this->attributes['email'] = '';
		}
		
		//$this->attributes['image'] = '//graph.facebook.com/'.$this->attributes['id'].'/picture?type=normal'; 
		$this->attributes['image'] = '//graph.facebook.com/'.$this->attributes['id'].'/picture?width=100&height=100'; 
		return true;
	}
}
