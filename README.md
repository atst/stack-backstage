# Atst/StackBackstage

A [Stack](http://stackphp.com) middleware for displaying maintenance pages. 

The middleware takes a path, if a file exists at that path, it's contents
are returned with a 503 status code.

## Example

``` php
<?php

$app = new Silex\Application();

$app->get('/', function () {
    return 'my app is working';
});

$stack = (new Stack\Builder())
    ->push('Atst\StackBackstage', __DIR__.'/maintenance.html');

$app = $stack->resolve($app);

```

## Usage

When you need to take the site down for maintenance

    $ echo "<h1>Down for Maintenance</h1>" > /path/to/your/app/maintenance.html

## Inspiration

Ported from rack/rack-contrib
