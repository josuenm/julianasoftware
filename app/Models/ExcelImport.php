<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filename'
    ];

    public function status()
    {
        return Status::find($this->status_id);
    }

    public function processing()
    {
        $this->status_id = 1;
    }

    public function fail(string $error)
    {
        $this->status_id = 2;
        $this->message = $error;
    }

    public function done(string $path)
    {
        $this->path = $path;
        $this->status_id = 3;
    }
}
