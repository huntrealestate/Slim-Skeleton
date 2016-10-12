<?php 

namespace App\Util;

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

///Class to parse lead data CSV 2d array
class LeadCsvParser {
    ///Method to parse a CSV into a lead model
    public function parse(/*string*/ $leadCsv){
        $lines = explode("\n", $leadCsv);
        $simpleArray = str_getcsv($leadCsv);
        $headers = str_getcsv(array_shift($lines),",", "\"", "\"");
        $leadData = array();
        foreach($lines AS $leadDatum){
            $leadData[] = $this->extractLeadData(
                str_getcsv($leadDatum,",", "\"", "\""),
                $headers
            );
        }
        return $leadData; //return the header indexed data
    }
    
    private function extractLeadData(array $simpleArray, array $headerNames){
        $dataSize = min(count($simpleArray), count($headerNames));
        $complexData = array();
        //insert each of the simple data items as a header named index data item
        for($idx = 0; $idx < $dataSize; ++$idx) {
            $complexData[$headerNames[$idx]] = $simpleArray[$idx];
        }
        return $complexData;
    }
}
