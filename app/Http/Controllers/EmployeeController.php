<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    private function return_error_response($message) {
        return response()->json([
            'status' => 'error',
            'message' => $message, 
        ]);
    }


    public function index() {
        return Employee::all();
    }

    public function add_node(Request $request) {
        $employee = new Employee($request->all());
        
        if (is_null($employee->get_parent())) {
            return $this->return_error_response('Parent not found');
        }

        $employee->set_height();

        if (!$employee->save()) {
            return $this->return_error_response('Unable to save');
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Created', 
        ]);
        
    }

    public function get_children(Employee $employee) {
        $children = Employee::where('parent_id', $employee->id)->get();
        return response()->json($children);
    }

    public function update_parent(Request $request, Employee $employee) {
        //TODO: Google how to do validation properly in Laravel
        $new_id = $request->post('new_id', null);
        if (is_null($new_id)) {
            return $this->return_error_response('ID not provided');
        }

        if ($employee->id == $new_id) {
            return $this->return_error_response('A node cannot be its own parent');
        }

        if ($employee->is_root()) {
            return $this->return_error_response('The root node cannot have its parent changed');
        }

        if (is_null(Employee::find($new_id))) {
            return $this->return_error_response('Invalid parent ID');
        }

        $employee->parent_id = $new_id;
        $employee->set_height();
        
        if (!$employee->update()) {
            // TODO: Find out if there's some sort of error that can be logged
            return $this->return_error_response('Unable to save');
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'updated', 
        ]);
    }  
}
