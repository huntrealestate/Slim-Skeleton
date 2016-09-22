<?php
namespace App\Service\Google;

use SlimApi\OAuth\UserServiceInterface;
use OAuth\Common\Service\ServiceInterface;

class UserService implements UserServiceInterface {

    public function __construct($userModel)
    {
        $this->userModel = $userModel;
    }

    public function createUser(ServiceInterface $service)
    {
        // request the user information from github
        // could go further with this and check org/team membership
        $user = json_decode($service->request('user'), true);

        // try to find user by the oauth server's user id, 
        // best way since oauth token might have been invalidated
        $models = $this->userModel->byRemoteId($user['id'])->get(); 
        $model  = $models->first();

        if (!$model) {
            // create and save a new user
            $model = new $this->userModel([
                'remote_id'   => $user['id']
            ]);
        }
        $model->oauth_token = $service->getStorage()->retrieveAccessToken('GitHub')->getAccessToken();
        $model->token       = App\Utils\TokenGenerator::getToken();
        $model->save();
        return $model;
    }

    public function findOrNew($authToken)
    {
        // retrieve the user by the authToken provided
        // this could also be from some fast access redis db
        $users = $this->userModel->byToken($authToken)->get();
        $user = $users->first();
        // or return a blank entry if it doesn't exist
        return ($user ?: new $this->userModel);
    }
}