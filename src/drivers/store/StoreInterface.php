<?php

namespace yii2lab\lang\drivers\store;

interface StoreInterface {
	
	public function set($value);
	public function get($def = null);
	public function has();
	public function remove();
	
}
