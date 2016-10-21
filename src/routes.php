<?php

// Routes
//login
$app->group('/login', function() {

    $this->get( '/', function ($request, $response, $args) {
        return \App\Controller\BaseController::renderWithLayout(
            $this->renderer,
            $response,
            'login.phtml',
            'layouts/simple-layout.phtml'
        );
    });

    $this->get( '/{idp}/',  function ($request, $response, $args) {
        //TODO get some error handling in
        $this->logger->addDebug("Logging in user with '{$args['idp']}'");
        $adapter      = $this->hybridauth->authenticate( ucwords( $args['idp'] ) );
        $user_profile = $adapter->getUserProfile();
        if (empty( $user_profile )) {
            $this->logger->addDebug("Unable to log in user with '{$args['idp']}'");
            return $response->withRedirect( '/login/?err=1' );
        }
        $identifier = $user_profile->identifier;
        if ($this->socialauth->identifier_exists( $identifier )) {
            $this->logger->addDebug("Logging in existing user with '{$args['idp']}' login");
            $this->socialauth->login_user( $identifier );
            return $response->withRedirect( '/welcome/' );
        } else {
            $this->logger->addDebug("Regisering new user with '{$args['idp']}' login");
            $register = $this->socialauth->register_user(
                $identifier,
                $user_profile->email,
                $user_profile->firstName,
                $user_profile->lastName,
                $user_profile->photoURL
            );
            if ($register) {
                $this->logger->addDebug("Logging in new user with '{$args['idp']}' login");
                $this->socialauth->login_user( $identifier );
                return $response->withRedirect( '/welcome/' );
            }
            else{
                $this->logger->addError("Failed regisering new user with '{$args['idp']}' login");
                return $response->withRedirect( '/login/?err=2' );
            }
        }
    });
});

$app->get( '/logout/', function ($request, $response, $args) {
    $this->hybridauth;
    $this->socialauth->logout_user();
    Hybrid_Auth::logoutAllProviders();
    return $response->withRedirect( '/login/' );
});

$app->get( '/welcome/', function ($request, $response, $args) {
    //TODO make a nice welcome landing page
    return $response->withRedirect( '/auth/dashboard/' );
});

$app->get( '/oauth2endpoint/', function ($request, $response, $args) {
        Hybrid_Endpoint::process();
});

$app->get( '/exception_test/', function ($request, $response, $args) {
    throw new Exception('Fake Error For Testing');
});

$app->get( '/hybrid/', function ($request, $response, $args) {
        return $response->withRedirect( '/oauth2endpoint/' );
});


//all of these require authentication first
$app->group('/auth', function() {

    //dashboard controller
    $this->get('/dashboard/', function ($request, $response, $args) {

        //example of getting the model
        $this->model;
        $session_information = \App\Model\User::getCurrentSessionUser();

        return \App\Controller\BaseController::renderWithLayout(
            $this->renderer,
            $response,
            'welcome.phtml',
            'layouts/dashboard-layout.phtml',
            ['session_user' => $session_information->toArray() ]
        );
    });

    //leads controller
    $this->group('/dashboard/leads', function() {
        $this->get('/', function ($request, $response, $args) {
            $endDate = new DateTime();
            $endDate->setTime(0, 0, 0);
            $startDate = clone($endDate);
            $startDate->sub(new DateInterval('P7D'));
            $params = new App\Model\LeadsFetchParams( $startDate, $endDate, 'm/d/Y' );
            $leads = $this->model['leads']->getLeads($params);
            return \App\Controller\BaseController::renderWithLayout(
                $this->renderer,
                $response,
                'leads.phtml',
                'layouts/dashboard-layout.phtml',
                ['data' => $leads ]
            );
            $leadsController = new App\Controller\Dashboard\LeadsController($request, $response, $args);
            return $leadsController->renderLastWeek();
        });
        $this->get('/all/', function ($request, $response, $args) {
            $leads = $this->model['leads']->getLeads();
            return \App\Controller\BaseController::renderWithLayout(
                $this->renderer,
                $response,
                'leads.phtml',
                'layouts/dashboard-layout.phtml',
                ['data' => $leads ]
            );
        });
        $this->get('/{year}/{month}/{day}/', function ($request, $response, $args) {
            $endDate = DateTime::CreateFromFormat('Y/m/d', $args['year'] . '/' . $args['month'] . '/' . $args['day']);
            $startDate = clone($endDate);
            $startDate->sub(new DateInterval('P7D'));
            $params = new App\Model\LeadsFetchParams( $startDate, $endDate, 'm/d/Y' );
            $leads = $this->model['leads']->getLeads($params);
            return \App\Controller\BaseController::renderWithLayout(
                $this->renderer,
                $response,
                'leads.phtml',
                'layouts/dashboard-layout.phtml',
                ['data' => $leads ]
            );
        });
        $this->get('/{start_year}/{start_month}/{start_day}/{end_year}/{end_month}/{end_day}/', function ($request, $response, $args) {
            $endDate = DateTime::CreateFromFormat('Y/m/d', $args['end_year'] . '/' . $args['end_month'] . '/' . $args['end_day']);
            $startDate = DateTime::CreateFromFormat('Y/m/d', $args['start_year'] . '/' . $args['start_month'] . '/' . $args['start_day']);
            $params = new App\Model\LeadsFetchParams( $startDate, $endDate, 'm/d/Y' );
            $leads = $this->model['leads']->getLeads($params);
            return \App\Controller\BaseController::renderWithLayout(
                $this->renderer,
                $response,
                'leads.phtml',
                'layouts/dashboard-layout.phtml',
                ['data' => $leads ]
            );
        });
    });

})->add( new \App\Middleware\AuthenticationMiddleware( $app ) );
