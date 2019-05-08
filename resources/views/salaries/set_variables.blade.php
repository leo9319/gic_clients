@extends('layouts.master')

@section('title', 'Set Variables')

@section('content')

<div class="container-fluid">

   <div class="panel panel-headline">

      <div class="panel-heading">

      	<h2>Set Variables</h2>

      </div>

      <div class="panel-body">

      	<table id="statement" class="table table-striped table-bordered" style="width:100%">

            <thead>

               <tr>

                  <th>SL.</th>

                  <th>Variable Name</th>

                  <th>Action</th>

               </tr>

            </thead>

            <tbody>

            	<tr>
            		<td>1</td>
            		<td>Set Employees</td>
            		<td>
            			<a href="{{ route('salaries.set_employees') }}" class="btn btn-success btn-block btn-sm">Assign</a>
            		</td>
            	</tr>
            	<tr>
            		<td>2</td>
            		<td>Set Basic Salary</td>
            		<td>
            			<a href="{{ route('salaries.set_rms') }}" class="btn btn-success btn-block btn-sm">Assign</a>
            		</td>
            	</tr>
            	<tr>
            		<td>2</td>
            		<td>Designate Employees</td>
            		<td>
            			<a href="{{ route('salaries.set_rms') }}" class="btn btn-success btn-block btn-sm">Assign</a>
            		</td>
            	</tr>
            	
            </tbody>

            <tfoot>

               <tr>

                  <th>SL.</th>

                  <th>Variable Name</th>

                  <th>Action</th>

               </tr>

            </tfoot>

         </table>
      	
      </div>

  </div>

</div>

@endsection