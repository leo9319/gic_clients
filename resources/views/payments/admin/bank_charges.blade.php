@extends('layouts.master')

@section('title', 'Bank Charges')

@section('content')

<div class="container-fluid" id="app">

   <div class="panel">

      <div class="panel-heading">
         <h3 class="panel-title">Bank Charges</h3>
      </div>

      <div class="panel-body">

         <table id="department-targets" class="table table-striped table-bordered">
            <thead>
               <tr>
                  <th>Bank Name</th>
                  <th>Charge</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
            	  @foreach($bank_charges as $bank_charge)
                  <tr>
                     <td>{{ ucwords(str_replace("_", " ", $bank_charge->bank_name)) }}</td>
                     <td>{{ $bank_charge->bank_charge }} %</td>
                     <td>
                 		<button class="btn btn-info btn-sm btn-block" id="paad" @click="onEditBankCharge($event)">Edit</button>
                     </td>
                  </tr>
                  @endforeach
            </tbody>
            <thead>
               <tr>
                  <th>Bank Name</th>
                  <th>Charge</th>
                  <th>Action</th>
               </tr>
            </thead>
         </table>

      </div>
   </div>
</div>

@section('footer_scripts')

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.0"></script>
{{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.js"></script> --}}

<script type="text/javascript">
	
	var app = new Vue({

		el: '#app',

		methods: {

			onEditBankCharge(event) {
				targetId = event.currentTarget.id;
            	alert(targetId);
			}

		}

	});

</script>

@endsection

@endsection