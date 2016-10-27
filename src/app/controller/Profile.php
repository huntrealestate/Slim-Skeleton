<?php namespace App\Controller;

class Profile extends BaseController {


    function profile($request, $response, $args) {
        $this->ci->get('model');
        $session_information=\App\Model\User::getCurrentSessionUser();
        $data=[
            //'user'=>$session_information->toArray(),
            'firstName' => $session_information['first_name'],
            'lastName' => $session_information['last_name'],
            'shortName' => 'HuntBID',
            'pageTitle' => 'Dashboard',
            'logoMini' => 'BIDS',
            'logoLg1' => 'HUNT ',
            'logoLg2' => 'BID System ',
            //TODO:'avatarImg' =>$session_information['avatar_url'],
            //TODO:'mesTime' => 'time',
            'genNum' => '1',
            'mesTitle' => 'Message Title',
            'genTex' => 'Text',
            'noteTex' => 'Notification Text',
            'taskTex' => 'Task Name',
            'userName' => 'User Name',
            'userDate' => '10-26-2016',
            'userStat' => 'Online',
            'subTitle' => 'This is your user profile page',
            'sideHead' => 'Sidebar Header',
            'compName' => 'Hunt Real Estate',
            'actTitle' => 'Activity Title',
            'actDesc' => 'Activity Description',
            'setTitle' => 'Setting',
            'setDesc' => 'Setting Description'
        ];
        return BaseController::renderWithLayout(
            $this->ci->get('renderer'),
            $response,
            'dashboard.phtml',
            'layouts/dashboard-layout2.phtml',
            $data  
        );
    }
}
