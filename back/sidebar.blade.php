<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading mb-3">Hi {{auth()->user()->name}}</div>
                    <hr>

                    @if(auth()->user()->hasRole('admin'))
                    <!-- ////////////// -->
                    <!-- admin routes -->
                    <!-- ////////////// -->
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>

                    <hr>

                    @if(auth()->user()->hasRole('consultant'))
                    <a class="nav-link" href="{{ route('consultant.dashboard') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Personal Dashboard
                    </a>
                    <hr>
                    @endif

                    <a class="nav-link" href="{{ route('admin.consultant.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Consultants
                    </a>
                    <hr>

                    <a class="nav-link" href="{{ route('admin.consultant.due_payment') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Consultants Due
                    </a>
                    <hr>

                    <a class="nav-link" href="{{ route('consultant.patient.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Patients
                    </a>
                    <hr>

                    @endif


                    @if(auth()->user()->hasRole('consultant'))
                    <!-- ////////////// -->
                    <!-- consultant routes -->
                    <!-- ////////////// -->
                    @if(!auth()->user()->hasRole('admin'))
                    <a class="nav-link" href="{{ route('consultant.dashboard') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                    <hr>

                    <a class="nav-link" href="{{ route('consultant.patient.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        All Patients
                    </a>
                    <hr>
                    @endif

                    <a class="nav-link" href="{{ route('consultant.schedule.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Schedules
                    </a>
                    <hr>


                    <a class="nav-link" href="{{ route('consultant.appointment.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        All Appointments
                    </a>
                    <hr>


                    @if(auth()->user()->hasRole('admin'))
                    <a class="nav-link" href="{{ route('admin.setting') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Company Settings
                    </a>
                    <hr>
                    @endif

                    @endif

                    @if(auth()->user()->hasRole('patient'))
                    <!-- ////////////// -->
                    <!-- patient routes -->
                    <!-- ////////////// -->
                    <a class="nav-link" href="{{ route('patient.dashboard') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                    <hr>

                    <a class="nav-link" href="{{ route('patient.appointment.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        All Appointments
                    </a>
                    <hr>

                    <!-- <a class="nav-link" href="{{ url('payment') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            payment
                        </a>
                        <hr> -->
                    @endif

                    @if(auth()->user()->hasRole('admin'))
                    <!-- ////////////// -->
                    <!-- admin routes -->
                    <!-- ////////////// -->
                    <a class="nav-link" href="{{ route('admin.contact.list') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Contact
                    </a>
                    <hr>


                    <a class="nav-link" href="{{ route('admin.category.list') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Blog Category
                    </a>
                    <hr>
                        
                        <a class="nav-link" href="{{ route('admin.blog.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Blog
                        </a>
                        <hr>
                        
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError1" aria-expanded="false" aria-controls="pagesCollapseError">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>Manage Site
                                        <div class="sb-sidenav-collapse-arrow"><svg class="svg-inline--fa fa-angle-down" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="angle-down" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" data-fa-i2svg=""><path fill="currentColor" d="M192 384c-8.188 0-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L192 306.8l137.4-137.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-160 160C208.4 380.9 200.2 384 192 384z"></path></svg><!-- <i class="fas fa-angle-down"></i> Font Awesome fontawesome.com --></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseError1" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages" style="">
                                        <nav style="margin-left: 10px;" class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="{{ route('admin.home-settings') }}">Home Page</a>
                                            <a class="nav-link" href="{{ route('admin.page.index') }}">Manage Pages</a>
                                            <a class="nav-link" href="{{ route('admin.contact-details') }}">Contact Us</a>
                                            <a class="nav-link" href="{{ route('admin.social-details') }}">Social Links</a>
                                            <a class="nav-link" href="{{ route('admin.testimonial.index') }}">Testimonials</a>
                                        </nav>
                                    </div>
                  

                    

                    <!-- <a class="nav-link" href="{{ url('pricing') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Pricing
                        </a>

                        <hr> -->

                    @endif


                </div>
            </div>

        </nav>
    </div>