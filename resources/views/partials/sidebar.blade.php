<div class="left-side-menu">

    <div class="h-100" data-simplebar>

         <!-- User box -->
        <div class="user-box text-center">

            {{-- <img src="/assets/images/users/user-1.jpg" alt="user-img" title="Mat Helme" class="rounded-circle img-thumbnail avatar-md">
                <div class="dropdown">
                    <a href="#" class="user-name dropdown-toggle h5 mt-2 mb-1 d-block" data-bs-toggle="dropdown"  aria-expanded="false">Nowak Helme</a>
                    <div class="dropdown-menu user-pro-dropdown">

                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <i class="fe-user me-1"></i>
                            <span>My Account</span>
                        </a>
        
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <i class="fe-settings me-1"></i>
                            <span>Settings</span>
                        </a>
        
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <i class="fe-lock me-1"></i>
                            <span>Lock Screen</span>
                        </a>
        
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <i class="fe-log-out me-1"></i>
                            <span>Logout</span>
                        </a>
         
                    </div>
                </div>

            <p class="text-muted left-user-info">Admin Head</p> 

            <ul class="list-inline">
                <li class="list-inline-item">
                    <a href="#" class="text-muted left-user-info">
                        <i class="mdi mdi-cog"></i>
                    </a>
                </li>

                <li class="list-inline-item">
                    <a href="#">
                        <i class="mdi mdi-power"></i>
                    </a>
                </li>
            </ul>--}}
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <ul id="side-menu">
        
                <li class="menu-title">Navigation</li>
                
                <li>
                    <a href={{route('dashboard')}}>
                        <i class="mdi mdi-view-dashboard"></i>
                        <span> Dashboard </span>
                    </a>
                </li>
                <li>
                    <a href="#system" data-bs-toggle="collapse">
                        <i class="mdi mdi-folder-open"></i>
                        <span> Système d'information </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="system">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{route('employe')}}">
                                    <i class="fas fa-user-friends px-1"></i>
                                    <span> Employés </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{route('profil')}}">
                                    <i class="fas fa-user px-1"></i>
                                    <span> Profils </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{route('application')}}">
                                    <i class="ti-layout-tab-window px-1"></i>
                                    <span> Applications </span>  
                                </a>
                            </li>
                            <li>
                                <a href="{{route('module')}}">
                                    <i class="mdi mdi-view-module px-1"></i>
                                    <span> Modules </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{route('fonct')}}">
                                    <span> Fonctionnalités </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{route('entite')}}">
                                    <span> Entités </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{route('poste')}}">
                                    <span> Postes </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href={{route('addData')}}>
                        <i class="mdi mdi-view-dashboard"></i>
                        <span> Données trimestrielles </span>
                    </a>
                </li>
                <li>
                    <a href={{route('rapports')}}>
                        <i class="mdi mdi-view-dashboard"></i>
                        <span> Rapport des écarts </span>
                    </a>
                </li>
                {{-- <li>
                    <a href={{route('revue')}}>
                        <i class="mdi mdi-view-dashboard"></i>
                        <span> Revue </span>
                    </a>
                </li> --}}
            </ul>
        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>