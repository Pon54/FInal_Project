@extends('admin.layouts.master')

@section('title','Dashboard')

@section('content')
<style>
.dashboard-card {
    border-radius: 8px;
    padding: 30px 20px;
    margin-bottom: 20px;
    color: white;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.dashboard-card h2 {
    font-size: 48px;
    font-weight: bold;
    margin: 0;
}
.dashboard-card h4 {
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin: 10px 0 15px 0;
}
.dashboard-card .full-detail {
    color: rgba(255,255,255,0.8);
    font-size: 12px;
    text-transform: uppercase;
    text-decoration: none;
    font-weight: 500;
}
.dashboard-card .full-detail:hover {
    color: white;
    text-decoration: none;
}
.bg-blue { background: linear-gradient(135deg, #4a90e2, #357abd); }
.bg-green { background: linear-gradient(135deg, #7ed321, #5cb85c); }
.bg-cyan { background: linear-gradient(135deg, #50e3c2, #4dd0e1); }
.bg-orange { background: linear-gradient(135deg, #f5a623, #ff9500); }
</style>

<div class="page-title">
    <h1>Dashboard</h1>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="dashboard-card bg-blue">
            <h2>{{ $usersCount ?? 5 }}</h2>
            <h4>REG USERS</h4>
            <a href="{{ route('admin.users.index') }}" class="full-detail">FULL DETAIL →</a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-card bg-green">
            <h2>{{ $vehiclesCount ?? 8 }}</h2>
            <h4>LISTED VEHICLES</h4>
            <a href="{{ route('admin.vehicles.index') }}" class="full-detail">FULL DETAIL →</a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-card bg-cyan">
            <h2>{{ $bookingsCount ?? 1 }}</h2>
            <h4>TOTAL BOOKINGS</h4>
            <a href="{{ route('admin.bookings.index') }}" class="full-detail">FULL DETAIL →</a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-card bg-orange">
            <h2>{{ $brandsCount ?? 6 }}</h2>
            <h4>LISTED BRANDS</h4>
            <a href="{{ route('admin.brands.index') }}" class="full-detail">FULL DETAIL →</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="dashboard-card bg-blue">
            <h2>{{ $subscribersCount ?? 2 }}</h2>
            <h4>SUBSCIBERS</h4>
            <a href="{{ route('admin.subscribers.index') }}" class="full-detail">FULL DETAIL →</a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-card bg-green">
            <h2>{{ $queriesCount ?? 2 }}</h2>
            <h4>QUERIES</h4>
            <a href="{{ route('admin.contactqueries.index') }}" class="full-detail">FULL DETAIL →</a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="dashboard-card bg-cyan">
            <h2>{{ $testimonialsCount ?? 0 }}</h2>
            <h4>TESTIMONIALS</h4>
            <a href="{{ route('admin.testimonials.index') }}" class="full-detail">FULL DETAIL →</a>
        </div>
    </div>
    <div class="col-md-3">
        <!-- Empty for now -->
    </div>
</div>

@endsection
