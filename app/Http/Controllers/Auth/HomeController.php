<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\ExcelHandler;
use App\Models\ExcelImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    public function index()
    {
        return view('auth.home');
    }

    public function getImports()
    {
        $files = ExcelImport::orderBy('created_at', 'desc')->where('user_id', auth()->id())->get();
        return view('auth.components.excelItem', compact('files'));
    }

    public function deleteFile(Request $req, $id)
    {
        $file = ExcelImport::where('user_id', auth()->id())->where('id', $req->id)->first();

        if (!isset($file)) {
            abort(404, 'Arquivo não encontrado na base de dados');
        }

        $file->delete();
        File::delete($file->path);

        return $this->getImports();
    }

    public function importExcel(Request $req)
    {
        $req->validate([
            'column' => 'required',
            'excel' => 'required|mimes:xlsx'
        ], [
            'column.required' => 'A coluna é obrigatória',
            'excel.required' => 'O arquivo é obrigatório',
            'excel.required' => 'O arquivo deve ser do tipo .xlsx',
        ]);

        $file = $req->file('excel');

        $fileNameWithExtension = $file->getClientOriginalName();
        $filename = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);

        $filePath = $file->store('temp', 'local');
        ExcelHandler::dispatch($filePath, $req->column, $filename);

        return response()->json(['message' => 'Arquivo enviado com sucesso']);
    }
}
