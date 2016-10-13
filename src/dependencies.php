<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler(
        $settings['path'],
        $settings['level']
    ));
    return $logger;
};

// Service factory for the ORM
$container['db'] = function ($container) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container['settings']['db']['huntbiddb']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
    return $capsule;
};

$container['model'] = function ($container) {
    $db = $container->get('db');
    
    //make Leads model
    $google_doc_id = '19Ya9gHRcS6dYFQX6aTJsbZmAfuNVpEB1lSG5a07_930';
    $csv_url = "https://docs.google.com/spreadsheets/d/{$google_doc_id}/export?format=csv&id={$google_doc_id}";
    $parser = new App\Utils\LeadCsvParser();
    $csvDownloader = new App\Utils\LeadCsvDownloader( $parser, $csv_url );
    $leads =  new App\Model\Leads($csvDownloader);
    
    return [
        'leads' => $leads,
        //'users' => \App\Model\User::from('users'), //FIXME this doesn't work
    ];
};

//hybridauth
$container['hybridauth'] = function($container) {
    Hybrid_Endpoint::process();
    return new Hybrid_Auth($container->get('settings')['hybridauth']);
};

//HybridAuth socialauth implementation
$container['socialauth'] = function($container) {
    return new \App\SocialLogin( $container->get('model')['users'] );
};

// google client
/*
possibly not necessary
$container['google-client'] = function($container) {
    $container->get('settings')['oAuthCreds'];
    $client = new Google_Client();
    $client->setApplicationName($googleSettings['application_name']);
    $client->setClientId($googleSettings['client_id']);
    $client->setClientSecret($googleSettings['client_secret']);
    foreach($googleSettings['scopes'] AS $nextScope){
        $client->addScope($nextScope);
    }
    $client->setRedirectUri($callbackRoute); //TODO determine what this should be
    return $client;
}
*/

// slim-oauth
$container[SlimApi\OAuth\OAuthFactory::class] = function($container) {
    return new SlimApi\OAuth\OAuthFactory($container->get('settings')['oAuthCreds']);
};

$container[SlimApi\OAuth\UserServiceInterface::class] = function($container) {
    //user service should implement SlimApi\OAuth\UserServiceInterface
    //user model should have a token variable to hold the random token sent to the client
    //$usermodel = App\Model\User; // $container->get('db')->table('google_tokens');
    return new App\Service\Google\UserService(new App\Model\GoogleToken());
};

$container[SlimApi\OAuth\OAuthMiddleware::class] = function($container) {
    return new SlimApi\OAuth\OAuthMiddleware(
        $container->get(SlimApi\OAuth\OAuthFactory::class),
        $container->get(SlimApi\OAuth\UserServiceInterface::class)
    );
};
