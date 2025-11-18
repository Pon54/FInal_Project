@extends('layouts.legacy')

@section('title', 'Home')

@section('content')
<!-- Banners -->
<section id="banner" class="banner-section">
<div class="container">
<div class="div_zindex">
<div class="row">
<div class="col-md-5 col-md-push-7">
<div class="banner_content">
<h1>&nbsp;</h1>
<p>&nbsp; </p>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- /Banners --> 


<!-- Resent Cat-->
<section class="section-padding gray-bg">
<div class="container">
<div class="section-header text-center">
<h2>Find the Best <span>CarForYou</span></h2>
<p>Nothing beats jet2 holiday</p>
</div>
<div class="row"> 
      
<!-- Nav tabs -->
<div class="recent-tab">
<ul class="nav nav-tabs" role="tablist">
<li role="presentation" class="active"><a href="#resentnewcar" role="tab" data-toggle="tab">New Car</a></li>
</ul>
</div>
<!-- Recently Listed New Cars -->
<div class="tab-content">
<div role="tabpanel" class="tab-pane active" id="resentnewcar">

@if(isset($vehicles) && count($vehicles))
  @foreach($vehicles as $result)
  <div class="col-list-3">
  <div class="recent-car-list">
  <div class="car-info-box"> <a href="{{ url('vehicle/' . ($result->id ?? 0)) }}">
    @php
      $img = $result->Vimage1 ?? '';
      $paths = [
        "legacy/admin-img/vehicleimages/{$img}",
        "legacy/admin/img/vehicleimages/{$img}",
        "legacy/img/vehicleimages/{$img}",
        "legacy/admin-img/{$img}",
        "legacy/admin/{$img}",
        "legacy/img/{$img}",
        "legacy/assets/images/{$img}",
      ];
      $src = asset('legacy/assets/images/car_755x430.png');
      foreach($paths as $p){ $full = public_path($p); if($img && file_exists($full)){ $src = asset($p); break; } }
    @endphp
    <img src="{{ $src }}" class="img-responsive" alt="image">
  </a>
  <ul>
  <li><i class="fa fa-car" aria-hidden="true"></i>{{ $result->FuelType ?? '' }}</li>
  <li><i class="fa fa-calendar" aria-hidden="true"></i>{{ $result->ModelYear ?? '' }} Model</li>
  <li><i class="fa fa-user" aria-hidden="true"></i>{{ $result->SeatingCapacity ?? '' }} seats</li>
  </ul>
  </div>
  <div class="car-title-m">
  <h6><a href="{{ url('vehicle/' . ($result->id ?? 0)) }}"> {{ $result->VehiclesTitle ?? '' }}</a></h6>
  <span class="price">${{ $result->PricePerDay ?? '' }} /Day</span> 
  </div>
  <div class="inventory_info_m">
  <p>{{ Str::limit($result->VehiclesOverview ?? '', 70) }}</p>
  </div>
  </div>
  </div>
  @endforeach
@else
  <p>wala pa mahuman.</p>
@endif
       
      </div>
    </div>
  </div>
</section>
<!-- /Resent Cat -->

@endsection

<style>
/* Car Card Hover Effects */
.col-list-3 .recent-car-list {
  transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  cursor: pointer;
  position: relative;
  background: #fff;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.col-list-3 .recent-car-list:hover {
  transform: translateY(-15px) scale(1.02);
  box-shadow: 0 25px 50px rgba(0,0,0,0.15);
  border: 2px solid #fa2837;
  z-index: 10;
}

/* Car Image Hover Effects */
.col-list-3 .car-info-box {
  position: relative;
  overflow: hidden;
  border-radius: 8px 8px 0 0;
}

.col-list-3 .car-info-box img {
  transition: all 0.5s ease;
  width: 100%;
  height: 220px;
  object-fit: cover;
}

.col-list-3 .recent-car-list:hover .car-info-box img {
  transform: scale(1.1);
  filter: brightness(1.1) contrast(1.05);
}

/* Overlay Effect on Hover */
.col-list-3 .car-info-box::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(45deg, rgba(250,40,55,0.7), rgba(250,40,55,0.3));
  opacity: 0;
  transition: all 0.4s ease;
}

.col-list-3 .recent-car-list:hover .car-info-box::after {
  opacity: 1;
}

/* Info List Hover Animation */
.col-list-3 .car-info-box ul {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: linear-gradient(transparent, rgba(0,0,0,0.8));
  color: white;
  padding: 15px;
  margin: 0;
  list-style: none;
  opacity: 0;
  transform: translateY(30px);
  transition: all 0.4s ease;
  z-index: 2;
}

.col-list-3 .recent-car-list:hover .car-info-box ul {
  opacity: 1;
  transform: translateY(0);
}

.col-list-3 .car-info-box ul li {
  color: white;
  font-size: 12px;
  margin: 4px 0;
  display: flex;
  align-items: center;
  animation: slideInLeft 0.6s ease forwards;
  opacity: 0;
}

.col-list-3 .recent-car-list:hover .car-info-box ul li {
  opacity: 1;
}

.col-list-3 .car-info-box ul li:nth-child(1) {
  animation-delay: 0.1s;
}

.col-list-3 .car-info-box ul li:nth-child(2) {
  animation-delay: 0.2s;
}

.col-list-3 .car-info-box ul li:nth-child(3) {
  animation-delay: 0.3s;
}

.col-list-3 .car-info-box ul li i {
  margin-right: 8px;
  width: 16px;
  color: #fa2837;
}

/* Title and Price Hover Effects */
.col-list-3 .car-title-m {
  padding: 20px 18px 10px;
  text-align: center;
  transition: all 0.3s ease;
}

.col-list-3 .car-title-m h6 {
  margin: 0 0 15px 0;
  font-size: 17px;
  font-weight: 700;
  line-height: 1.3;
  transition: all 0.3s ease;
}

.col-list-3 .recent-car-list:hover .car-title-m h6 {
  color: #fa2837;
  transform: translateY(-5px);
}

.col-list-3 .car-title-m h6 a {
  color: inherit;
  text-decoration: none;
  transition: all 0.3s ease;
}

.col-list-3 .car-title-m .price {
  color: #fa2837;
  font-size: 20px;
  font-weight: 800;
  margin: 12px 0;
  transition: all 0.4s ease;
  display: block;
}

.col-list-3 .recent-car-list:hover .car-title-m .price {
  transform: scale(1.2);
  color: #d71e2a;
  text-shadow: 0 2px 8px rgba(250,40,55,0.3);
}

/* Description Hover */
.col-list-3 .inventory_info_m {
  padding: 0 18px 20px;
  text-align: center;
}

.col-list-3 .inventory_info_m p {
  color: #666;
  font-size: 13px;
  line-height: 1.4;
  margin: 0;
  transition: all 0.3s ease;
}

.col-list-3 .recent-car-list:hover .inventory_info_m p {
  color: #333;
  transform: translateY(-3px);
}

/* Animations */
@keyframes slideInLeft {
  from {
    opacity: 0;
    transform: translateX(-20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

/* Click Effect */
.col-list-3 .recent-car-list:active {
  transform: translateY(-10px) scale(0.98);
  transition: all 0.1s ease;
}

/* Loading Animation for Cards */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.col-list-3 .recent-car-list {
  animation: fadeInUp 0.8s ease forwards;
}

.col-list-3:nth-child(1) .recent-car-list {
  animation-delay: 0.1s;
}

.col-list-3:nth-child(2) .recent-car-list {
  animation-delay: 0.2s;
}

.col-list-3:nth-child(3) .recent-car-list {
  animation-delay: 0.3s;
}

.col-list-3:nth-child(4) .recent-car-list {
  animation-delay: 0.4s;
}

.col-list-3:nth-child(5) .recent-car-list {
  animation-delay: 0.5s;
}

.col-list-3:nth-child(6) .recent-car-list {
  animation-delay: 0.6s;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
  .col-list-3 .recent-car-list:hover {
    transform: translateY(-8px) scale(1.01);
  }
  
  .col-list-3 .car-info-box img {
    height: 180px;
  }
  
  .col-list-3 .car-title-m {
    padding: 15px 12px 10px;
  }
}
</style>
