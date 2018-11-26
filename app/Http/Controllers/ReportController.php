<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\IncomeExpense;
use App\Payment;
use App\Step;
use App\PaymentType;
use App\Program;

class ReportController extends Controller
{
    public function index()
    {
    	$data['active_class'] = 'reports';

    	return view('reports.index', $data);
    }

    public function profitAndLoss(Request $request)
    {
    	$income_and_expenses =  IncomeExpense::all();
    	$client_payment =  Payment::all();
    	$counter = 0;
    	$data['sum'] = 0;

    	foreach ($income_and_expenses as $iae_key => $iae_value) {

    		$reports[$counter]['date'] = Carbon::parse($iae_value['created_at'])->format('d-m-y');
    		$reports[$counter]['description'] = $iae_value['description'];
    		$reports[$counter]['type'] = $iae_value['payment_type'];
    		$reports[$counter]['bank'] = $iae_value['bank_name'];
    		$reports[$counter]['amount'] = $iae_value['total_amount'];
    		$data['sum'] += $iae_value['total_amount'];

    		$counter++;
    	}

    	foreach ($client_payment as $cp_key => $cp_value) {
    		$reports[$counter]['date'] = Carbon::parse($cp_value['created_at'])->format('d-m-y');
    		$reports[$counter]['description'] = Program::find($cp_value['program_id'])->program_name;
    		$reports[$counter]['type'] = Step::find($cp_value['step_id'])->step_name;
    		$reports[$counter]['bank'] = 'N/A';
    		$reports[$counter]['amount'] = PaymentType::where('payment_id', $cp_value['id'])->sum('amount_received');
    		$data['sum'] += PaymentType::where('payment_id', $cp_value['id'])->sum('amount_received');;

    		$counter++;
    	}

    	asort($reports);
    	$data['reports'] = $reports;

    	return view('reports.profit_and_loss', $data);
    }

    public function monthly(Request $request)
    {
    	$month = $data['month'] = Carbon::parse($request->month)->month; 

    	$income_and_expenses =  IncomeExpense::whereMonth('created_at', '=', $month)->get();
    	$client_payment =  Payment::whereMonth('created_at', '=', $month)->get();
    	$counter = 0;
    	$data['sum'] = 0;

    	foreach ($income_and_expenses as $iae_key => $iae_value) {

    		$reports[$counter]['date'] = Carbon::parse($iae_value['created_at'])->format('d-m-y');
    		$reports[$counter]['description'] = $iae_value['description'];
    		$reports[$counter]['type'] = $iae_value['payment_type'];
    		$reports[$counter]['bank'] = $iae_value['bank_name'];
    		$reports[$counter]['amount'] = $iae_value['total_amount'];
    		$data['sum'] += $iae_value['total_amount'];

    		$counter++;
    	}

    	foreach ($client_payment as $cp_key => $cp_value) {
    		$reports[$counter]['date'] = Carbon::parse($cp_value['created_at'])->format('d-m-y');
    		$reports[$counter]['description'] = Program::find($cp_value['program_id'])->program_name;
    		$reports[$counter]['type'] = Step::find($cp_value['step_id'])->step_name;
    		$reports[$counter]['bank'] = 'N/A';
    		$reports[$counter]['amount'] = PaymentType::where('payment_id', $cp_value['id'])->sum('amount_received');
    		$data['sum'] += PaymentType::where('payment_id', $cp_value['id'])->sum('amount_received');;

    		$counter++;
    	}

    	asort($reports);
    	$data['reports'] = $reports;

    	return view('reports.monthly', $data);
    }
}
