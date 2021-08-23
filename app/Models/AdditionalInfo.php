<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalInfo extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'info'];
    protected $visible = ['type', 'info'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
