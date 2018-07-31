<div id="sidebar-nav" class="sidebar">
   <div class="sidebar-scroll">
      <nav>
         <ul class="nav">
            <li>
               <a href="{{ route('dashboard') }}" class="{{ $active_class == 'dashboard' ? 'active' : '' }}"><i class="lnr lnr-home"></i> <span>Dashboard</span></a>
            </li>

            @if(Auth::user()->user_role == 'rm' | Auth::user()->user_role == 'counselor')
               <li>
               <a href="{{ route('user.clients', Auth::user()->id) }}" class="{{ $active_class == 'my-clients' ? 'active' : '' }}">
                  <i class="fa fa-user-plus"></i>
                  <span>My Clients</span></a>
            </li>
            @endif

            @if(Auth::user()->user_role == 'admin')
               <li>
               <a href="#subTargets" data-toggle="collapse" class="collapsed {{ $active_class == 'set-targets' ? 'active' : '' }}"><i class="fa fa-bullseye"></i> <span>Set Targets</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
               <div id="subTargets" class="collapse ">
                  <ul class="nav">
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

            @if (Auth::user()->user_role == 'admin' || Auth::user()->user_role == 'rm' || Auth::user()->user_role == 'counsellor')
            <li>
               <a href="#subAppointments" data-toggle="collapse" class="collapsed {{ $active_class == 'appointments' ? 'active' : '' }}"><i class="fa fa-calendar"></i> <span>Appointments</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
               <div id="subAppointments" class="collapse ">
                  <ul class="nav">
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

            @if (Auth::user()->user_role == 'admin' || Auth::user()->user_role == 'rm' || Auth::user()->user_role == 'counsellor')
            <li>
               <a href="{{ route('task.index') }}" class="{{ $active_class == 'tasks' ? 'active' : '' }}">
                  <i class="fa fa-tasks"></i> 
                  <span>Tasks</span></a>
            </li>
            @endif

            @if (Auth::user()->user_role == 'client')
            <li>
               <a href="{{ route('client.myprograms', ['client_id'=>Auth::user()->id]) }}" class="{{ $active_class == 'my-tasks' ? 'active' : '' }}"><i class="fa fa-tasks"></i> <span>My Tasks</span></a>
            </li>
            <li>
               <a href="{{ route('appointment.client.rm', ['client_id'=>Auth::user()->id]) }}" class="{{ $active_class == 'appointments' ? 'active' : '' }}"><i class="fa fa-tasks"></i> <span>Set Appointment</span></a>
            </li>
          {{--  <li>
               <a href="#setAppointment" data-toggle="collapse" class="collapsed {{ $active_class == 'appointments' ? 'active' : '' }}"><i class="fa fa-tasks"></i> <span>Set Appointments</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
               <div id="setAppointment" class="collapse ">
                  <ul class="nav">
                     <li><a href="{{ route('appointment.client.rm', ['client_id'=>Auth::user()->id]) }}" class="">With RMs</a></li>
                     <li><a href="{{ route('appointment.client.counsellor', ['client_id'=>Auth::user()->id]) }}" class="">With Counsellors</a></li>
                  </ul>
               </div>
            </li>--}}
            @endif

            @if (Auth::user()->user_role != 'client')
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