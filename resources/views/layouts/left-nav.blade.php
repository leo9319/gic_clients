<div id="sidebar-nav" class="sidebar">
   <div class="sidebar-scroll">
      <nav>
         <ul class="nav">
            <li><a href="{{ route('dashboard') }}" class="{{ $active_class == 'dashboard' ? 'active' : '' }}"><i class="lnr lnr-home"></i> <span>Dashboard</span></a></li>
            <!-- <li><a href="elements.html" class=""><i class="lnr lnr-code"></i> <span>Elements</span></a></li> -->
            <!-- <li><a href="charts.html" class=""><i class="lnr lnr-chart-bars"></i> <span>Charts</span></a></li> -->
            <!-- <li><a href="panels.html" class=""><i class="lnr lnr-cog"></i> <span>Panels</span></a></li> -->
            <!-- <li><a href="notifications.html" class=""><i class="lnr lnr-alarm"></i> <span>Notifications</span></a></li> -->
            <li>
               <a href="#subProfile" data-toggle="collapse" class="collapsed {{ $active_class == 'file' ? 'active' : '' }}"><i class="fa fa-file"></i> <span>File</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
               <div id="subProfile" class="collapse ">
                  <ul class="nav">
                     <li><a href="{{ route('file.create') }}" class="">Create Your File</a></li>
                     <li><a href="#" class="">My File</a></li>
                     <li><a href="#" class="">View File</a></li>
                     <li><a href="#" class="">Edit File</a></li>
                  </ul>
               </div>
            </li>

            @if (Auth::user()->user_role == 'rm' || Auth::user()->user_role == 'admin')
            <li>
               <a href="#subTask" data-toggle="collapse" class="collapsed {{ $active_class == 'tasks' ? 'active' : '' }}"><i class="fa fa-tasks"></i> <span>Tasks</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
               <div id="subTask" class="collapse ">
                  <ul class="nav">
                     <li><a href="{{ route('task.create') }}" class="">Create Task</a></li>
                  </ul>
               </div>
            </li>
            @endif

            @if (Auth::user()->user_role == 'client')
            <li>
               <a href="{{ route('client.mytasks', ['client_id'=>Auth::user()->id]) }}" class="{{ $active_class == 'my-tasks' ? 'active' : '' }}"><i class="fa fa-tasks"></i> <span>My Tasks</span></a>
            </li>
            @endif

            @if (Auth::user()->user_role == 'admin' || Auth::user()->user_role == 'rm' || Auth::user()->user_role == 'accountant')
            <li>
               <a href="{{ route('client.index') }}" class="{{ $active_class == 'clients' ? 'active' : '' }}">
                  <i class="fa fa-users"></i> 
                  <span>Clients</span></a>
            </li>
            @endif
            <!-- <li><a href="tables.html" class=""><i class="lnr lnr-dice"></i> <span>Tables</span></a></li> -->
            <!-- <li><a href="typography.html" class=""><i class="lnr lnr-text-format"></i> <span>Typography</span></a></li> -->
            <!-- <li><a href="icons.html" class=""><i class="lnr lnr-linearicons"></i> <span>Icons</span></a></li> -->
            @if(Auth::user()->user_role == 'admin')
            <li><a href="{{ route('users') }}" class="{{ $active_class == 'users' ? 'active' : '' }}"><i class="fa fa-user-circle"></i></i> <span>Users</span></a></li>
            @endif
         </ul>
      </nav>
   </div>
</div>