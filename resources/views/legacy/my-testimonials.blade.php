@extends('layouts.legacy')

@section('title', 'My Testimonials')

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
.content-section {
  margin-bottom: 30px;
}
.content-section h3 {
  color: #2c3e50;
  margin-bottom: 20px;
  padding-bottom: 10px;
  border-bottom: 2px solid #e9ecef;
  text-transform: uppercase;
  font-weight: 700;
}
.no-testimonials {
  text-align: center;
  padding: 60px 20px;
  color: #666;
  font-size: 18px;
  font-weight: 500;
}
.testimonial-card {
  background: white;
  border: 1px solid #e9ecef;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 20px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.05);
  transition: box-shadow 0.3s;
}
.testimonial-card:hover {
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.testimonial-content {
  font-size: 15px;
  line-height: 1.6;
  color: #555;
  margin-bottom: 15px;
  font-style: italic;
}
.testimonial-meta {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 15px;
  border-top: 1px solid #e9ecef;
}
.testimonial-date {
  color: #999;
  font-size: 13px;
}
.testimonial-status {
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
}
.status-approved {
  background: #d4edda;
  color: #155724;
}
.status-pending {
  background: #fff3cd;
  color: #856404;
}
.status-rejected {
  background: #f8d7da;
  color: #721c24;
}
</style>

<div class="profile-header">
  <div class="profile-header-content">
    <h1>My Testimonials</h1>
    <div class="profile-breadcrumb">
      <a href="{{ url('/') }}" style="color: rgba(255,255,255,0.8);">Home</a> 
      <span style="margin: 0 10px;">></span> 
      <span>My Testimonials</span>
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
                <li><a href="{{ url('profile') }}">Profile Settings</a></li>
                <li><a href="{{ url('update-password') }}">Update Password</a></li>
                <li><a href="{{ url('my-booking') }}">My Booking</a></li>
                <li><a href="{{ url('post-testimonial') }}">Post a Testimonial</a></li>
                <li><a href="{{ url('my-testimonials') }}" class="active">My Testimonials</a></li>
                <li><a href="{{ url('logout') }}">Sign Out</a></li>
              </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 profile-content">
              <div class="content-section">
                <h3>My Testimonials</h3>
                
                @if(session('msg'))
                  <div class="alert alert-success">{{ session('msg') }}</div>
                @endif
                
                @if(session('error'))
                  <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                
                @if($testimonials->count() > 0)
                  @foreach($testimonials as $testimonial)
                  <div class="testimonial-card">
                    <div class="testimonial-content">
                      "{{ $testimonial->Testimonial ?? $testimonial->testimonial }}"
                    </div>
                    <div class="testimonial-meta">
                      <div class="testimonial-date">
                        Posted on {{ $testimonial->created_at ? $testimonial->created_at->format('M d, Y') : ($testimonial->PostingDate ? date('M d, Y', strtotime($testimonial->PostingDate)) : 'N/A') }}
                      </div>
                      <div>
                        @php
                          $status = $testimonial->status ?? $testimonial->Status ?? 0;
                          $statusClass = 'status-pending';
                          $statusText = 'Pending Review';
                          
                          if ($status == 1) {
                            $statusClass = 'status-approved';
                            $statusText = 'Approved';
                          } elseif ($status == 2) {
                            $statusClass = 'status-rejected';
                            $statusText = 'Rejected';
                          }
                        @endphp
                        <span class="testimonial-status {{ $statusClass }}">{{ $statusText }}</span>
                      </div>
                    </div>
                  </div>
                  @endforeach
                @else
                  <div class="no-testimonials">
                    <i class="fa fa-comment-o" style="font-size: 48px; color: #ddd; margin-bottom: 20px;"></i>
                    <p>You haven't posted any testimonials yet.</p>
                    <p><a href="{{ url('post-testimonial') }}" style="color: #ff2e3c; text-decoration: none;">Write your first testimonial</a></p>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection