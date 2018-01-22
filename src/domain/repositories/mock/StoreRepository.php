<?php

namespace yii2module\lang\domain\repositories\mock;

use yii2lab\domain\repositories\BaseRepository;
use yii2module\lang\domain\interfaces\repositories\StoreInterface;

class StoreRepository extends BaseRepository implements StoreInterface {
	
	public $key = 'language';
	public $extra;
	
	private $current;
	
	public function set($value) {
		$this->current = $value;
	}
	
	public function get($def = null) {
		return $this->current;
	}
	
	public function has() {
		return empty($this->current);
	}
	
	public function remove()
	{
		return $this->current = null;
	}
	
}
