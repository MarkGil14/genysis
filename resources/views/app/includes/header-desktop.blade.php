<div class="page-loader-wrapper" id="preload">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-white"> 
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please Wait ... </p>
        </div>
    </div>




<!-- HEADER DESKTOP-->
    <header class="header-desktop3 d-none d-lg-block" style="background:{{ $company->theme_color }};" id="header-bg">
            <div class="section__content section__content--p35">
                <div class="header3-wrap">
                    <div class="header__logo">
                        <a href="#">
                            <img src="{{ asset('images/genysis/genysis.png') }}" alt="CoolAdmin" style="width:139px;" />
                        </a>
                    </div>
                    <div class="header__navbar">
                        <ul class="list-unstyled">
                            <li  >
                                <a href="{{ route('app.dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i>Dashboard
                                    <span class="bot-line"></span>
                                </a>
                              
                            </li>
                            <li>
                            <a href="{{ route('app.upload') }}">
                                    <i class="fas fa-upload"></i>
                                    <span class="bot-line"></span>Import Data</a>
                            </li>
                            <li class="has-sub">
                                    <a href="{{ route('app.orders') }}">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span class="bot-line"></span>Orders</a>
                                    <ul class="header3-sub-list list-unstyled">
                                        <li>
                                        <a href="{{ route('app.orders') }}">Sales Order</a>
                                        </li>
                                        <li>
                                        <a href="{{ route('app.returns') }}">Returns Order</a>
                                        </li>
                                    </ul>
                                </li>

                                <li>
                                    <a href="{{ route('app.summary-report') }}">
                                        <i class="fas fa-book"></i>
                                        <span class="bot-line"></span>Summary</a>
                                </li>

                                

                                


                                <li class="has-sub">
                                        <a href="{{ route('app.order-report') }}">
                                            <i class="fas fa-download"></i>
                                            <span class="bot-line"></span>Export Data</a>
                                        <ul class="header3-sub-list list-unstyled">
                                            <li>
                                            <a href="{{ route('app.order-report') }}">Sales Order</a>
                                            </li>
                                            <li>
                                            <a href="{{ route('app.return-report') }}">Returns</a>
                                            </li>
                                        </ul>
                                    </li>

                                    

                                    
                        </ul>
                    </div>
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
            </div>
        </header>
        <!-- END HEADER DESKTOP-->

        
