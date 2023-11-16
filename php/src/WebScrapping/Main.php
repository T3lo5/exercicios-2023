<?php

namespace Chuva\Php\WebScrapping;


libxml_use_internal_errors(true);

class Main {
    
 
  public static function run(): void {
    $dom = new \DOMDocument('1.0', 'utf-8');
    $dom->loadHTMLFile(__DIR__ . '/../../assets/origin.html');

    $scrapper = new Scrapper();
    $data = $scrapper->scrap($dom);
    
    $scrapper->writeToSpreadsheet($data);
  }
}
