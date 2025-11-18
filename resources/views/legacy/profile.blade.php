@extends('layouts.legacy')

@section('title', 'Your Profile')

@section('content')
<style>
.profile-header {
  background: url('/legacy/assets/images/profile-page-header-img.jpg') no-repeat center;
  background-size: cover;
  padding: 80px 0;
  color: white;
  text-align: center;
  position: relative;
}
.profile-header::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.5);
}
.profile-header-content {
  position: relative;
  z-index: 1;
}
.profile-breadcrumb {
  margin-top: 10px;
  color: rgba(255,255,255,0.8);
}
.profile-container {
  padding: 60px 0;
  background: #f8f9fa;
}
.profile-card {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  overflow: hidden;
}
.profile-sidebar {
  background: #2c3e50;
  padding: 0;
}
.profile-menu {
  list-style: none;
  margin: 0;
  padding: 0;
}
.profile-menu li {
  border-bottom: 1px solid rgba(255,255,255,0.1);
}
.profile-menu li:last-child {
  border-bottom: none;
}
.profile-menu a {
  display: block;
  padding: 15px 20px;
  color: rgba(255,255,255,0.8);
  text-decoration: none;
  transition: all 0.3s;
}
.profile-menu a:hover,
.profile-menu a.active {
  background: #34495e;
  color: white;
  text-decoration: none;
}
.profile-content {
  padding: 30px;
}
.profile-avatar {
  text-align: center;
  margin-bottom: 30px;
}
.profile-avatar img {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  border: 4px solid #fff;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.profile-name {
  margin: 15px 0 5px;
  font-size: 24px;
  font-weight: 600;
  color: #2c3e50;
}
.profile-status {
  background: #27ae60;
  color: white;
  padding: 5px 15px;
  border-radius: 15px;
  font-size: 12px;
  font-weight: 600;
  display: inline-block;
}
.form-section {
  margin-bottom: 30px;
}
.form-section h3 {
  color: #2c3e50;
  margin-bottom: 20px;
  padding-bottom: 10px;
  border-bottom: 2px solid #e9ecef;
}
.form-group {
  margin-bottom: 20px;
}
.form-group label {
  font-weight: 600;
  color: #555;
  margin-bottom: 8px;
  display: block;
}
.form-control {
  border: 1px solid #ddd;
  border-radius: 5px;
  padding: 12px 15px;
  font-size: 14px;
  transition: border-color 0.3s;
}
.form-control:focus {
  border-color: #ff2e3c;
  outline: none;
  box-shadow: 0 0 0 2px rgba(255, 46, 60, 0.1);
}
.btn-save {
  background: #ff2e3c;
  color: white;
  border: none;
  padding: 12px 30px;
  border-radius: 5px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.3s;
}
.btn-save:hover {
  background: #e02631;
}
</style>

<div class="profile-header">
  <div class="profile-header-content">
    <h1>Your Profile</h1>
    <div class="profile-breadcrumb">
      <a href="{{ url('/') }}" style="color: rgba(255,255,255,0.8);">Home</a> 
      <span style="margin: 0 10px;">></span> 
      <span>Profile</span>
    </div>
  </div>
</div>

<div class="profile-container">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="profile-card">
          <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 profile-sidebar">
              <div class="profile-avatar">
                <img src="/legacy/assets/images/cat-profile.png" alt="Profile" class="img-responsive">
                <h4 class="profile-name">{{ session('fname', 'User') }}</h4>
                <span class="profile-status">Autospot</span>
              </div>
              
              <ul class="profile-menu">
                <li><a href="{{ url('profile') }}" class="active">Profile Settings</a></li>
                <li><a href="{{ url('update-password') }}">Update Password</a></li>
                <li><a href="{{ url('my-booking') }}">My Booking</a></li>
                <li><a href="{{ url('post-testimonial') }}">Post a Testimonial</a></li>
                <li><a href="{{ url('my-testimonials') }}">My Testimonials</a></li>
                <li><a href="{{ url('logout') }}">Sign Out</a></li>
              </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 profile-content">
              <div class="form-section">
                <h3>GENERAL SETTINGS</h3>
                
                @if(session('msg'))
                  <div class="alert alert-success">{{ session('msg') }}</div>
                @endif
                
                @if(session('error'))
                  <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                
                <form method="POST" action="{{ url('profile') }}">
                  @csrf
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Reg Date</label>
                        <input type="text" class="form-control" value="{{ $user->created_at ?? '2025-09-05 11:25:33' }}" readonly>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="fullname" class="form-control" value="{{ $user->FullName ?? '' }}" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->EmailId ?? '' }}" required>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ $user->ContactNo ?? '' }}">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Date of Birth (dd/mm/yyyy)</label>
                        <input type="date" name="dob" class="form-control" value="{{ $user->dob ?? '' }}">
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label>Your Address</label>
                    <textarea name="address" class="form-control" rows="4" placeholder="Enter your address">{{ $user->Address ?? '' }}</textarea>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Country</label>
                        <input type="text" name="country" class="form-control" value="{{ $user->Country ?? '' }}">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" class="form-control" value="{{ $user->City ?? '' }}">
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <button type="submit" class="btn-save">
                      <i class="fa fa-save"></i> Save Changes
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection