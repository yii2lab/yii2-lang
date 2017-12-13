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
	'bootstrap' => [..., 'language', ...],
	'components' => [
        ...
	    'language' => 'yii2module\lang\domain\components\Language',
        ...
	],
];

```

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
	'components' => [
		// ...
		'lang' => [
            'class' => 'yii2lab\domain\Domain',
            'path' => 'yii2module\lang\domain',
            'repositories' => [
                'language' => Driver::DISC,
                'store' => APP == API ? Driver::HEADER : Driver::COOKIE,
            ],
            'services' => [
                'language',
            ],
        ],
		// ...
	],
];
```
