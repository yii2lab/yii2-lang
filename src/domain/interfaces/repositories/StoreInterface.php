<?php

namespace yii2module\lang\domain\interfaces\repositories;

interface StoreInterface {
	
	public function set($value);
	public function get($def = null);
	public function has();
	public function remove();
	
}
