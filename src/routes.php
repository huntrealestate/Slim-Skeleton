<?php

// Routes
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
    
    $this->get('/google/', function($request, $response, $args) {
         return \App\Controller\BaseController::renderWithLayout(
            $this->renderer,
            $response,
            'dev-dump.phtml',
            'layouts/simple-layout.phtml',
            [
                'dump' => [
                    'google'=>$this->google,
                    'google_api'=>$this->google_api,
                    'leads' => $this->model['leads']
                ]
            ]
        );
    })->setName('dev-google-dump');
    
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

// API routes return json
$app->group('/api/v1', function() {
    
    //Average total listings controller
    $this->group('/listings/total/average', function() {
        $this->get('/', '\App\Controller\Api\AvgTotalListingsController:current')->setName('avg-total-listing-data');
        $this->get('/all/', '\App\Controller\Api\AvgTotalListingsController:all')->setName('all-avg-total-listing-data');
        $this->get('/{year}/{month}/{day}/', '\App\Controller\Api\AvgTotalListingsController:past')->setName('past-avg-total-listing-data');
        $this->get(
            '/{start_year}/{start_month}/{start_day}/{end_year}/{end_month}/{end_day}/',
            '\App\Controller\Api\AvgTotalListingsController:custom'
        )->setName('custom-range-avg-total-listing-data');
    });
    
    //Average total listings controller
    $this->group('/deals', function() {
        $this->get('/', '\App\Controller\Api\DealsController:current')->setName('deals-data');
        $this->get('/all/', '\App\Controller\Api\DealsController:all')->setName('all-deals-data');
        $this->get('/{year}/{month}/{day}/', '\App\Controller\Api\DealsController:past')->setName('past-deals-data');
        $this->get(
            '/{start_year}/{start_month}/{start_day}/{end_year}/{end_month}/{end_day}/',
            '\App\Controller\Api\DealsController:custom'
        )->setName('custom-range-deals-data');
    });

})->add( new \App\Middleware\AuthenticationMiddleware( $app ) );
