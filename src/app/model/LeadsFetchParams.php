<?php

namespace App\Model;

class LeadsFetchParams {
    private /* DateTime */ $start_date = null;
    private /* DateTime */ $end_date = null;
    private /* string */ $date_format = null;

    public function __construct(\DateTime $start_date = null, \DateTime $end_date = null, $date_format = null) {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->date_format = $date_format;
    }

    public function getStartDate() { return $this->start_date; }

    public function getEndDate() { return $this->end_date; }

    public function getFormat() { return $this->date_format; }
}
