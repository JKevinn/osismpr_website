<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MeetingSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'uuid';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'meeting_title',
        'time_start',
        'time_end',
        'date',
        'location',
        'description',
        'status',
        'created_by',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'uuid' => 'string',
        'status' => 'string',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'meeting_uuid', 'uuid');
    }
}
