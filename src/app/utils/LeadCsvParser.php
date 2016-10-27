<?php

namespace App\Utils;

///Class to parse lead data CSV 2d array
class LeadCsvParser implements ICsvParser{
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
