<aside class="main-sidebar sidebar-dark-primary elevation-4">
   <!-- Brand Logo -->
   <a href="{{ Auth::user()->hasRole('admin') ? '/dashboard' : '/trainer-dashboard' }}" class="brand-link">
     <img src="/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
     <span class="brand-text font-weight-light">Title</span>
   </a>

   <!-- Sidebar -->
   <div class="sidebar">
     <!-- Sidebar user panel (optional) -->
     <div class="user-panel mt-3 pb-3 mb-3 d-flex">
       <div class="image">
         <img src="/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
       </div>
       <div class="info">
         <a href="{{ Auth::user()->hasRole('admin') ? '/dashboard' : '/trainer-dashboard' }}" class="d-block">{{ Auth::user()->name }}</a>
       </div>
     </div>

     <!-- Sidebar Menu -->
     <nav class="mt-2">
       <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Se l'utente è un admin -->
        @if(Auth::user()->hasRole('admin'))
         <!-- Dashboard -->
         <li class="nav-item">
           <a href="/dashboard" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
             <i class="nav-icon fas fa-tachometer-alt"></i>
             <p>Dashboard</p>
           </a>
         </li>

         <!-- USERS -->
         <li class="nav-item has-treeview {{ Request::is('dashboard/users*') ? 'menu-open' : '' }}">
           <a href="#" class="nav-link {{ Request::is('dashboard/users*') ? 'active' : '' }}">
             <span class="material-symbols-outlined">group</span>
             &nbsp;
             <p>
               Utenti
               <i class="fas fa-angle-left right"></i>
               <span class="badge badge-info right">{{ count(getSidebarData('users')) }}</span>
             </p>
           </a>
           <ul class="nav nav-treeview">
             <li class="nav-item">
               <a href="/dashboard/users" class="nav-link {{ Request::is('dashboard/users') ? 'active' : '' }}">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Tutti gli utenti</p>
               </a>
             </li>
             <li class="nav-item">
               <a href="{{ route('users.create') }}" class="nav-link {{ Request::is('dashboard/users/create') ? 'active' : '' }}">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Aggiungi utente</p>
               </a>
             </li>
           </ul>
         </li>

         <!-- COURSES -->
          <li class="nav-item has-treeview {{ Request::is('dashboard/courses*') ? 'menu-open' : '' }}">
           <a href="#" class="nav-link {{ Request::is('dashboard/courses*') ? 'active' : '' }}">
             <span class="material-symbols-outlined">sports_martial_arts</span>
             &nbsp;
             <p>
               Corsi
               <i class="fas fa-angle-left right"></i>
               <span class="badge badge-info right">{{ count(getSidebarData('courses')) }}</span>
             </p>
           </a>
           <ul class="nav nav-treeview">
             <li class="nav-item">
               <a href="/dashboard/courses" class="nav-link {{ Request::is('dashboard/courses') ? 'active' : '' }}">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Tutti i corsi</p>
               </a>
             </li>
             <li class="nav-item">
               <a href="{{ route('courses.create') }}" class="nav-link {{ Request::is('dashboard/courses/create') ? 'active' : '' }}">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Aggiungi corso</p>
               </a>
             </li>
           </ul>
          </li>

         <!-- STUDENTS -->
         <li class="nav-item has-treeview {{ Request::is('dashboard/students*') ? 'menu-open' : '' }}">
           <a href="#" class="nav-link {{ Request::is('dashboard/students*') ? 'active' : '' }}">
             <span class="material-symbols-outlined">sensor_occupied</span>
             &nbsp;
             <p>
               Allievi
               <i class="right fas fa-angle-left"></i>
             </p>
           </a>
           <ul class="nav nav-treeview">
             <li class="nav-item">
               <a href="/dashboard/students" class="nav-link {{ Request::is('dashboard/students') ? 'active' : '' }}">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Tutti gli allievi</p>
               </a>
             </li>
             <li class="nav-item">
               <a href="/dashboard/students/create" class="nav-link {{ Request::is('dashboard/students/create') ? 'active' : '' }}">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Aggiungi allievo</p>
               </a>
             </li>
           </ul>
         </li>

         <!-- ATTENDANCES -->
         <li class="nav-item">
           <a href="/dashboard/attendances" class="nav-link {{ Request::is('dashboard/attendances') ? 'active' : '' }}">
             <i class="nav-icon far fa-calendar-alt"></i>
             <p>Calendario</p>
           </a>
         </li>
        @endif
        <!-- ######################################################################################## -->
        <!-- ######################################################################################## -->
        <!-- ######################################################################################## -->
        <!-- ######################################################################################## -->
        <!-- ######################################################################################## -->
        <!-- ######################################################################################## -->
        <!-- ######################################################################################## -->
        <!-- ######################################################################################## -->
        <!-- ######################################################################################## -->
        <!-- ######################################################################################## -->
        <!-- Se l'utente è un trainer -->
        @if(Auth::user()->hasRole('trainer'))
         <!-- Dashboard -->
         <li class="nav-item">
           <a href="/trainer-dashboard" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
             <i class="nav-icon fas fa-tachometer-alt"></i>
             <p>Dashboard</p>
           </a>
         </li>

         <!-- USERS -->
         
 
         <!-- STUDENTS -->
         <li class="nav-item has-treeview {{ Request::is('trainer-dashboard/students*') ? 'menu-open' : '' }}">
           <a href="#" class="nav-link {{ Request::is('trainer-dashboard/students*') ? 'active' : '' }}">
             <span class="material-symbols-outlined">sensor_occupied</span>
             &nbsp;
             <p>
               Allievi
               <i class="right fas fa-angle-left"></i>
             </p>
           </a>
           <ul class="nav nav-treeview">
             
             <li class="nav-item">
               <a href="{{ route('trainer.students.create') }}" class="nav-link {{ Request::is('trainer-dashboard/students/create') ? 'active' : '' }}">
                 <i class="far fa-circle nav-icon"></i>
                 <p>Aggiungi allievo</p>
               </a>
             </li>
           </ul>
         </li>
          <!-- COURSES -->
          <li class="nav-item has-treeview {{ Request::is('dashboard/courses*') ? 'menu-open' : '' }}">
           <a href="/trainer-dashboard/courses" class="nav-link {{ Request::is('dashboard/courses*') ? 'active' : '' }}">
             <span class="material-symbols-outlined">sports_martial_arts</span>
             &nbsp;
             <p>
               I miei corsi
               <span class="badge badge-info right"></span>
             </p>
           </a>
           
          </li>
         <!-- ATTENDANCES -->
         <li class="nav-item">
           <a href="/trainer-dashboard/attendances" class="nav-link {{ Request::is('trainer-dashboard/attendances') ? 'active' : '' }}">
             <i class="nav-icon far fa-calendar-alt"></i>
             <p>Calendario</p>
           </a>
         </li>
        @endif
       </ul>
     </nav>
     <!-- /.sidebar-menu -->
   </div>
   <!-- /.sidebar -->
 </aside>
