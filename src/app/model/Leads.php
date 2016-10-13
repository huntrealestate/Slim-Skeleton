<?php

namespace App\Model;

class Leads {
    private /* array */ $leads = array();

    public function __construct(\App\Utils\LeadCsvDownloader $leadDownloader) {
        foreach($leadDownloader->downloadCsv() as $nextData) {
            $this->leads[] =  new \App\Data\Lead($nextData);
        }
    }

    public function getLeads(LeadsFetchParams $leadsFetchParams = null) {
        if (!isset($leadsFetchParams)) {
            return $this->leads;
        }

        $outLeads = array();
        foreach($this->leads as $lead) {
            $leadDate = \DateTime::createFromFormat($leadsFetchParams->getFormat(), $lead->getDate());
            if ($leadDate >= $leadsFetchParams->getStartDate() && $leadDate <= $leadsFetchParams->getEndDate()) {
                $outLeads[] = $lead;
            }
        }
        return $outLeads;
    }
}