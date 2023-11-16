<?php

namespace Chuva\Php\WebScrapping;

use Chuva\Php\WebScrapping\Entity\Paper;
use Chuva\Php\WebScrapping\Entity\Person;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Does the scrapping of a webpage.
 */
class Scrapper {

  /**
   * Loads paper information from the HTML and returns the array with the data.
   */
  public function scrap(\DOMDocument $dom): array {
    return [
      new Paper(
        123,
        'The Nobel Prize in Physiology or Medicine 2023',
        'Nobel Prize',
        [
          new Person('Katalin Karikó', 'Szeged University'),
          new Person('Drew Weissman', 'University of Pennsylvania'),
        ]
      ),
    ];
  }

  /**
   * Writes the paper data to a spreadsheet.
   */
  public function writeToSpreadsheet(array $data, $filename = 'output.xlsx') {
    $filePath = __DIR__ . '/../../output/' . $filename; // Caminho absoluto do arquivo

    // Cria uma nova planilha
    $spreadsheet = new Spreadsheet();
    
    // Adiciona dados à planilha
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Título');
    $sheet->setCellValue('C1', 'Autores');

    for ($i = 0; $i < count($data); $i++) {
        // Adicione esta linha para verificar os dados em cada iteração
        print_r($data[$i]); 

        $sheet->setCellValue('A' . ($i + 2), $data[$i]->id);
        $sheet->setCellValue('B' . ($i + 2), $data[$i]->title);

        $authors = implode(', ', array_map(function (Person $person) {
            return $person->name;
        }, $data[$i]->authors));

        $sheet->setCellValue('C' . ($i + 2), $authors);
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
