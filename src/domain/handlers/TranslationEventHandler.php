<?php

namespace yii2module\lang\domain\handlers;

use yii\helpers\Inflector;
use yii\i18n\MissingTranslationEvent;

class TranslationEventHandler
{
    public static function handleMissingTranslation(MissingTranslationEvent $event) {
	    if(YII_DEBUG && APP != CONSOLE) {
		    $event->translatedMessage = "[{$event->category}, {$event->message}]";
	    } else {
		    $event->translatedMessage = Inflector::titleize($event->message);
	    }
    }
}
