<?php
// Prueba rápida de PhpSpreadsheet: crea un XLSX, lo lee y muestra contenido.
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Ruta de prueba
$dir = __DIR__ . '/../storage/tmp';
if (! is_dir($dir)) {
    mkdir($dir, 0777, true);
}
$file = $dir . '/phpspreadsheet_test.xlsx';

// Crear spreadsheet y escribir datos
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'name');
$sheet->setCellValue('B1', 'email');
$sheet->setCellValue('A2', 'Test User');
$sheet->setCellValue('B2', 'test@example.com');

$writer = new Xlsx($spreadsheet);
$writer->save($file);

echo "WROTE: $file\n";

// Leer el archivo creado
$reader = IOFactory::createReaderForFile($file);
$reader->setReadDataOnly(true);
$doc = $reader->load($file);
$sheet = $doc->getActiveSheet();

$rows = [];
$highestRow = $sheet->getHighestDataRow();
$highestColumn = $sheet->getHighestDataColumn();
$highestColumnIndex = PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

$headers = [];
for ($col = 1; $col <= $highestColumnIndex; $col++) {
    $colLetter = PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
    $headers[] = (string) trim((string) $sheet->getCell($colLetter . '1')->getValue());
}
for ($r = 2; $r <= $highestRow; $r++) {
    $item = [];
    for ($c = 1; $c <= $highestColumnIndex; $c++) {
        $colLetter = PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
        $item[$headers[$c-1] ?? 'col_'.$c] = (string) $sheet->getCell($colLetter . $r)->getValue();
    }
    $rows[] = $item;
}

echo json_encode(['read' => $rows], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
