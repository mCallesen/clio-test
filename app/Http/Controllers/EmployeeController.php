<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Developer;
use App\Models\Manager;
use App\Models\AdditionalInfo;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    private function _return_error_message($message)
    {
        return response()->json(
            [
                'status' => 'error',
                'message' => $message, 
            ]
        );
    }


    public function index()
    {
        return Employee::with('additional_info')->get();
    }

    public function add_node(Request $request) 
    {
        // TODO: Check if it's actually there
        $job_title = strtolower($request->input('job_title'));

        switch ($job_title) {
            case 'developer':
                $employee = new Developer($request->all());
                // TODO: Add check for existence of additonal_info
                $additional = new AdditionalInfo($request->input('additional_info'));
                $employee->additional_info = $additional;
                break;
            case 'manager':
                $employee = new Manager($request->all());
                $additional = new AdditionalInfo($request->input('additional_info'));
                $employee->additional_info = $additional;
                break;
            default:
                $employee = new Employee($request->all());
        }
        
        $validation_data = $employee->validate();
        if (!$validation_data[0]) {
            return $this->_return_error_message($validation_data[1]);
        }

        $employee->set_height();

        // It'll try to save a column named additional_info if the attribute exists
        unset($employee->additional_info);

        if (!$employee->save()) {
            return $this->_return_error_message('Unable to save');
        }

        // TODO: Figure out how to make Eloquent handle the saving of relations
        if (property_exists($employee, 'additional_info')) {
            $additional->employee_id = $employee->id;
            $additional->save();
        }

        return response()->json(
            [
                'status' => 'hest med hat',
                'message' => 'created', 
            ]
        );
    }

    public function get_children(Employee $employee)
    {
        $children = Employee::where('parent_id', $employee->id)->with('additional_info')->get();
        return response()->json($children);
    }

    public function update_parent(Request $request, Employee $employee)
    {
        //TODO: Google how to do validation properly in Laravel
        $new_id = $request->post('new_id', null);
        if (is_null($new_id)) {
            return $this->_return_error_message('ID not provided');
        }

        if ($employee->id == $new_id) {
            return $this->_return_error_message('A node cannot be its own parent');
        }

        if ($employee->is_root()) {
            return $this->_return_error_message('The root node cannot have its parent changed');
        }

        if (is_null(Employee::find($new_id))) {
            return $this->_return_error_message('Invalid parent ID');
        }

        $employee->parent_id = $new_id;
        $employee->set_height();
        
        if (!$employee->update()) {
            // TODO: Find out if there's some sort of error that can be logged
            return $this->_return_error_message('Unable to save');
        }

        return response()->json(
            [
                'status' => 'ok',
                'message' => 'updated', 
            ]
        );
    }  
}
