<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use App\Enums\TaskPriorty;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['title', 'description', 'priority', 'due_date', 'status', 'assigned_to','user_id'];

    protected $casts = [
        'status' => TaskStatus::class,
        'priority' => TaskPriorty::class,
    ];
    protected $appends = ['due_date'];
    protected $primaryKey = 'task_id';
    public $incrementing = true;
    const CREATED_AT = 'created_on';
    const UPDATED_AT = 'updated_on';

    protected $per_page = 5;

    public function User()
    {
        return $this->belongsTo(User::class);
    }
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    public function getDueDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }
    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date']= Carbon::createFromFormat('d-m-Y H:i', $value);
    }
    
}
