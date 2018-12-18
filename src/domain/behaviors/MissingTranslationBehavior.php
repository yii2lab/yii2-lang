<?php

namespace yii2module\lang\domain\behaviors;

use yii\base\Behavior;
use yii\base\Event;
use yii\helpers\Inflector;
use yii\i18n\MessageSource;
use yii\i18n\MissingTranslationEvent;
use yii2lab\domain\enums\EventEnum;
use yii2lab\domain\events\MethodEvent;
use yii2lab\domain\repositories\BaseRepository;
use yii2lab\domain\services\base\BaseService;

class MissingTranslationBehavior extends Behavior {

	public function events() {
		return [
            MessageSource::EVENT_MISSING_TRANSLATION => 'prepare',
		];
	}
	
	public function prepare(MissingTranslationEvent $event) {
        if(YII_DEBUG && APP != CONSOLE) {
            $event->translatedMessage = "[{$event->category}, {$event->message}]";
        } else {
            $event->translatedMessage = Inflector::titleize($event->message);
        }
	}

}
