<?php

// Routes

$app->get( '/login/:idp', function ($request, $response, $args) {
        try {
            $user_profile = $app->hybridauth->authenticate( ucwords( $args['idp'] ) ) ->getUserProfile();
        } catch ( Exception $e ) {
            //TODO put in better error handling/messaging
            $this->logger->info("Error authenticating user: "); $e->getMessage();
        }
        if (empty( $user_profile )) {
            $this->redirect( '/login/?err=1' );
        }

        $identifier = $user_profile->identifier;

        //TODO handle authenticating user
        if ($model->identifier_exists( $identifier )) {
            $model->login_user( $identifier );
            $this->redirect( '/welcome/' );
        } 
        else {
            //TODO handle registering user
            $register = $model->register_user(
                $identifier,
                $user_profile->email,
                $user_profile->firstName,
                $user_profile->lastName,
                $user_profile->photoURL
            );

            if ($register) {
                $model->login_user( $identifier );
                $this->redirect( '/welcome/' );
            }
        }
    }
);

// TODO Change route name back to something sane once we have authentication in.
$app->group('/abqrd8730', function() {
    $this->get('/', function($request, $response, $args) {
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