<?php
/**
 * Language component
 */
namespace yii2module\lang\components;

use Yii;
use yii\base\Component;
use yii2module\lang\helpers\drivers\Cookie as Store;

/**
 * LngComponent
 *
 *  configuration script
	'bootstrap' => ['lng'],
	'components' => [
		'lng' => [
		'class' => 'common\components\LngComponent',
		'store' => [
			'key' => 'language',					 // Name of the cookie.
			'cookieDomain' => 'example.com',				// Domain of the cookie.
			'expireDays' => 64, // The expiration time of the cookie is 64 days.
		],
		'languages' => [
			[
				'title' => 'English',
				'code' => 'en',
				'locale' => 'en-Uk',
				'is_main' => false,
			],
		],
		'callback' => function() {
			if (!\Yii::$app->user->isGuest) {
				$user = User::findOne(\Yii::$app->user->id);
				$user->language = \Yii::$app->language;
				$user->save();
			}
		}
		],
	],
 */
class LngComponent extends Component
{
	public $languages = [];
	public $store = [];
	public $callback;
	
	protected $storeIt;
	
	public function init()
	{
		require_once(Yii::getAlias('@woop/module/lang/helpers/Func.php'));
		if(YII_ENV_TEST) {
			$this->languages[] = [
				'title' => 'Source',
				'code' => 'xx',
				'locale' => 'xx-XX',
				'is_main' => false,
			];
		}
		if(APP != CONSOLE) {
			$this->initStore();
			$this->initLanguage();
		}
		parent::init();
	}

	public function initStore()
	{
		$config = $this->store;
		$config['key'] = !empty($config['key']) ? $config['key'] : 'language';
		$config['extra'] = !empty($config['extra']) ? $config['extra'] : [];
		$this->storeIt = Yii::createObject($config);
	}
	
	public function saveLanguage($language = null)
	{
		$language = $this->filterLanguage($language);
		Yii::$app->language = $language;
		$this->storeIt->set($language);
		if (is_callable($this->callback)) {
			call_user_func($this->callback);
		}
	}

	public function getMainLanguage($format = 'code')
	{
		foreach ($this->languages as $lng) {
			if (isset($lng[$format]) && $lng['is_main']) {
				return $lng[$format];
			}
		}
		return false;
	}

	public function getAllLanguages()
	{
		return $this->languages;
	}

	protected function initLanguage()
	{
		$languageFromStore = $this->storeIt->get();
		if ($this->isValidLanguage($languageFromStore)) {
			Yii::$app->language = $languageFromStore;
		} else {
			$languageFromUserAgent = $this->detectLanguageFromUserAgent();
			$this->saveLanguage($languageFromUserAgent);
		}
	}
	
	protected function detectLanguageFromUserAgent()
	{
		$acceptableLanguages = Yii::$app->getRequest()->getAcceptableLanguages();
		foreach ($acceptableLanguages as $language) {
			if ($this->isValidLanguage($language)) {
				return $language;
			}
		}
		foreach ($acceptableLanguages as $language) {
			$pattern = preg_quote(substr($language, 0, 2), '/');
			foreach ($this->languages as $value) {
				if (preg_match('/^' . $pattern . '/', $value['locale'])) {
					return $value['locale'];
				}
			}
		}
	}
	
	protected function filterLanguage($language = null)
	{
		if (empty($language) || !$this->isValidLanguage($language)) {
			$language = $this->getMainLanguage();
		}
		return $language;
	}
	
	protected function isValidLanguage($language)
	{
		if(!empty($language) && is_string($language)) {
			foreach ($this->languages as $lng) {
				if ($lng['locale'] == $language || $lng['code'] == $language) {
					return true;
				}
			}
		}
		return false;
	}

	/* protected function localeToCode($locale = null)
	{
		if ($locale == null) {
			$locale = Yii::$app->language;
		}
		$result = explode('-', $locale);
		return (!empty($result[0])) ? $result[0] : null;
	} */
	
}
