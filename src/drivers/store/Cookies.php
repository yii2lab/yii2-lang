<?php

namespace yii2module\lang\drivers\store;

use Yii;
use yii\web\Cookie as YiiCookie;

class Cookies implements StoreInterface {
	
	public $key;
	public $extra;
	
	public function set($value) {
		$cookie = new YiiCookie([
			'name' => $this->key,
			'domain' => $this->getCookieDomain(),
			'value' => $value,
			'expire' => time() + 86400 * $this->getExpireDays(),
		]);
		Yii::$app->response->cookies->add($cookie);
	}
	
	public function get($def = null) {
		$value = Yii::$app->request->cookies->getValue($this->key);
		if($value === null) {
			$value = $def;
		}
		return $value;
	}

	public function has() {
		return Yii::$app->response->cookies->has($this->key);
	}

	public function remove()
	{
		return Yii::$app->response->cookies->remove($this->key);
	}
	
	private function getCookieDomain()
	{
		return !empty($this->extra['cookieDomain']) ? $this->extra['cookieDomain'] : '';
	}
	
	private function getExpireDays()
	{
		return !empty($this->extra['expireDays']) ? $this->extra['expireDays'] : 365;
	}
	
}
