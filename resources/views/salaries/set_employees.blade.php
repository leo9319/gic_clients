@extends('layouts.master')

@section('title', 'Set Employees')

@section('content')

<div class="container-fluid">

   <div class="row">

      <div class="col-md-8">

         <div class="panel">

            <div class="panel-heading">

               <h3 class="panel-title">List of Employees</h3>

            </div>

            <div class="panel-body no-padding">

               <table class="table table-striped">

                  <thead>

                     <tr>

                        <th>Customer ID.</th>
                        <th>Name</th>
                        <th>Date Opened</th>
                        <th>View Profile</th>

                     </tr>

                  </thead>

                  <tbody>

                  </tbody>

               </table>

            </div>

         </div>

      </div>


      <div class="col-md-4">

         <div class="panel">

            <div class="panel-heading">

               <h3 class="panel-title">Add Employees</h3>

            </div>

            <div class="panel-body no-padding">

               {{ Form::open() }}

				<div class="form-group">

					{{ Form::label('name') }}
					{{ Form::text('name', null, ['class'=>'form-control']) }}
					
				</div>

               {{ Form::close() }}

            </div>

         </div>

      </div>

   </div>

</div>

@endsection