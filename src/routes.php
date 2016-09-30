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