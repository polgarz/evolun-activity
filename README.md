Activity module for Evolun
=======

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist polgarz/evolun-activity "@dev"
```

or add

```
"polgarz/evolun-activity": "@dev"
```

to the require section of your `composer.json` file.

Configuration
-----

```php
'modules' => [
    'activity' => [
        'class' => 'evolun\activity\Module',
    ],
],
```