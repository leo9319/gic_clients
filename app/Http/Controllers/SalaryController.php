<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Employee;

class SalaryController extends Controller
{
	public function index()
	{
		$data['active_class'] = 'salary'

		return view('salaries.index');
	}

    public function setEmployees()
    {
    	$data['active_class'] = 'salary';

    	return view('salaries.set_employees', $data);
    }

    public function setVariables()
    {
    	$data['active_class'] = 'salary';

    	return view('salaries.set_variables', $data);
    }

    public function setRMs()
    {
    	$data['active_class'] = 'salary';

    	$data['rms'] = User::userRole('rm')->get();
    	$data['counselors'] = User::userRole('counselor')->get();

    	return view('salaries.set_rms', $data);
    }

    public function storeEmployees(Request $request)
    {
    	return $request->all();
    }
}
