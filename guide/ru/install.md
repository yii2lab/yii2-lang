Установка
===

Устанавливаем зависимость:

```
composer require yii2module/yii2-lang
```

Объявляем common/config/main:

```php
use yii2module\lang\domain\enums\LanguageEnum;

return [
    ...
	'language' => LanguageEnum::RU, // current Language
	'sourceLanguage' => LanguageEnum::SOURCE, // Language development
	...
	'bootstrap' => [..., 'language', ...],
	...
	'components' => [
        ...
	    'language' => 'yii2module\lang\domain\components\Language',
	    'i18n' => [
			'class' => 'yii2module\lang\domain\i18n\I18N',
			'translations' => [
				'*' => [
					'class' => 'yii2module\lang\domain\i18n\PhpMessageSource',
					'basePath' => '@common/messages',
					'on missingTranslation' => ['yii2module\lang\domain\handlers\TranslationEventHandler', 'handleMissingTranslation'],
				],
			],
		],
		...
	],
];
```

Обработчик недостающего перевода `on missingTranslation` желательно назначать всем источникам перевода,
чтобы обработка была единообразной.

Объявляем common модуль:

```php
return [
	'modules' => [
		...
		'lang' => [
			'class' => 'yii2module\lang\module\Module',
		],
        ...
	],
];
```

Объявляем домен:

```php
return [
		// ...
		'lang' => 'yii2module\lang\domain\Domain',
		// ...
];
```
