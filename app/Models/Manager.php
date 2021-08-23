<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manager extends Employee
{
    use HasFactory;

    protected $table = 'employees';

    public function validate()
    {
        $parent_validation = parent::validate();
        if (!$parent_validation[0]) {
            return $parent_validation;
        }

        if ($this->additional_info->type != 'department') {
            return array(false, 'No department provided');
        }

        return array(true, '');
    }

}
