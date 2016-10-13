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

    $this->group('/dashboard/leads', function() {
    
        $this->get('/', function($request, $response, $args) {
            $endDate = new DateTime();
            $endDate->setTime(0, 0, 0);
            $startDate = clone($endDate);
            $startDate->sub(new DateInterval('P7D'));
            $leadsFetchParams = new App\Model\LeadsFetchParams( $startDate, $endDate, 'm/d/Y' );
            $leads = $this->model['leads']->getLeads($leadsFetchParams);
            return $this->renderer->render($response, 'leads.phtml', ['data' => $leads ]);
        });
        
        $this->get('/all/', function($request, $response, $args) {
            $leads = array();
            $leads = $this->model['leads']->getLeads();
            return $this->renderer->render($response, 'leads.phtml', ['data' => $leads ]);
        });

        $this->get('/{year}/{month}/{day}/', function($request, $response, $args) {
            $endDate = DateTime::CreateFromFormat('Y/m/d', $args['year'] . '/' . $args['month'] . '/' . $args['day']);
            $startDate = clone($endDate);
            $startDate->sub(new DateInterval('P7D'));
            $leadsFetchParams = new App\Model\LeadsFetchParams( $startDate, $endDate, 'm/d/Y' );
            $leads = $this->model['leads']->getLeads($leadsFetchParams);
            return $this->renderer->render($response, 'leads.phtml', [ 'data' => $leads ]);
        });

        $this->get('/{start_year}/{start_month}/{start_day}/{end_year}/{end_month}/{end_day}/', function($request, $response, $args) {
            $endDate = DateTime::CreateFromFormat('Y/m/d', $args['end_year'] . '/' . $args['end_month'] . '/' . $args['end_day']);
            $startDate = DateTime::CreateFromFormat('Y/m/d', $args['start_year'] . '/' . $args['start_month'] . '/' . $args['start_day']);
            $leadsFetchParams = new App\Model\LeadsFetchParams( $startDate, $endDate, 'm/d/Y' );
            $leads = $this->model['leads']->getLeads($leadsFetchParams);
            return $this->renderer->render($response, 'leads.phtml', [ 'data' => $leads ]);
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
