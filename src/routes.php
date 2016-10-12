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

function getLeadsModel() {
    $google_doc_id = '19Ya9gHRcS6dYFQX6aTJsbZmAfuNVpEB1lSG5a07_930';
    $csv_url = "https://docs.google.com/spreadsheets/d/{$google_doc_id}/export?format=csv&id={$google_doc_id}";
    $parser = new App\Utils\LeadCsvParser();
    $csvDownloader = new App\Utils\LeadCsvDownloader( $parser, $csv_url );
    return new App\Model\Leads($csvDownloader);
}

// TODO Change route name back to something sane once we have authentication in.
$app->group('/abqrd8730', function() {
    $this->get('/all/', function($request, $response, $args) {
        $leads = array();
        $leadsModel = getLeadsModel();
        $leads = $leadsModel->getLeads();
        return $this->renderer->render($response, 'leads.phtml', ['data' => $leads ]);
    });

    $this->get('/', function($request, $response, $args) {
        $leadsModel = getLeadsModel();
        $endDate = new DateTime();
        $endDate->setTime(0, 0, 0);
        $startDate = clone($endDate);
        $startDate->sub(new DateInterval('P7D'));
        $leadsFetchParams = new App\Model\LeadsFetchParams( $startDate, $endDate, 'm/d/Y' );
        $leads = $leadsModel->getLeads($leadsFetchParams);
        return $this->renderer->render($response, 'leads.phtml', ['data' => $leads ]);
    });

    $this->get('/{year}/{month}/{day}/', function($request, $response, $args) {
        $leadsModel = getLeadsModel();
        $endDate = DateTime::CreateFromFormat('Y/m/d', $args['year'] . '/' . $args['month'] . '/' . $args['day']);
        $startDate = clone($endDate);
        $startDate->sub(new DateInterval('P7D'));
        $leadsFetchParams = new App\Model\LeadsFetchParams( $startDate, $endDate, 'm/d/Y' );
        $leads = $leadsModel->getLeads($leadsFetchParams);
        return $this->renderer->render($response, 'leads.phtml', [ 'data' => $leads ]);
    });

    $this->get('/{start_year}/{start_month}/{start_day}/{end_year}/{end_month}/{end_day}/', function($request, $response, $args) {
        $leadsModel = getLeadsModel();
        $endDate = DateTime::CreateFromFormat('Y/m/d', $args['end_year'] . '/' . $args['end_month'] . '/' . $args['end_day']);
        $startDate = DateTime::CreateFromFormat('Y/m/d', $args['start_year'] . '/' . $args['start_month'] . '/' . $args['start_day']);
        $leadsFetchParams = new App\Model\LeadsFetchParams( $startDate, $endDate, 'm/d/Y' );
        $leads = $leadsModel->getLeads($leadsFetchParams);
        return $this->renderer->render($response, 'leads.phtml', [ 'data' => $leads ]);
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