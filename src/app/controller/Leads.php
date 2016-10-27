<?php namespace App\Controller;

class Leads extends BaseController {

    function current($request, $response, $args) {
        $endDate = new \DateTime();
        $endDate->setTime(0, 0, 0);
        $startDate = $this->createWeekStartDate($endDate);
        $this->render( $response, $this->getLeads( $this->createParams($startDate, $endDate)));
    }

    function all($request, $response, $args) {
        $this->render( $response, $this->getLeads());
    }

    function past($request, $response, $args) {
        $endDate = $this->createDate($args['year'], $args['month'], $args['day']);
        $startDate = $this->createWeekStartDate($endDate);
        $this->render( $response, $this->getLeads( $this->createParams($startDate, $endDate)));
    }

    function custom($request, $response, $args) {
        $endDate = $this->createDate($args['end_year'], $args['end_month'], $args['end_day']);
        $startDate = $this->createDate($args['start_year'], $args['start_month'], $args['start_day']);
        $this->render( $response, $this->getLeads( $this->createParams($startDate, $endDate)));
    }
    
    private function createDate($year, $month, $day){
        $date = \DateTime::CreateFromFormat('Y/m/d', "{$year}/{$month}/{$day}");
        $date->setTime(0, 0, 0);
        return $date;
    }
    
    private function createWeekStartDate($endDate){
        $startDate = clone($endDate);
        $startDate->sub(new \DateInterval('P7D'));
        return $startDate;
    }
    
    private function createParams(\DateTime $startDate, \DateTime$endDate) {
        return new \App\Model\LeadsFetchParams( $startDate, $endDate, 'm/d/Y' );
    }
    
    private function getLeads($params = null){
        return $this->ci->get('model')['leads']->getLeads($params);
    }
    
    private function render($response, $leads) {
        return \App\Controller\BaseController::renderWithLayout(
            $this->ci->get('renderer'),
            $response,
            'leads.phtml',
            'layouts/dashboard-layout.phtml',
            ['data' => $leads ]
        );
    }
}
 