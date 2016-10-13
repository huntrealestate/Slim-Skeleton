<?php

// Routes

$app->group('/login', function() {

    $this->get( '/', function () {
        return $this->renderer->render($response, 'login.phtml');
    });

    $this->get( '/:idp', function ($request, $response, $args) {
        //TODO get some error handling in
        $this->socialauth = $this->socialauth;
        $adapter      = $this->hybridInstance->authenticate( ucwords( $idp ) );
        $user_profile = $adapter->getUserProfile();
        if (empty( $user_profile )) {
            $this->redirect( '/login/?err=1' );
        }
        $identifier = $user_profile->identifier;
        if ($this->socialauth->identifier_exists( $identifier )) {
            $this->socialauth->login_user( $identifier );
            $this->redirect( '/welcome/' );
        } else {
            $register = $this->socialauth->register_user(
                $identifier,
                $user_profile->email,
                $user_profile->firstName,
                $user_profile->lastName,
                $user_profile->photoURL
            );
            if ($register) {
                $this->socialauth->login_user( $identifier );
                $this->redirect( '/welcome/' );
            }
        }
    });
});

$app->get( '/logout/', function () {
    $this->hybridInstance;
    $this->socialauth->logout_user();
    Hybrid_Auth::logoutAllProviders();
    $app->redirect( '/login/' );
});

//all of these require authentication first
$app->group('/auth', function() {

    $this->group('/dashboard', function() {

        $this->get( '/', function ($request, $response, $args) {
                $this->renderer->render( 'dashboard.phtml' );
        });

        $this->get('/leads/', function($request, $response, $args) {
            //TODO take in Jason's changes
        });

        $this->get('/raw_data/', function($request, $response, $args) {
            $leads = array();
            $google_doc_id = '19Ya9gHRcS6dYFQX6aTJsbZmAfuNVpEB1lSG5a07_930';
            $csv_url = "https://docs.google.com/spreadsheets/d/{$google_doc_id}/export?format=csv&id={$google_doc_id}";
            $parser = new App\Utils\LeadCsvParser();
            $csvDownloader = new App\Utils\LeadCsvDownloader( $parser, $csv_url );
            $leadModel = new App\Model\Leads($csvDownloader);
            $leads = $leadModel->getLeads();
            return $this->renderer->render($response, 'leads.phtml', ['data' => $leads ]);
        });
    });

})->add( new \App\Middleware\AuthenticationMiddleware() );

/*
Example Routes
$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get('/{name}/dashboard', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'dashboard.phtml', $args);
});
*/
