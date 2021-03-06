<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'height', 'parent_id', 'job_title'];
    protected $hidden = ['created_at', 'updated_at'];

    public function additional_info()
    {
        return $this->hasOne(AdditionalInfo::class, 'employee_id', 'id');
    }

    public function get_parent()
    {
        if (is_null($this->parent_id)) {
            return null;
        }

        return Employee::find($this->parent_id);
    }

    public function calculate_height()
    {
        $count = 0;
        $current = $this->get_parent();
        while (!is_null($current)) {
            $count++;
            $current = $current->get_parent();
        }

        return $count;
    }

    //TODO: make this private and run it on every insert
    public function set_height()
    {
        $this->height = $this->calculate_height();
    }

    public function is_root()
    {
        // We need to make sure that we're working with the actual parent_id stored in the db
        $existing = Employee::find($this->id);
        
        // Maybe worthy of an exception?
        if (is_null($existing)) {
            return false;
        }

        return is_null($existing->parent_id);
    }

    public function validate() 
    {
        if (is_null($this->get_parent())) {
            return array(false, 'Parent not found');
        }

        return array(true, '');
    }
}
