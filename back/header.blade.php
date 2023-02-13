<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="{{route('home')}}"><img class="img-fluid"
            src="{{asset('panel/assets/img/logo.png')}}" alt=""></a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
            class="fas fa-bars"></i></button>
    <!-- Navbar Search-->



    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..."
                aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
        </div>
    </form>
    <!-- Navbar-->


    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4" id="notification">

        <li class="nav-item dropdown">
            @php($notification_count=App\Notification::where('user_id', auth()->id())->where('seen',0)->count())
            @php($notifications=App\Notification::where('user_id', auth()->id())->orderBy('created_at',
            'DESC')->where('seen',0)->take(5)->get())
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                aria-expanded="false"><i class="fa-sharp fa-solid fa-bell"></i> <span
                    class="note">{{$notification_count}}</span> </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                <h3 class="text-center mt-4">Notification</h3>
                <hr>

                <div class="suggested">
                     @forelse($notifications as $notification)

                        <div class="lorem1 notification_tr">

                            <p class="mb-3"><a href="{{$notification->url}}"> {{$notification->message}}</a></p>
                            <div class="row">
                                <div class="col-7">
                                    <span>{{date('M d,Y h:i A', strtotime($notification->created_at))}}</span>
                                </div>
                                <div class="col-5">
                                    <a href="javascript:;" data-href="{{ route('notification_destroy', $notification->id)  }}" class="mark_as_read">mark as read</a>
                                </div>
                            </div>

                            <hr>

                        </div>

                   
                     @empty

                <div class="lorem2">

                  
<span class="text-center">You're all caught up.</span>


</div>


                @endforelse
            </div>

              
                <div class="lorem text-center">
                    <a href="{{ route('notification_list') }}" class="text-center">
                        View All
                    </a>
                </div>


            </ul>
        </li>

    </ul>
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                @if(auth()->user()->profile_pic != null)
                <img src="{{ App\Helpers\Helpers::getImg(config('globals.USER_IMAGES_PATH'), auth()->user()->profile_pic, 'user') }}"
                    id="bss_image_preview" class="img-fluid rounded-circle" alt="Profile Pic"
                    style="width: 30px !important; height: 30px !important;object-fit: cover;">
                @else
                <i class="fas fa-user fa-fw"></i>
                @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                @if(auth()->user()->hasRole('consultant'))
                <li><a class="dropdown-item" href="{{ route('consultant.profile') }}">Account Settings</a></li>
                @elseif(auth()->user()->hasRole('admin'))
                <li><a class="dropdown-item" href="{{ route('admin.profile') }}">Account Settings</a></li>
                @elseif(auth()->user()->hasRole('patient'))
                <li><a class="dropdown-item" href="{{ route('patient.profile') }}">Account Settings</a></li>
                @endif
                <li><a class="dropdown-item" href="{{ route('change_password') }}">Update Password</a></li>
                <li>
                    <a href="{{ route('logout') }}" class="dropdown-item"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout <i class="fas fa-sign-out-alt"></i>
                    </a>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </ul>
        </li>
    </ul>
</nav>
