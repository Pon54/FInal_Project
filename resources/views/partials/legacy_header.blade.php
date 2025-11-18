@php
use Illuminate\Support\Facades\Auth;
@endphp

<header>
  <div class="default-header">
    <div class="container">
      <div class="row">
        <div class="col-sm-3 col-md-2">
          <div class="logo"> 
            <a href="{{ url('/') }}">
              <img src="{{ asset('legacy/assets/images/logo.png') }}" alt="Car Rental Portal"/>
            </a> 
          </div>
        </div>
        <div class="col-sm-9 col-md-10">
          <div class="header_info">
            <div class="header_widgets">
              <div class="circle_icon"> <i class="fa fa-envelope" aria-hidden="true"></i> </div>
              <p class="uppercase_text">FOR SUPPORT MAIL US :</p>
              <span>psy@gmail.com</span>
            </div>
            <div class="header_widgets">
              <div class="circle_icon"> <i class="fa fa-phone" aria-hidden="true"></i> </div>
              <p class="uppercase_text">SERVICE HELPLINE CALL US:</p>
              <span>09057193245</span>
            </div>
            <div class="social-follow">
              <!-- Social icons can be added here -->
            </div>

            @guest
            <div class="login_btn"> 
              <a href="#loginform" class="btn btn-xs uppercase" data-toggle="modal" data-dismiss="modal">Login / Register</a> 
            </div>
            @else
            <div class="login_btn">
              <span class="welcome-text">Welcome To Car rental portal</span>
            </div>
            @endguest
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Navigation -->
  <nav id="navigation_bar" class="navbar navbar-default">
    <div class="container">
      <div class="navbar-header">
        <button id="menu_slide" data-target="#navigation" aria-expanded="false" data-toggle="collapse" class="navbar-toggle collapsed" type="button"> 
          <span class="sr-only">Toggle navigation</span> 
          <span class="icon-bar"></span> 
          <span class="icon-bar"></span> 
          <span class="icon-bar"></span> 
        </button>
      </div>
      <div class="header_wrap">
        <div class="user_login">
          <ul>
            <li class="dropdown"> 
              <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-user-circle" aria-hidden="true"></i> 
                @if(Auth::check())
                  {{ Auth::user()->FullName ?? Auth::user()->name ?? 'User' }}
                @endif
                <i class="fa fa-angle-down" aria-hidden="true"></i>
              </a>
              <ul class="dropdown-menu">
                @if(Auth::check())
                  <li><a href="{{ url('/profile') }}">Profile Settings</a></li>
                  <li><a href="{{ url('/update-password') }}">Update Password</a></li>
                  <li><a href="{{ url('/my-booking') }}">My Booking</a></li>
                  <li><a href="{{ url('/post-testimonial') }}">Post a Testimonial</a></li>
                  <li><a href="{{ url('/my-testimonials') }}">My Testimonial</a></li>
                  <li><a href="{{ url('/logout') }}">Sign Out</a></li>
                @else
                  <li><a href="#loginform" data-toggle="modal">Login</a></li>
                  <li><a href="#signupform" data-toggle="modal">Register</a></li>
                @endif
              </ul>
            </li>
          </ul>
        </div>
        
        <div class="header_search">
          <div id="search_toggle"><i class="fa fa-search" aria-hidden="true"></i></div>
          <form action="{{ url('/search') }}" method="post" id="header-search-form">
            @csrf
            <input type="text" placeholder="Search..." name="searchdata" class="form-control" required="true">
            <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
          </form>
        </div>
      </div>
      
      <div class="collapse navbar-collapse" id="navigation">
        <ul class="nav navbar-nav">
          <li><a href="{{ url('/') }}">Home</a></li>
          <li><a href="{{ url('/page?type=aboutus') }}">About Us</a></li>
          <li><a href="{{ url('/car-listing') }}">Car Listing</a></li>
          <li><a href="{{ url('/page?type=faqs') }}">FAQs</a></li>
          <li><a href="{{ url('/contact-us') }}">Contact Us</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- Navigation end --> 
</header>

<!-- Login/Registration Modal -->
@include('partials.modals.login')
@include('partials.modals.registration')
@include('partials.modals.forgotpassword')