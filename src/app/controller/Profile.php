<?php namespace App\Controller;

class Profile extends BaseController {

    function profile($request, $response, $args) {
        return BaseController::renderWithLayout(
            $this->ci->get('renderer'),
            $response,
            'dashboard.phtml',
            'layouts/dashboard-layout.phtml',
            ['name' => 'Unnamed User' ]
        );
    }
}
 







