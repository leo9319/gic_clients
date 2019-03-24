<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\IncomeExpense;
use App\Payment;
use App\Step;
use App\PaymentType;
use App\Program;
use App\User;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin')->only('index');
    }
    
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
    	// $client_payment =  Payment::whereBetween('created_at', [$from, $to])->get();
        $client_payment = PaymentType::whereBetween('created_at', [$from, $to])
                            ->where('cheque_verified', 1)
                            ->get();
    	$counter = 0;
    	$data['sum'] = 0;
        

    	foreach ($income_and_expenses as $iae_key => $iae_value) {

            $reports[$counter]['date'] = Carbon::parse($iae_value['created_at'])->format('d-m-y');
            $reports[$counter]['client_code'] = 'N/A';
            $reports[$counter]['client_name'] = 'N/A';
    		$reports[$counter]['location'] = $iae_value['location'];
    		$reports[$counter]['description'] = $iae_value['description'];
            $reports[$counter]['type'] = $iae_value['payment_type'];
            $reports[$counter]['counselor'] = ['N/A'];
    		$reports[$counter]['rm'] = ['N/A'];
            $reports[$counter]['bank'] = $iae_value['bank_name'];
    		$reports[$counter]['notes'] = $iae_value['bank_name'];
            $reports[$counter]['amount'] = $iae_value['total_amount'];
    		$reports[$counter]['after_charge'] = $iae_value['total_amount'];
    		$data['sum'] += $iae_value['total_amount'];

    		$counter++;
    	}

    	foreach ($client_payment as $cp_key => $cp_value) {
            $refund = '';
            $counselors_array = [];
            $rms_array = [];
            $reports[$counter]['date'] = Carbon::parse($cp_value['created_at'])->format('d-m-y');

            $payment = Payment::find($cp_value['payment_id']);

            $reports[$counter]['client_code'] = User::find($payment->client_id)->client_code ?? 'N/A';
            $reports[$counter]['client_name'] = User::find($payment->client_id)->name ?? 'N/A';
            $reports[$counter]['location'] = $payment->location;
            $reports[$counter]['description'] = Program::find($payment->program_id)->program_name ?? 'Program Removed';

            if($cp_value['refund_payment']) {

                $refund = '(refund)';
                $reports[$counter]['amount'] = -$cp_value['amount_paid'];
                $reports[$counter]['after_charge'] = -$cp_value['amount_received'];

            } else {

                $reports[$counter]['amount'] = $cp_value['amount_paid'];
                $reports[$counter]['after_charge'] = $cp_value['amount_received'];

            }

            $type = Step::find($payment->step_id)->step_name ?? 'Step Removed';

            $reports[$counter]['type'] = $type . ' ' . $refund;

            foreach($payment->userInfo->getAssignedCounselors as $counselors) {

                array_push($counselors_array, ($counselors->user->name ?? 'N/A'));

            }

            foreach($payment->userInfo->getAssignedRms as $rms) {

                array_push($rms_array, ($rms->user->name ?? 'N/A'));

            }

            

            $reports[$counter]['counselor'] = $counselors_array;

            $reports[$counter]['rm'] = $rms_array;

            $reports[$counter]['bank'] = $cp_value['bank_name'];

            $reports[$counter]['notes'] = $payment->comments;

            $data['sum'] += 0;

            $counter++;
            
    	}

        // return $client_payment;

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
