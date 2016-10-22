<?php

// Routes
require_once __DIR__ . '/app/helpers/RouteHelpers.php';

//login
$app->group('/login', function() {
    $this->get( '/', '\App\Controller\Authentication:getLogin')->setName('login');
    $this->post( '/', '\App\Controller\Authentication:postLogin')->setName('login-submission');
    $this->get( '/{idp}/',  '\App\Controller\Authentication:oauthLogin')->setName('oauth-login');
});
$app->get( '/logout/', '\App\Controller\Authentication:logout')->setName('logout');
$app->get( '/welcome/','\App\Controller\Authentication:welcome')->setName('welcome');
$app->get( '/oauth2endpoint/','\App\Controller\Authentication:oauth2endpoint')->setName('oauth2endpoint');
$app->get( '/hybrid/','\App\Controller\Authentication:oauth2endpoint')->setName('old-oauth2endpoint');

$app->get( '/exception_test/', function ($request, $response, $args) {
    throw new Exception('Fake Error For Testing');
});

//all of these require authentication first
$app->group('/auth', function() {
    
    //dashboard controller
    $this->get('/dashboard/', '\App\Controller\Profile:profile')->setName('default-dashboard');
    
    //leads controller
    $this->group('/dashboard/leads', function() {
        $this->get('/', '\App\Controller\Leads:current')->setName('leads-dashboard');
        $this->get('/all/', '\App\Controller\Leads:all')->setName('all-leads-dashboard');
        $this->get('/{year}/{month}/{day}/', '\App\Controller\Leads:past')->setName('past-leads-dashboard');
        $this->get(
            '/{start_year}/{start_month}/{start_day}/{end_year}/{end_month}/{end_day}/',
            '\App\Controller\Leads:custom'
        )->setName('custom-range-leads-dashboard');
    });
})->add( new \App\Middleware\AuthenticationMiddleware( $app ) );
