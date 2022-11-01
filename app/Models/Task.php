<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'deadline',
        'completed',
    ];

    public function scopeSearchDeadline($query, $data)
    {
        if($data == 'yet') {
            return $query->where('deadline', '>=', now());
        }
        if($data == 'done') {
            return $query->where('deadline', '<=', now());
        }
    }

    public function scopeSearchStatus($query, $data)
    {
        if($data == 'completed') {
            return $query->where('completed', true);
        }
        if($data == 'incompleted') {
            return $query->where('completed', false);
        }
    }

    public function scopeSearchKeyword($query, $keyword)
    {
        return $query->where('title', 'like', '%'.$keyword.'%')
                    ->orWhere('description', 'like', '%'.$keyword.'%');
    }

    public function scopeBetweenDeadline($query, $start, $end)
    {
        if($start != '' && $end != ''){
            return $query->whereBetween('deadline', [$start, $end]);
        }else if($start != '') {
            return $query->where('deadline', '>=', $start);
        }else if($end != ''){
            return $query->where('deadline', '<=', $end);
        }
    }
}
