<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Filterable;

class Project extends Model
{
    use HasFactory, Filterable;

    protected $fillable = [
        'name',
        'status',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    public function attributeValues()
    {
        return $this->morphMany(AttributeValue::class, 'entity');
    }

    // Define additional regular attributes that can be filtered
    protected function getRegularAttributes()
    {
        return ['name', 'status'];
    }

    //For Projects: /api/projects?filters[name]=ProjectA&filters[status]=active
}
