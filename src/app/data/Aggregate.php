<?php

namespace App\Data;

class Aggregate implements \JsonSerializable {
    private $regions = [ 'Buffalo',
                         'Rochester',
                         'Syracuse',
                         'Capital',
                         'Arizona',
                         'Total',
    ];

    private $sources = [ 'Huntrealestate.com (IDX)',
                         'ERA.com (Zap)',
                         'Homes.com',
                         'Homefinder.com',
                         'Hotpads.com',
                         'Trulia.com',
                         'Zillow.com',
                         'LeadRouter Total',
                         'DTA - huntrealestate.com',
                         'Voicepad',
                         'VoicepadUnique',
                         'Combined',
    ];

    private $leadRouterTotalSources = [ 'ERA.com (Zap)',
                                        'Homes.com',
                                        'Homefinder.com',
                                        'Hotpads.com',
                                        'Trulia.com',
                                        'Zillow.com',
    ];

    private $sourcesMap = [ 'LR - HUNT Website' => 'Huntrealestate.com (IDX)',
                            'LR - ZAP ERA' => 'ERA.com (Zap)',
                            'LR - Homes.com' => 'Homes.com',
                            'LR - Homefinder.com' => 'Homefinder.com',
                            'LR - Zillow.com' => 'Zillow.com',
                            'Agent Achieve' => 'DTA - huntrealestate.com',
                            'Voicepad' => 'Voicepad',
    ];

    private $regionsMap = [ 'Wheatfield' => 'Rochester',
                            'Greece' => 'Rochester',
                            'KenTon' => 'Buffalo',
                            'Camillus' => 'Syracuse',
                            'Buffalo-Metropolitan' => 'Buffalo',
                            'Lockport' => 'Buffalo',
                            'Dewitt' => 'Syracuse',
                            'Amherst' => 'Buffalo',
                            'Lancaster' => 'Buffalo',
                            'Hamburg' => 'Buffalo',
                            'Perinton' => 'Rochester',
                            'Clinton' => 'Syracuse',
                            'Brighton/Pittsford' => 'Rochester',
                            'Glens Falls' => 'Capital',
                            'Old Forge' => 'Syracuse',
                            'Slingerlands' => 'Capital',
                            'Rensselaer' => 'Capital',
                            'Saratoga Springs' => 'Capital',
                            'West Seneca' => 'Buffalo',
                            'Williamsville Village' => 'Buffalo',
                            'Scottsdale' => 'Arizona',
                            'Loudonville' => 'Capital',
                            'Lewiston' => 'Buffalo',
                            'Oneida' => 'Syracuse',
                            'Cicero' => 'Syracuse',
                            'Williamsville/Clarence' => 'Buffalo',
                            'Liverpool' => 'Syracuse',
                            'Cazenovia' => 'Syracuse',
                            'Watertown' => 'Syracuse',
                            'Canandaigua' => 'Rochester',
                            'Manlius' => 'Syracuse',
                            'Orchard Park' => 'Buffalo',
                            'Chittenango' => 'Syracuse',
                            'Hornell' => 'Rochester',
                            'Alfred' => 'Rochester',
                            'Irondequoit' => 'Rochester',
                            'East Aurora' => 'Buffalo',
                            'Akron' => 'Buffalo',
                            'Tempe' => 'Arizona',
                            'HUNT Real Estate ERA - GSAR Camillus' => 'Syracuse',
                            'HUNT Real Estate ERA - BNAR Williamsville/Clarence' => 'Buffalo',
                            'HUNT Real Estate ERA - BNAR West Seneca' => 'Buffalo',
                            'Corporate' => 'Buffalo'
    ];

    private /* array */ $totals = array();

    private /* array */ $seenEmailPhones = array();

    public function __construct() {
        foreach($this->sources as $source) {
            foreach($this->regions as $region) {
                $this->totals[$source][$region] = 0;
                $this->combinedTotal[$region] = 0;
            }
        }

    }

    public function addLead(\App\Data\Lead $lead) {
        if (array_key_exists($lead->getSource(), $this->sourcesMap)
            && array_key_exists($lead->getOffice(), $this->regionsMap)) {
            $source = $this->sourcesMap[$lead->getSource()];
            $region = $this->regionsMap[$lead->getOffice()];
            $this->totals[$source][$region]++;
            $this->totals[$source]['Total']++;

            if (in_array($source, $this->leadRouterTotalSources)) {
                $this->totals['LeadRouter Total'][$region]++;
                $this->totals['LeadRouter Total']['Total']++;
            }

            if ($source == 'Voicepad') {
                $emailPhone = $lead->getEmailPhone();
                if (!in_array($emailPhone, $this->seenEmailPhones)) {
                    $this->seenEmailPhones[] = $emailPhone;
                    $this->totals['VoicepadUnique'][$region]++;
                    $this->totals['VoicepadUnique']['Total']++;
                    $this->totals['Combined'][$region]++;
                    $this->totals['Combined']['Total']++;
                }
            } else {
                $this->totals['Combined'][$region]++;
                $this->totals['Combined']['Total']++;
            }
        }
    }

    public function getTotal($source, $region) {
        return $this->totals[$source][$region];
    }

    public function getPercent(/* string */ $source, /* string */ $region) {
        $denominator = 0;
        if ($source == 'Combined') {
            $denominator = $this->totals['Combined']['Total'];
        } else {
            $denominator = $this->totals['Combined'][$region];
        }

        if ($denominator == 0) {
            return 0;
        } else {
            return round($this->totals[$source][$region] / $denominator * 100);
        }
    }

    public function jsonSerialize() {
        return $this->totals;
    }
}