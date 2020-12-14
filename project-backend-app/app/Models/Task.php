<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public const TO_DO = 1;
    public const IN_PROGRESS = 2;
    public const DONE = 3;

    protected $fillable = [
        'title',
        'description',
        'status',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
