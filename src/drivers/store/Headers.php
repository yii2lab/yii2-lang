<?php

namespace yii2lab\lang\drivers\store;

use Yii;
use yii\base\Component;

class Headers extends Component implements StoreInterface {

	public $key;
	public $extra;
	
	public function set($value) {
		Yii::$app->response->headers->add($this->key, $value);
	}
	
	public function get($def = null) {
		return Yii::$app->request->headers->get($this->key, $def);
	}

	public function has() {
		return Yii::$app->response->headers->has($this->key);
	}

	public function remove()
	{
		return Yii::$app->response->headers->remove($this->key);
	}
	
}
