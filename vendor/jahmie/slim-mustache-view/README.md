# Slim Mustache View

Mustache View package for the Slim Framework 3+,
using [bobthecow](https://github.com/bobthecow/mustache.php) great PHP implementation of Mustache! ;-)

## How to install

#### Using [Composer](http://getcomposer.org/)


```bash
$ composer require jahmie/slim-mustache-view
```

## How to use

```php
<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim();
$container = $app->getContainer();
$container['view'] = function ($c) {
    $mustache = new \Slim\Mustache\Mustache(
        '/templates', // Template path
        array(
            'charset' => 'UTF-8',
        ),
        array(
            'extension' => '.html'
        )
    );
    return $mustache;
};
$app->get('/', function (Request $request, Response $response) {
    // The render method takes the reponse object,
    // template name and finally some data as an array.
    $response = $this->view->render($response, "hello", ["foo" => 'bar']);
    return $response;
});
```

### Methods

#### ```render($templateName, $data)```   
Renders the Mustache template with the ResponseInterface used by Slim; ```$response->getBody()->write($output);```

#### ```getRenderedMarkup($templateName, $data)```
Returns the renderd Mustache template as a string.

#### ```setTemplatePath($templatePath)```
Set the template path where Mustache will look for template files.

#### ```setLoaderOptions($loaderOptions)```
Set the options for Mustache filesystem loader. See; https://github.com/bobthecow/mustache.php/wiki/Template-Loading

#### ```setOptions($options)```
Set the Mustache options. See; https://github.com/bobthecow/mustache.php/wiki

## Authors

[Jamie Telin](https://github.com/jahmie/)

## License

The MIT License (MIT)

## Change log

### 1.0.1

* Added method ```getRenderedMarkup($templateName, $data)``` which allow to fetch the processed markup as raw html string.
* Fixed some typos.
* Better readme, because you all read this right? :-D

### 1.0

* First version y'all!
