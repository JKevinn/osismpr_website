<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    // Specify the primary key type
    protected $keyType = 'char';

    // Specify the primary key
    protected $primaryKey = 'uuid';

    // Specify the incrementing to false since it's not an integer
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'meeting_uuid',
        'user_uuid',
        'arrival_time',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'uuid' => 'string',
        'meeting_uuid' => 'string',
        'user_uuid' => 'string',
        'status' => 'string',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array<string>
     */
    protected $dates = ['deleted_at'];

    public function meetingSchedule()
    {
        return $this->belongsTo(MeetingSchedule::class, 'meeting_uuid', 'uuid');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_uuid', 'uuid');
    }
}
