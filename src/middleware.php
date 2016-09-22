<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

//Add SlimApi\OAuth\OAuthMiddleware from container
$app->add( $app->getContainer()->get('SlimApi\OAuth\OAuthMiddleware') );