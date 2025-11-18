@extends('layouts.legacy')

@section('title', 'My Booking')

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
.no-bookings {
  text-align: center;
  padding: 60px 20px;
  color: #ff2e3c;
  font-size: 18px;
  font-weight: 600;
}
.booking-table {
  width: 100%;
  border-collapse: collapse;
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.booking-table thead {
  background: #2c3e50;
  color: white;
}
.booking-table th,
.booking-table td {
  padding: 15px;
  text-align: left;
  border-bottom: 1px solid #eee;
}
.booking-table th {
  font-weight: 600;
  text-transform: uppercase;
  font-size: 12px;
  letter-spacing: 1px;
}
.booking-table tr:hover {
  background: #f8f9fa;
}
.booking-status {
  padding: 5px 12px;
  border-radius: 15px;
  font-size: 11px;
  font-weight: 600;
  text-transform: uppercase;
}
.status-confirmed {
  background: #d4edda;
  color: #155724;
}
.status-pending {
  background: #fff3cd;
  color: #856404;
}
.status-cancelled {
  background: #f8d7da;
  color: #721c24;
}
.booking-id {
  font-weight: 600;
  color: #2c3e50;
}
.vehicle-info {
  font-weight: 500;
}
.booking-dates {
  color: #666;
  font-size: 13px;
}
</style>

<div class="profile-header">
  <div class="profile-header-content">
    <h1>My Booking</h1>
    <div class="profile-breadcrumb">
      <a href="{{ url('/') }}" style="color: rgba(255,255,255,0.8);">Home</a> 
      <span style="margin: 0 10px;">></span> 
      <span>My Booking</span>
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
                <li><a href="{{ url('my-booking') }}" class="active">My Booking</a></li>
                <li><a href="{{ url('post-testimonial') }}">Post a Testimonial</a></li>
                <li><a href="{{ url('my-testimonials') }}">My Testimonials</a></li>
                <li><a href="{{ url('logout') }}">Sign Out</a></li>
              </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 profile-content">
              <div class="content-section">
                <h3>My Bookings</h3>
                
                @if(session('msg'))
                  <div class="alert alert-success">{{ session('msg') }}</div>
                @endif
                
                @if(session('error'))
                  <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                
                @if($bookings->count() > 0)
                  <div class="table-responsive">
                    <table class="booking-table">
                      <thead>
                        <tr>
                          <th>Booking ID</th>
                          <th>Vehicle</th>
                          <th>From Date</th>
                          <th>To Date</th>
                          <th>Message</th>
                          <th>Status</th>
                          <th>Booking Date</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                          <td>
                            <span class="booking-id">#{{ $booking->BookingNumber ?? $booking->id }}</span>
                          </td>
                          <td>
                            <div class="vehicle-info">
                              {{ $booking->vehicle->BrandName ?? 'N/A' }} - {{ $booking->vehicle->VehiclesTitle ?? 'Vehicle' }}
                            </div>
                          </td>
                          <td>
                            <div class="booking-dates">{{ $booking->FromDate ?? 'N/A' }}</div>
                          </td>
                          <td>
                            <div class="booking-dates">{{ $booking->ToDate ?? 'N/A' }}</div>
                          </td>
                          <td>{{ Str::limit($booking->message ?? $booking->Message ?? 'No message', 50) }}</td>
                          <td>
                            @php
                              $status = $booking->Status ?? $booking->status ?? 0;
                              $statusClass = 'status-pending';
                              $statusText = 'Pending';
                              
                              if ($status == 1) {
                                $statusClass = 'status-confirmed';
                                $statusText = 'Confirmed';
                              } elseif ($status == 2) {
                                $statusClass = 'status-cancelled';
                                $statusText = 'Cancelled';
                              }
                            @endphp
                            <span class="booking-status {{ $statusClass }}">{{ $statusText }}</span>
                          </td>
                          <td>
                            <div class="booking-dates">
                              {{ $booking->created_at ? $booking->created_at->format('M d, Y') : ($booking->PostingDate ?? 'N/A') }}
                            </div>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                @else
                  <div class="no-bookings">
                    No booking yet
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