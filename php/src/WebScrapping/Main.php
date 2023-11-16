<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

/**
 * Runner for the Webscraping exercise.
 */

// Ignore os avisos do DOMDocument
libxml_use_internal_errors(true);

class Main {
    
  /**
   * Main runner, instantiates a Scrapper and runs.
   */
  public static function run(): void {
    $dom = new \DOMDocument('1.0', 'utf-8');
    $dom->loadHTMLFile(__DIR__ . '/../../assets/origin.html');

    $data = (new Scrapper())->scrap($dom);

    // You can now create instances of Paper and Person classes and use them.
    $paper = new Paper(1, 'Sample Paper', 'Poster', [new Person('John Doe', 'University A')]);
    
    // Write your logic to save the output file below.
    print_r($data);
  }
}
