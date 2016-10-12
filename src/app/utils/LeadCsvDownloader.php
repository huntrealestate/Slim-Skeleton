<?php 

namespace App\Utils;

///Class to retrieve google sheet CSV lead data
class LeadCsvDownloader {
    
    private /* string */ $url;
    
    //LeadCsvParser
    private $parser;
    
    ///Constructor which takes a Util\LeadCsvParser
    public function __construct(LeadCsvParser $parser, /*string*/ $url) {
       $this->parser = $parser;
       $this->url = $url;
   }
   
    ///Method to retrieve google sheet CSV
    public function downloadCSV(){
        // TODO Temporarily reading from static data. Change back to live data.
        return $this->parser->parse( file_get_contents( __DIR__ . '/static_data.csv') );
        // ///convert to 2d array
        // return $this->parser->parse( file_get_contents($this->url) );
    }
    
}

