<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\ClientProgram;
use App\ClientFileInfo;
use PDF;

class InvoiceController extends Controller
{
    public function opening($client_id)
    {
    	$data['client'] = User::find($client_id);
    	$data['client_programs'] = ClientProgram::selectedPrograms($client_id);
    	$data['client_file_info'] = ClientFileInfo::moreInfo($client_id);

    	// $pdf = PDF::loadView('invoice.index', $data);

    	// return $pdf->download('invoice.pdf');

    	return view('invoice.index', $data);
    }
}
