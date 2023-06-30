<?php

namespace App\Jobs;

use App\Models\ExcelImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelHandler implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $excelPath;
    protected $column;
    protected $filename;
    protected $excelImport;

    /**
     * Create a new job instance.
     */
    public function __construct(string $excelPath, string $column, string $filename)
    {
        $this->excelPath = storage_path('app/public/' . $excelPath);
        $this->column = $column;
        $this->userId = auth()->id();
        $this->filename = $filename . '_' . uniqid() . '.xlsx';
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->excelImport = new ExcelImport([
                'user_id' => $this->userId,
                'filename' => $this->filename
            ]);
            $this->excelImport->processing();
            $this->excelImport->save();

            $spreadsheet = IOFactory::load($this->excelPath);

            $firstWorksheet = $spreadsheet->getActiveSheet();

            $rows = $firstWorksheet->toArray();
            $headers = $rows[0];
            $indexColumn = array_search($this->column, $headers);
            array_shift($rows);

            if ($indexColumn === false) {
                throw new \Exception('A coluna ' . $this->column . ' não existe na planilha');
            }

            $worksheets = $spreadsheet->getAllSheets();
            foreach ($worksheets as $worksheet) {
                if ($worksheet === $firstWorksheet) continue;
                array_push($rows, $worksheet->toArray());
            }

            $data = [];
            foreach ($rows as $row) {
                foreach ($row as $index) {
                    if (!empty($index) && $this->isTel(strval($index))) {
                        $data[] = $index;
                    }
                }
            }

            $dataFormatted = [];
            foreach ($data as $item) {
                $tel = $this->formatNumber($item);
                if ($this->alreadyExist($dataFormatted, $tel)) continue;
                if ($this->isSequence($tel)) continue;

                $dataFormatted[] = $tel;
            }

            $newSheet = new Spreadsheet();

            $newWorkSheet = $newSheet->getActiveSheet();
            $newWorkSheet = $newSheet->getActiveSheet()->setTitle('Telefones');
            $newWorkSheet->fromArray(['telefone'], null, 'A1');

            foreach ($dataFormatted as $index => $value) {
                $row = $index + 2;
                $newWorkSheet->setCellValue('A' . $row, $value);
            }

            $writer = new Xlsx($newSheet);

            $path = storage_path('app/public/' . $this->filename);

            $writer->save($path);

            File::delete($this->excelPath);

            $this->excelImport->done($path);
            $this->excelImport->save();
        } catch (\Exception $error) {
            $this->excelImport->fail($error->getMessage());
            $this->excelImport->save();
            File::delete($this->excelPath);
            throw new \Exception();
        }
    }

    private function isSequence($tel)
    {
        $digits = str_split($tel);
        $sequence = 0;

        for ($i = 0; $i < count($digits); $i++) {
            if ($i > 0 && $digits[$i - 1] === $digits[$i]) {
                $sequence++;
            } else {
                $sequence = 0;
            }

            if ($sequence >= 6) {
                return true;
            }
        }

        return false;
    }

    private function alreadyExist($telList, $tel)
    {
        return in_array($tel, $telList);
    }

    private function formatNumber($tel)
    {
        return preg_replace('/[^0-9]/', '', strval($tel));
    }

    private function isAllNull($list)
    {
        return empty(array_filter($list, fn ($item) => !is_null($item)));
    }

    private function isTel($tel)
    {
        $clearTel = preg_replace('/\D/', '', $tel);
        if (strlen($clearTel) <= 13) {
            return true;
        }

        return false;
    }
}
