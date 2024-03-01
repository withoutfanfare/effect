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

    /**
     * @param  string  $jobId
     * @param  string  $parsedBlocks
     * @param  string  $status
     *
     * @return bool
     */
    public static function saveBlocks(string $jobId, string $parsedBlocks, string $status): bool
    {
        $extraction = new self();

        $extraction->job_id = $jobId;
        $extraction->text = $parsedBlocks;
        $extraction->status = $status;

        if(empty($parsedBlocks) || $status !== 'succeeded') {
            $extraction->text = 'Something went wrong. Please try again.';
        }

        return $extraction->save();
    }
}
