<?php
namespace App\Model;
use \App\Utils\LeadCsvParser;
use \App\Utils\LeadCsvDownloader;
use \App\Utils\GoogleSheetCsvRetriever;
use \App\Data\Lead;

class Leads {
    private /* array */ $leads = [];
    private $initialized = false;
    private $csvDownloader; 
    private /* \Slim\Container */ $c;
    
    public function __construct(\Slim\Container $c) {
        $this->c = $c;
    }

    public function getLeads(LeadsFetchParams $leadsFetchParams = null) {
        $this->initLeads();
        $aggregate = new \App\Data\Aggregate();
        if (!isset($leadsFetchParams)) {
            foreach($this->leads as $lead) {
                $aggregate->addLead($lead);
            }
            return [ 'leads' => $this->leads, 'aggregate' => $aggregate ];
        }

        $outLeads = array();
        foreach($this->leads as $lead) {
            $leadDate = \DateTime::createFromFormat($leadsFetchParams->getFormat(), $lead->getDate());
            if ($leadDate >= $leadsFetchParams->getStartDate() && $leadDate <= $leadsFetchParams->getEndDate()) {
                $outLeads[] = $lead;
                $aggregate->addLead($lead);
            }
        }
        return [ 'leads' => $outLeads, 'aggregate' => $aggregate ];
    }

    public function addLead(Lead $newLead) {
        $this->initLeads();
        $this->leads[] = $newLead;
    }
    
    private function bulkAddLeads($leadsRawData){
        foreach($leadsRawData as $nextData) {
            $this->addLead(new Lead($nextData));
        }
    }
    
    private function initLeads(){
        if( $this->initialized ){
            return;
        }
        $this->initialized = true;
        //array not yet created, make it now
        $this->leadsArr = [];
        $this->csvDownloader = new GoogleSheetCsvRetriever( 
            new LeadCsvParser(), $this->c->get('google_service_drive'), $this->c );
        $google_doc_ids = $this->c->get('settings')['model']['leads']['google_doc_ids'];
        foreach($google_doc_ids as $google_doc_id){
            $this->bulkAddLeads( $this->csvDownloader->downloadCsv($google_doc_id) );
        }
    }
}
