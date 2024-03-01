<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'text',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'job_id' => 'string',
        'text' => 'string',
        'status' => 'string',
    ];

    public static function saveBlocks($jobId, string $blocks)
    {
        $extraction = new self();

        ray('saveBlocks', $jobId, $blocks);

        $extraction->job_id = $jobId;
        $extraction->text = $blocks;
        $extraction->status = 'success';

        if(empty($blocks)) {
            $extraction->text = 'Something went wrong. Please try again.';
            $extraction->status = 'failed';
        }

        return $extraction->save();
    }
}
