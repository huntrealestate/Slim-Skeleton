<?php namespace App\Controller;

class AvgTotalListingsController extends BaseController {

    function current($request, $response, $args) {
        $endDate = $this->setDateStartTime( new \DateTime() );
        $startDate = $this->createWeekStartDate($endDate);
        $params = $this->createParams($startDate, $endDate);
        $data = $this->getData( $params );
        $response->withJson($data);
    }

    function all($request, $response, $args) {
        $data = $this->getData();
        $response->withJson($data);
    }

    function past($request, $response, $args) {
        $endDate = $this->createDate($args['year'], $args['month'], $args['day']);
        $startDate = $this->createWeekStartDate($endDate);        
        $data = $this->getData( $params );
        $response->withJson($data);
    }

    function custom($request, $response, $args) {
        $endDate = $this->createDate($args['end_year'], $args['end_month'], $args['end_day']);
        $startDate = $this->createDate($args['start_year'], $args['start_month'], $args['start_day']);
        $params = $this->createParams($startDate, $endDate);
        $data = $this->getData( $params );
        $response->withJson($data);
    }
    
    private function setDateStartTime( \DateTime $date ){
        $date->setTime(0, 0, 0);
        return $date;
    }
    
    private function createDate($year, $month, $day){
        $date = \DateTime::CreateFromFormat('Y/m/d', "{$year}/{$month}/{$day}");
        return $this->setDateStartTime($date);
        return $date;
    }
    
    private function createWeekStartDate($endDate){
        $startDate = clone($endDate);
        $startDate->sub(new \DateInterval('P7D'));
        return $startDate;
    }
    
    private function createParams(\DateTime $startDate, \DateTime $endDate) {
        return [
            'unix_start' => $startDate->getTimestamp(),
            'unix_end' => $endDate->getTimestamp(),
        ];
    }
    
    private function getData($params = null){
        //TODO retrieve this data from a proshow API call
        //for now we append the params to some static test data
        return $params + [
            'total_average_listings' => [ 
                'Buffalo' => 920,
                'Rochester' => 462,
                'Syracuse' => 838,
                'Capital' => 251,
                'Arizona' => 31,
                'Total' => 2502,
            ]
        ];
    }
}
