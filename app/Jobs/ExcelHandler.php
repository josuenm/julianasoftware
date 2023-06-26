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
        $excelImport = new ExcelImport([
            'user_id' => $this->userId,
            'filename' => $this->filename
        ]);
        $excelImport->processing();
        $excelImport->save();

        $spreadsheet = IOFactory::load($this->excelPath);

        $firstWorksheet = $spreadsheet->getActiveSheet();

        $rows = $firstWorksheet->toArray();
        $headers = $rows[0];
        $indexColumn = array_search($this->column, $headers);
        array_shift($rows);

        if (!$indexColumn) {
            $excelImport->fail('A coluna ' . $this->column . ' não existe na planilha');
            $excelImport->save();
            throw new \Exception('A coluna ' . $this->column . ' não existe na planilha');
        }

        $worksheets = $spreadsheet->getAllSheets();
        foreach ($worksheets as $worksheet) {
            if ($worksheet === $firstWorksheet) continue;

            $sheet = $worksheet->toArray();

            for ($i = 0; $i < count($sheet); $i++) {
                if ($this->isAllNull($sheet[$i])) {
                    array_shift($sheet);
                }
                break;
            }

            array_push($rows, $worksheet->toArray());
        }

        $newSheet = new Spreadsheet();

        $newWorkSheet = $newSheet->getActiveSheet();
        $newWorkSheet->fromArray(['telefone'], null, 'A1');
        $newWorkSheet->fromArray(['12992228437', '12992228437', '12992228437', '12992228437'], null, 'A2');

        $writer = new Xlsx($newSheet);

        $path = storage_path('app/public/' . $this->filename);

        $writer->save($path);

        File::delete($this->excelPath);

        $excelImport->done($path);
        $excelImport->save();
    }

    public function isAllNull($array)
    {
        return empty(array_filter($array, fn ($item) => !is_null($item)));
    }
}
