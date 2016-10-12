<?php

namespace App\Model;

class LeadModel {
    private /* array */ $leads = array();

    public function __construct(\App\Util\LeadCsvDownloader $leadDownloader) {
        foreach($leadDownloader->downloadCsv() as $nextData) {
            $this->leads[] =  new \App\Data\Lead($nextData);
        }
    }

    public function getLeads() {
        return $this->leads;
    }
}