<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Scrapper {

  public function scrap(\DOMDocument $dom): array {
    $data = [];

    $nodes = $dom->getElementsByTagName('div');

    foreach ($nodes as $node) {
        print_r($node);

        $id = $node->getAttribute('data-id');
        
        $titleElement = $node->getElementsByTagName('h2')->item(0);
        $title = $titleElement ? $titleElement->textContent : 'N/A';
        
        $type = $node->getAttribute('data-type');
        
        $authors = [];
        $authorsNodes = $node->getElementsByTagName('span');

        foreach ($authorsNodes as $authorNode) {
            // Debugging output to check each author node structure
            print_r($authorNode);

            $nameElement = $authorNode->getElementsByTagName('span')->item(0);
            $name = $nameElement ? $nameElement->textContent : 'N/A';

            $institutionElement = $authorNode->getElementsByTagName('span')->item(1);
            $institution = $institutionElement ? $institutionElement->textContent : 'N/A';

            $authors[] = new Person($name, $institution);
        }

        $data[] = new Paper($id, $title, $type, $authors);
    }

    return $data;
  }

  public function writeToSpreadsheet(array $data, $filename = 'output.xlsx') {
    $filePath = __DIR__ . '/../../output/' . $filename; // Caminho absoluto do arquivo

    // Cria uma nova planilha
    $spreadsheet = new Spreadsheet();
    
    // Adiciona dados à planilha
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Título');
    $sheet->setCellValue('C1', 'Tipo');
    $sheet->setCellValue('D1', 'Autores');

    for ($i = 0; $i < count($data); $i++) {
        $sheet->setCellValue('A' . ($i + 2), $data[$i]->id);
        $sheet->setCellValue('B' . ($i + 2), $data[$i]->title);
        $sheet->setCellValue('C' . ($i + 2), $data[$i]->type);

        $authors = implode(', ', array_map(function (Person $person) {
            return $person->name;
        }, $data[$i]->authors));

        $sheet->setCellValue('D' . ($i + 2), $authors);
    }

    try {
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);
        echo 'Arquivo criado com sucesso em: ' . $filePath;
    } catch (\Throwable $e) {
        echo 'Erro ao salvar o arquivo: ' . $e->getMessage();
    }
  }
}
