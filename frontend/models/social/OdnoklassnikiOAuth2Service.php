<?php
/**
 * An example of extending the provider class.
 *
 * @author Maxim Zemskov <nodge@yandex.ru>
 * @link http://github.com/Nodge/yii2-eauth/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

namespace frontend\models\social;

use yii\helpers\VarDumper;

class OdnoklassnikiOAuth2Service extends \nodge\eauth\services\OdnoklassnikiOAuth2Service
{
	const SCOPE_GET_EMAIL = 'GET_EMAIL';
	protected $scopes = array(self::SCOPE_VALUABLE_ACCESS, self::SCOPE_GET_EMAIL); //, self::SCOPE_GET_EMAIL
	//protected $scopes = array(self::SCOPE_GET_EMAIL2); //, self::SCOPE_GET_EMAIL

	protected function fetchAttributes()
	{
		parent::fetchAttributes();
		
		
		$info = $this->makeSignedRequest('', array(
			'query' => array(
				'method' => 'users.getCurrentUser', //users.getInfo
				'uids' => $this->attributes['id'],
				'fields' => 'url_profile',
				'format' => 'JSON',
				'application_key' => $this->clientPublic,
				'client_id' => $this->clientId,
				'fields' => 'UID, LOCALE, FIRST_NAME, LAST_NAME, NAME, GENDER, AGE, BIRTHDAY, HAS_EMAIL, CURRENT_STATUS, CURRENT_STATUS_ID, CURRENT_STATUS_DATE, ONLINE, PHOTO_ID, PIC_1, PIC_2, LOCATION, EMAIL',
			),
		));
		
		$tokenData = $this->getAccessTokenData();
		/*var_dump($info['email']);
		
		 VarDumper::dump($info, 10, true);
		 VarDumper::dump($tokenData, 10, true);
		die;*/

		//preg_match('/\d+\/{0,1}$/', $info[0]->url_profile, $matches);
		//$this->attributes['id'] = (int)$matches[0];
		//$this->attributes['url'] = $info[0]->url_profile;
		/*$this->attributes = $info;*/
		
		
		$this->attributes = $info;
		$this->attributes['id'] = $info['uid'] ;
		if($info['email']){
			$this->attributes['email'] = $info['email'];
		}
		else
		{
			$this->attributes['email'] = '';
		}
		
		$this->attributes['name'] = $info['name'];
		$this->attributes['link'] = 'http://ok.ru/profile/' . $info['uid'];
		$this->attributes['image'] = $info['pic_2']; 
		
		

		return true;
	}


	/**
	 * @param string $link
	 * @param string $message
	 * @return array
	 */
	public function wallPost($link, $message)
	{
		return $this->makeSignedRequest('', array(
			'query' => array(
				'application_key' => $this->clientPublic,
				'method' => 'share.addLink',
				'format' => 'JSON',
				'linkUrl' => $link,
				'comment' => $message,
			),
		));
	}

}
