<?php

namespace App\Model;

class Leads {
    private /* array */ $leads = array();

    public function __construct(\App\Utils\LeadCsvDownloader $leadDownloader) {
        foreach($leadDownloader->downloadCsv() as $nextData) {
            $this->addLead(new \App\Data\Lead($nextData));
        }
    }

    public function getLeads(LeadsFetchParams $leadsFetchParams = null) {
        if (!isset($leadsFetchParams)) {
            return $this->leads;
        }

        $outLeads = array();
        $aggregate = new \App\Data\Aggregate();
        foreach($this->leads as $lead) {
            $leadDate = \DateTime::createFromFormat($leadsFetchParams->getFormat(), $lead->getDate());
            if ($leadDate >= $leadsFetchParams->getStartDate() && $leadDate <= $leadsFetchParams->getEndDate()) {
                $outLeads[] = $lead;
                $aggregate->addLead($lead);
            }
        }
        return [ 'leads' => $outLeads, 'aggregate' => $aggregate ];
    }

    private function addLead(\App\Data\Lead $newLead) {
        $this->leads[] = $newLead;
    }
}