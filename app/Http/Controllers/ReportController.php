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
        $data['active_class'] = 'reports';

        $from = $request->start_date;
        $to = $request->end_date;

    	$income_and_expenses =  IncomeExpense::whereNotIn('payment_type', ['Cash Transfer In', 'Cash Transfer Out'])->whereBetween('created_at', [$from, $to])->get();
    	$client_payment =  Payment::whereBetween('created_at', [$from, $to])->get();
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
            $without_refunds = PaymentType::where('payment_id', $cp_value['id'])->where('cheque_verified', 1)->where('refund_payment', 0)->sum('amount_received');
    		$with_refunds = PaymentType::where('payment_id', $cp_value['id'])->where('cheque_verified', 1)->where('refund_payment', 1)->sum('amount_received');

            $reports[$counter]['amount'] = $without_refunds - $with_refunds;


    		$data['sum'] += PaymentType::where('payment_id', $cp_value['id'])->sum('amount_received');;

    		$counter++;
    	}

    	asort($reports);
    	$data['reports'] = $reports;

        // return $reports;

    	return view('reports.monthly', $data);
    }

    public function ourCurrentClients(Request $request)
    {
        $data['active_class'] = 'reports'; 

        $from = $request->start_date;
        $to = $request->end_date;

        $data['payments'] = Payment::whereBetween('created_at', [$from, $to])->get();

        return view('reports.our_current_clients', $data);
    }
}
