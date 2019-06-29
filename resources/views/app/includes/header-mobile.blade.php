        <!-- HEADER MOBILE-->
        <header class="header-mobile header-mobile-2 d-block d-lg-none" style="background:{{ $company->theme_color }};" id="header-bg">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <a class="logo" href="#">
                            <img src="{{ asset('images/genysis/genysis.png') }}" alt="genysis" style="width:120px;" />
                        </a>
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <nav class="navbar-mobile">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
                   
                        <li>
                            <a href="{{ route('app.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                        </li>

   
                        <li>
                            <a href="{{ route('app.upload') }}">
                                <i class="fas fa-upload"></i>Import Data</a>
                        </li>                        
 


                    
                        <li class="has-sub">
                            <a class="js-arrow" href="{{ route('app.orders') }}">
                                <i class="fas fa-copy"></i>Orders</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="{{ route('app.orders') }}">Sales Order</a>
                                </li>
                                <li>
                                    <a href="{{ route('app.returns') }}">Returns Order</a>
                                </li>
                            </ul>
                        </li>
                        

                        <li class="has-sub">
                                <a class="js-arrow" href="{{ route('app.orders') }}">
                                    <i class="fas fa-copy"></i>Report</a>
                                <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                    <li>
                                        <a href="{{ route('app.orders') }}">Sales Order</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('app.returns') }}">Returns Order</a>
                                    </li>
                                </ul>
                            </li>


                    </ul>
                </div>
            </nav>
        </header>
        
        <div class="sub-header-mobile-2 d-block d-lg-none">
                <div class="header__tool">
                        <div class="header-button-item
                        @if ($orderFailedCount != 0)
                        has-noti 
                        @endif
                         js-item-menu">
                            <i class="zmdi zmdi-alert-circle"></i>
                            <div class="notifi-dropdown notifi-dropdown--no-bor js-dropdown">
                                @if ($orderFailedCount != 0)
                                    <div class="notifi__title">
                                            <p>You have <span class="badge badge-danger"> {{ $orderFailedCount }}</span> Failed Orders</p>
                                    </div>
                                @endif

                                @if ($returnFailedCount != 0)
                                    <div class="notifi__title">
                                            <p>You have <span class="badge badge-danger"> {{ $returnFailedCount }}</span> Failed Returns</p>
                                    </div>
                                @endif

                                @if ($orderFailedCount != 0 || $returnFailedCount != 0)                                  
                                    {{-- <div class="notifi__footer">                                        
                                        <a href="#"><i class="fa fa-wrench"></i> Fix all errors</a>
                                    </div> --}}                                    
                                @else 
                                    <div class="notifi__title">
                                        <p>No Failed Data yet</p>
                                    </div>
                                @endif

                            </div>
                        </div>
                        <div class="header-button-item js-item-menu">
                            <i class="zmdi zmdi-settings"></i>                        
                            <div class="setting-dropdown js-dropdown">
                                <div class="account-dropdown__body">
                                        <div class="notifi__title">
                                                <p>Field Validation Settings</p>
                                        </div>

                                    <div class="account-dropdown__item">
                                    <a href="{{ route('app.field-settings', ['field' => $formats->siforder ]) }}"><i class="fa fa-shopping-cart"></i> {{ $formats->siforder }}</a>
                                    </div>

                                    <div class="account-dropdown__item">
                                        <a href="{{ route('app.field-settings', ['field' => $formats->sifreturn ]) }}"><i class="fa fa-reply"></i> {{ $formats->sifreturn }}</a>
                                    </div>

                                        <div class="account-dropdown__item">
                                            <a href="{{ route('app.field-settings', ['field' => $formats->sifitem ]) }}"><i class="fa fa-laptop"></i> {{ $formats->sifitem }}</a>
                                        </div>
        
                                        <div class="account-dropdown__item">
                                            <a href="{{ route('app.field-settings', ['field' => $formats->sifcustomer ]) }}"><i class="fa fa-users"></i> {{ $formats->sifcustomer }}</a>
                                        </div>

                                        <div class="account-dropdown__item">
                                            <a href="{{ route('app.field-settings', ['field' => $formats->sifdsp ]) }}"><i class="fa fa-user"></i> {{ $formats->sifdsp }}</a>
                                        </div>
                            
            
                                </div>
                               
                            </div>
                        </div>
                        <div class="account-wrap">
                            <div class="account-item account-item--style2 clearfix js-item-menu">
                                <div class="image">
                                    <img src="{{ asset($company->logo) }}" alt="genysis">
                                </div>                                        
                                 <div class="content">
                                    <a class="js-acc-btn" href="#">{{ $company->company_name }}</a>
                                </div>
                                <div class="account-dropdown js-dropdown">
                                    <div class="info clearfix">
                                        <div class="image">
                                            <a href="#">
                                            <img src="{{ asset($company->logo) }}" alt="genysis">
                                            </a>
                                            

                                        </div>                                        
                                        <div class="content">
                                            <h5 class="name">
                                                <a href="#">{{ $company->company_name }}</a>
                                            </h5>
                                        <span class="email">{{ $company->company_email }}</span>
                                        </div>
                                    </div>
                                    <div class="account-dropdown__body">
                                        <div class="account-dropdown__item">
                                            <a href="{{ route('app.company-settings') }}">
                                                <i class="zmdi zmdi-settings"></i>Company Settings</a>
                                        </div>                                        
                                        <div class="account-dropdown__item">
                                            <a href="{{ route('app.about-system') }}">
                                                <i class="zmdi zmdi-info"></i>About System</a>
                                        </div>

                                        <div class="account-dropdown__item">
                                            <a href="#">
                                                <i class="zmdi zmdi-storage"></i>Database</a>
                                        </div>

                                    </div>
                                    {{-- <div class="account-dropdown__footer">
                                        <a href="#">
                                            <i class="zmdi zmdi-power"></i>Logout</a>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
        </div>
        <!-- END HEADER MOBILE -->
        
        <!-- PAGE CONTENT-->
        <div class="page-content--bgf7">
