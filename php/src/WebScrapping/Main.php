<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;

// Ignore os avisos do DOMDocument
libxml_use_internal_errors(true);

class Main {
    
  public static function run(): void {
    $dom = new \DOMDocument('1.0', 'utf-8');
    $dom->loadHTMLFile(__DIR__ . '/../../assets/origin.html');

    $data = (new Scrapper())->scrap($dom);

    $paper = new Paper(1, 'Sample Paper', 'Poster', [new Person('John Doe', 'University A')]);

    print_r($data);
  }
}
