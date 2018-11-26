<div id="sidebar-nav" class="sidebar">
   <div class="sidebar-scroll">
      <nav>
         <ul class="nav">
            <li>
               <a href="{{ route('dashboard') }}" class="{{ $active_class == 'dashboard' ? 'active' : '' }}"><i class="lnr lnr-home"></i> <span>Dashboard</span></a>
            </li>

            @if (Auth::user()->user_role == 'admin')
            <li>
               <a href="{{ route('task.index') }}" class="{{ $active_class == 'tasks' ? 'active' : '' }}">
                  <i class="fa fa-tasks"></i> 
                  <span>Programs and Tasks</span></a>
            </li>
            @endif

            @if(Auth::user()->user_role == 'rm' || Auth::user()->user_role == 'counselor')
               <li>
                  <a href="{{ route('user.clients', Auth::user()->id) }}" class="{{ $active_class == 'my-clients' ? 'active' : '' }}">
                     <i class="fa fa-user-plus"></i>
                     <span>My Clients</span>
                  </a>
               </li>

               <li>
                  <a href="{{ route('task.user.tasks', Auth::user()->id) }}" class="{{ $active_class == 'user-tasks' ? 'active' : '' }}">
                     <i class="fa fa-check"></i>
                     <span>My Tasks</span>
                  </a>
               </li>
               <li>
                  <a href="{{ route('client.appointment', Auth::user()->id) }}" class="{{ $active_class == 'my-appointments' ? 'active' : '' }}">
                     <i class="fa fa-calendar"></i>
                     <span>My Appointments</span>
                  </a>
               </li>


            @endif

            @if(Auth::user()->user_role == 'client')
               <li>

               <a href="#subTasks" data-toggle="collapse" class="collapsed {{ $active_class == 'client-tasks' ? 'active' : '' }}">

                  <i class="fa fa-tasks"></i>

                  <span>Tasks</span> 

                  <i class="icon-submenu lnr lnr-chevron-left"></i>

               </a>


               <div id="subTasks" class="collapse ">

               <ul class="nav">

                  <li>

                  <a href="{{ route('client.myprograms', Auth::user()->id) }}" class="{{ $active_class == 'client-tasks' ? 'active' : '' }}">

                  <i class="fa fa-tasks"></i>

                  <span>My Tasks</span></a>

                  </li>

                  <li>

                     <a href="{{ route('spouse.myprograms', Auth::user()->id) }}" class="{{ $active_class == 'client-tasks' ? 'active' : '' }}">

                     <i class="fa fa-tasks"></i>

                     <span>Spouse Tasks</span></a>

                  </li>

                  <li>

                     <a href="{{ route('client.assigned.counselor', Auth::user()->id) }}" class="{{ $active_class == 'client-tasks' ? 'active' : '' }}">

                     <i class="fa fa-tasks"></i>

                     <span>Counselor Tasks</span></a>

                  </li>

                  <li>

                     <a href="{{ route('client.assigned.rm', Auth::user()->id) }}" class="{{ $active_class == 'client-tasks' ? 'active' : '' }}">

                     <i class="fa fa-tasks"></i>

                     <span>RM Tasks</span></a>

                  </li>

               </ul>

               </div>

               </li>
               
            @endif

            @if(Auth::user()->user_role == 'admin')
               <li>
               <a href="#subTargets" data-toggle="collapse" class="collapsed {{ $active_class == 'set-targets' ? 'active' : '' }}"><i class="fa fa-bullseye"></i> <span>Set Targets</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
               <div id="subTargets" class="collapse ">
                  <ul class="nav">
                     <li>
                        <a href="{{ route('target.department') }}" class="{{ $active_class == 'department-targets' ? 'active' : '' }}">
                           <i class="fa fa-bullseye"></i> 
                           <span>Department Targets</span></a>
                     </li>
                     <li>
                        <a href="{{ route('target.rm') }}" class="{{ $active_class == 'set-targets' ? 'active' : '' }}">
                           <i class="fa fa-bullseye"></i> 
                           <span>RMs</span></a>
                     </li>
                     <li>
                        <a href="{{ route('target.counselor') }}" class="{{ $active_class == 'set-targets' ? 'active' : '' }}">
                           <i class="fa fa-bullseye"></i> 
                           <span>Counselors</span></a>
                     </li>
                  </ul>
               </div>
            </li>
            @endif

            @if(Auth::user()->user_role == 'admin')
               <li>
               <a href="#subStaff" data-toggle="collapse" class="collapsed {{ $active_class == 'assigend_clients' ? 'active' : '' }}"><i class="fa fa-sitemap"></i> <span>Assigned Clients</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
               <div id="subStaff" class="collapse ">
                  <ul class="nav">
                     <li>
                        <a href="{{ route('rms.index') }}" class="{{ $active_class == 'rms' ? 'active' : '' }}">
                           <i class="fa fa-sitemap"></i> 
                           <span>RMs</span></a>
                     </li>
                     <li>
                        <a href="{{ route('counselors.index') }}" class="{{ $active_class == 'counselors' ? 'active' : '' }}">
                           <i class="fa fa-sitemap"></i> 
                           <span>Counselors</span></a>
                     </li>
                  </ul>
               </div>
            </li>
            @endif

            @if(Auth::user()->user_role == 'client')
            <li>
               <a href="#subProfile" data-toggle="collapse" class="collapsed {{ $active_class == 'file' ? 'active' : '' }}"><i class="fa fa-file"></i> <span>File</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
               <div id="subProfile" class="collapse ">
                  <ul class="nav">
                     <li><a href="{{ route('file.create') }}" class="">Create Your File</a></li>
                     <li><a href="{{ route('file.myfile') }}" class="">View My File</a></li>
                     <!-- <li><a href="#" class="">Edit File</a></li> -->
                  </ul>
               </div>
            </li>
            @endif

            @if(Auth::user()->user_role == 'admin' || Auth::user()->user_role == 'backend'|| Auth::user()->user_role == 'client')

            <li>

               <a href="#subAppointments" data-toggle="collapse" class="collapsed {{ $active_class == 'appointments' ? 'active' : '' }}"><i class="fa fa-calendar"></i> <span>Appointments</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
               <div id="subAppointments" class="collapse ">
                  <ul class="nav">

                     @if(Auth::user()->user_role != 'admin')
                     @if(Auth::user()->user_role != 'backend')

                     <li>
                        <a href="{{ route('client.appointment', Auth::user()->id) }}" class="">
                           <i class="fa fa-calendar"></i>
                           <span>My Appointments</span></a>
                     </li>     

                     @endif
                     @endif

                     <li>
                        <a href="{{ route('appointment.rm.appointment') }}" class="">
                           <i class="fa fa-calendar"></i>
                           <span>RMs</span></a>
                     </li>
                     <li>
                        <a href="{{ route('appointment.counselor.appointment') }}" class="">
                           <i class="fa fa-calendar"></i>
                           <span>Counselors</span></a>
                     </li>
                  </ul>
               </div>
            </li>

            @endif

            @if (Auth::user()->user_role == 'admin' || Auth::user()->user_role == 'accountant' || Auth::user()->user_role == 'counselor' || Auth::user()->user_role == 'rm')

            <li>
               <a href="#subPayments" data-toggle="collapse" class="collapsed {{ $active_class == 'payments' ? 'active' : '' }}"><i class="fa fa-credit-card"></i> <span>Payments</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>

               <div id="subPayments" class="collapse ">
                  <ul class="nav">
                     @if(Auth::user()->user_role == 'admin' || Auth::user()->user_role == 'accountant')

                     <li>
                        <a href="{{ route('payment.income') }}" class="">
                           <i class="fa fa-credit-card"></i>
                           <span>Income</span>
                        </a>
                     </li>

                     <li>
                        <a href="{{ route('payment.expense') }}" class="">
                           <i class="fa fa-credit-card"></i>
                           <span>Expense</span>
                        </a>
                     </li>

                     <li>
                        <a href="{{ route('payment.show.income.and.expenses') }}" class="">
                           <i class="fa fa-credit-card"></i>
                           <span>View Income/Expense</span>
                        </a>
                     </li>

                     @endif

                     @if(Auth::user()->user_role == 'admin')
                     <li>
                        <a href="{{ route('payment.bank.account') }}" class="">
                           <i class="fa fa-credit-card"></i>
                           <span>Bank Account</span></a>
                     </li>
                     @endif

                     <li>
                        <a href="{{ route('payment.statement') }}" class="">
                           <i class="fa fa-credit-card"></i>
                           <span>Statement of Accounts</span></a>
                     </li>

                     <li>
                        <a href="{{ route('payment.history') }}" class="">
                           <i class="fa fa-credit-card"></i>
                           <span>Client Payment History</span></a>
                     </li>

                     <li>
                        <a href="{{ route('payment.client.refund') }}" class="">
                           <i class="fa fa-credit-card"></i>
                           <span>Refund Client</span></a>
                     </li>

                     {{-- View only available to Counselor or RM --}}

                     @if(Auth::user()->user_role == 'accountant')

                     <li>
                        <a href="{{ route('payment.index') }}" class="">
                           <i class="fa fa-credit-card"></i>
                           <span>Create Invoice</span></a>
                     </li>

                     @endif

                  </ul>
               </div>
            </li>

            @endif

            @if(Auth::user()->user_role == 'admin')
            <li>
               <a href="{{ route('reports.index') }}" class="{{ $active_class == 'reports' ? 'active' : '' }}">
                  <i class="fa fa-folder-open"></i>
                  <span>Reports</span></a>
            </li>

            @endif

            @if (Auth::user()->user_role == 'admin' || Auth::user()->user_role == 'backend')
               <li>
                  <a href="{{ route('client.index') }}" class="{{ $active_class == 'clients' ? 'active' : '' }}">
                     <i class="fa fa-users"></i> 
                     <span>All Clients</span></a>
               </li>
            @endif
            
            @if(Auth::user()->user_role == 'admin')
               <li><a href="{{ route('users') }}" class="{{ $active_class == 'users' ? 'active' : '' }}"><i class="fa fa-user-circle"></i></i> <span>GIC Staffs</span></a></li>
            @endif


         </ul>
      </nav>
   </div>
</div>