@extends('layouts.legacy')

@section('title', 'Vehicle Details')

@section('content')
<style>
.vehicle-image-slider {
  position: relative;
  max-width: 900px;
  margin: 0 auto;
  background: #f8f9fa;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.slider-container {
  position: relative;
  width: 100%;
  height: 400px;
  overflow: hidden;
}
.slider-slide {
  display: none;
  width: 100%;
  height: 100%;
}
.slider-slide.active {
  display: block;
}
.slider-slide img {
  width: 100%;
  height: 400px;
  object-fit: cover;
  object-position: center;
}
.slider-nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(0,0,0,0.7);
  color: white;
  border: none;
  padding: 15px 20px;
  cursor: pointer;
  font-size: 18px;
  border-radius: 50%;
  transition: all 0.3s ease;
}
.slider-nav:hover {
  background: rgba(0,0,0,0.9);
  transform: translateY(-50%) scale(1.1);
}
.slider-prev { left: 20px; }
.slider-next { right: 20px; }
.slider-dots {
  text-align: center;
  padding: 20px;
  background: #fff;
}
.slider-dot {
  display: inline-block;
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: #ddd;
  margin: 0 5px;
  cursor: pointer;
  transition: all 0.3s ease;
}
.slider-dot.active {
  background: #333;
  transform: scale(1.2);
}
.slider-counter {
  position: absolute;
  top: 20px;
  right: 20px;
  background: rgba(0,0,0,0.7);
  color: white;
  padding: 8px 15px;
  border-radius: 20px;
  font-size: 14px;
}

/* Enhanced Vehicle Image Slider */
.vehicle-image-slider {
  position: relative;
  max-width: 100%;
  margin: 0 auto;
  background: #000;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 8px 32px rgba(0,0,0,0.3);
}
.slider-container {
  position: relative;
  width: 100%;
  height: 400px;
  overflow: hidden;
  cursor: grab;
}
.slider-container:active {
  cursor: grabbing;
}
.slider-slide {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  transform: translateX(100%);
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
.slider-slide.active {
  opacity: 1;
  transform: translateX(0);
}
.slider-slide.prev {
  transform: translateX(-100%);
}
.slider-slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  user-select: none;
  -webkit-user-select: none;
  pointer-events: none;
}
.slider-nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(0,0,0,0.7);
  color: white;
  border: none;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  font-size: 18px;
  cursor: pointer;
  transition: all 0.3s ease;
  z-index: 10;
  display: flex;
  align-items: center;
  justify-content: center;
}
.slider-nav:hover {
  background: rgba(0,0,0,0.9);
  transform: translateY(-50%) scale(1.1);
}
.slider-prev {
  left: 15px;
}
.slider-next {
  right: 15px;
}
.slider-counter {
  position: absolute;
  top: 15px;
  right: 15px;
  background: rgba(0,0,0,0.7);
  color: white;
  padding: 8px 12px;
  border-radius: 20px;
  font-size: 14px;
  z-index: 10;
}
.slider-dots {
  display: flex;
  justify-content: center;
  padding: 15px 0;
  background: linear-gradient(135deg, #f5f5f5, #e0e0e0);
}
.slider-dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: #ccc;
  margin: 0 5px;
  cursor: pointer;
  transition: all 0.3s ease;
}
.slider-dot.active {
  background: #f44336;
  transform: scale(1.2);
}
.slider-dot:hover {
  background: #f44336;
  opacity: 0.7;
}

/* Vehicle Overview Styles */
.vehicle-details {
  padding: 20px;
}
.vehicle-details h4 {
  color: #333;
  margin-bottom: 20px;
  border-bottom: 2px solid #f44336;
  padding-bottom: 10px;
}
.vehicle-specs {
  list-style: none;
  padding: 0;
}
.vehicle-specs li {
  padding: 8px 0;
  border-bottom: 1px solid #eee;
}
.vehicle-specs li:last-child {
  border-bottom: none;
}
.vehicle-description {
  margin-top: 20px;
  padding: 15px;
  background: #f8f9fa;
  border-radius: 8px;
}
.vehicle-description p {
  margin-bottom: 10px;
  line-height: 1.6;
}

/* Accessories Styles */
.accessories-grid {
  padding: 20px;
}
.accessories-grid h4 {
  color: #333;
  margin-bottom: 20px;
  border-bottom: 2px solid #f44336;
  padding-bottom: 10px;
}
.accessory-item {
  display: flex;
  align-items: center;
  padding: 12px 15px;
  margin-bottom: 10px;
  border-radius: 8px;
  transition: all 0.3s ease;
}
.accessory-item.available {
  background: #e8f5e8;
  border-left: 4px solid #4caf50;
}
.accessory-item.not-available {
  background: #fff3e0;
  border-left: 4px solid #ff9800;
}
.accessory-item i {
  margin-right: 10px;
  font-size: 16px;
}
.accessory-item.available i {
  color: #4caf50;
}
.accessory-item.not-available i {
  color: #ff9800;
}
.accessory-item span {
  font-weight: 500;
  color: #333;
}

/* Similar Cars Hover Effects */
.similar_cars .product-listing-m {
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
  cursor: pointer;
  position: relative;
  background: #fff;
  border: 1px solid #e0e0e0;
  height: 100%;
  display: flex;
  flex-direction: column;
}

.similar_cars .product-listing-m:hover {
  transform: translateY(-12px) scale(1.03);
  box-shadow: 0 20px 40px rgba(0,0,0,0.25);
  border: 2px solid #f44336;
  z-index: 10;
}

.similar_cars .product-listing-img {
  position: relative;
  overflow: hidden;
}

.similar_cars .product-listing-img img {
  transition: all 0.4s ease;
  width: 100%;
  height: 220px;
  object-fit: cover;
  filter: brightness(0.95);
}

.similar_cars .product-listing-m:hover .product-listing-img img {
  transform: scale(1.15);
  filter: brightness(1.1) contrast(1.1);
}

.similar_cars .product-listing-img::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(45deg, rgba(244,67,54,0.8), rgba(244,67,54,0.4));
  opacity: 0;
  transition: all 0.3s ease;
}

.similar_cars .product-listing-m:hover .product-listing-img::after {
  opacity: 1;
}

.similar_cars .product-listing-content {
  padding: 20px;
  position: relative;
  z-index: 2;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  text-align: center;
}

.similar_cars .product-listing-content h5 {
  transition: all 0.3s ease;
  margin-bottom: 15px;
  font-size: 16px;
  font-weight: 700;
  line-height: 1.3;
  min-height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.similar_cars .product-listing-m:hover .product-listing-content h5 {
  color: #f44336;
  transform: translateY(-3px);
}

.similar_cars .product-listing-content h5 a {
  text-decoration: none;
  color: inherit;
  font-weight: 600;
}

.similar_cars .list-price {
  font-size: 20px;
  font-weight: 800;
  color: #f44336;
  margin: 12px 0;
  transition: all 0.3s ease;
  text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.similar_cars .product-listing-m:hover .list-price {
  transform: scale(1.15);
  color: #d32f2f;
  text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.similar_cars .fuel-type,
.similar_cars .seating {
  font-size: 13px;
  color: #666;
  margin: 5px 0;
  display: flex;
  align-items: center;
  transition: all 0.3s ease;
}

.similar_cars .fuel-type i,
.similar_cars .seating i {
  margin-right: 8px;
  width: 16px;
}

.similar_cars .product-listing-m:hover .fuel-type,
.similar_cars .product-listing-m:hover .seating {
  color: #333;
  transform: translateX(3px);
}

/* Similar Cars Grid Responsive */
.similar_cars .row {
  margin-left: -15px;
  margin-right: -15px;
  display: flex;
  flex-wrap: wrap;
  align-items: stretch;
}

.similar_cars .grid_listing {
  padding-left: 15px;
  padding-right: 15px;
  margin-bottom: 30px;
  display: flex;
}

.similar_cars .grid_listing .product-listing-m {
  width: 100%;
}

/* Similar Cars Section Styling */
.similar_cars {
  padding: 40px 0;
}

.similar_cars h3 {
  color: #333;
  font-size: 28px;
  font-weight: 700;
  margin-bottom: 40px;
  text-align: center;
  position: relative;
}

.similar_cars h3:after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);
  width: 60px;
  height: 3px;
  background: linear-gradient(135deg, #f44336, #d32f2f);
  border-radius: 2px;
}

/* Click Animation */
.similar_cars .product-listing-m:active {
  transform: translateY(-8px) scale(0.98);
  transition: all 0.15s ease;
}

/* Loading Animation */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(40px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.similar_cars .product-listing-m {
  animation: fadeInUp 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
  opacity: 0;
}

.similar_cars .grid_listing:nth-child(1) .product-listing-m {
  animation-delay: 0.1s;
}

.similar_cars .grid_listing:nth-child(2) .product-listing-m {
  animation-delay: 0.2s;
}

.similar_cars .grid_listing:nth-child(3) .product-listing-m {
  animation-delay: 0.3s;
}

.similar_cars .grid_listing:nth-child(4) .product-listing-m {
  animation-delay: 0.4s;
}

/* Equal Height Cards */
.similar_cars .row {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
}

.similar_cars .grid_listing {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .similar_cars .grid_listing {
    margin-bottom: 20px;
  }
  
  .similar_cars .product-listing-img {
    height: 180px;
  }
  
  .similar_cars .product-listing-content {
    padding: 15px 12px;
  }
  
  .similar_cars .product-listing-content h5 {
    font-size: 15px;
    min-height: 40px;
  }
  
  .similar_cars .list-price {
    font-size: 18px;
  }
}
  opacity: 0;
}

.similar_cars .grid_listing:nth-child(1) .product-listing-m {
  animation-delay: 0.1s;
}

.similar_cars .grid_listing:nth-child(2) .product-listing-m {
  animation-delay: 0.2s;
}

.similar_cars .grid_listing:nth-child(3) .product-listing-m {
  animation-delay: 0.3s;
}

.similar_cars .grid_listing:nth-child(4) .product-listing-m {
  animation-delay: 0.4s;
}

/* Similar Cars Section Heading */
.similar_cars h3 {
  color: #333;
  font-size: 28px;
  font-weight: 700;
  margin-bottom: 30px;
  text-align: center;
  position: relative;
}

.similar_cars h3:after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);
  width: 60px;
  height: 3px;
  background: linear-gradient(135deg, #007bff, #0056b3);
  border-radius: 2px;
}
</style>

<section id="listing_img_slider">
  @if($vehicle)
    @php
      $images = array_filter([$vehicle->Vimage1,$vehicle->Vimage2,$vehicle->Vimage3,$vehicle->Vimage4,$vehicle->Vimage5]);
    @endphp
    @if(count($images) > 0)
    <div class="vehicle-image-slider">
      <div class="slider-container">
        <div class="slider-counter">
          <span id="current-slide">1</span> / <span id="total-slides">{{ count($images) }}</span>
        </div>
        
        @foreach($images as $index => $img)
          @php
            $paths = [
              "legacy/admin/img/vehicleimages/{$img}",
              "legacy/admin-img/vehicleimages/{$img}",
              "legacy/img/vehicleimages/{$img}",
              "legacy/admin-img/{$img}",
              "legacy/admin/{$img}",
              "legacy/img/{$img}",
              "legacy/assets/images/{$img}",
            ];
            $src = asset('legacy/assets/images/car_755x430.png');
            foreach($paths as $p){ $full = public_path($p); if($img && file_exists($full)){ $src = asset($p); break; } }
          @endphp
          <div class="slider-slide {{ $index == 0 ? 'active' : '' }}" data-slide="{{ $index + 1 }}">
            <img src="{{ $src }}" alt="Vehicle Image {{ $index + 1 }}">
          </div>
        @endforeach
        
        @if(count($images) > 1)
        <button class="slider-nav slider-prev" onclick="changeSlide(-1)" title="Previous Image">
          <i class="fa fa-chevron-left"></i>
        </button>
        <button class="slider-nav slider-next" onclick="changeSlide(1)" title="Next Image">
          <i class="fa fa-chevron-right"></i>
        </button>
        @endif
      </div>
      
      @if(count($images) > 1)
      <div class="slider-dots">
        @foreach($images as $index => $img)
          <span class="slider-dot {{ $index == 0 ? 'active' : '' }}" onclick="currentSlide({{ $index + 1 }})"></span>
        @endforeach
      </div>
      @endif
    </div>
    
    <script>
    let slideIndex = 1;
    const totalSlides = {{ count($images) }};
    let isDragging = false;
    let startX = 0;
    let currentX = 0;
    let autoSlideInterval;
    
    function changeSlide(direction) {
      slideIndex += direction;
      if (slideIndex > totalSlides) slideIndex = 1;
      if (slideIndex < 1) slideIndex = totalSlides;
      showSlide(slideIndex);
      resetAutoSlide();
    }
    
    function currentSlide(index) {
      slideIndex = index;
      showSlide(slideIndex);
      resetAutoSlide();
    }
    
    function showSlide(index) {
      const slides = document.querySelectorAll('.slider-slide');
      const dots = document.querySelectorAll('.slider-dot');
      
      slides.forEach((slide, i) => {
        slide.classList.remove('active', 'prev');
        if (i === index - 1) {
          slide.classList.add('active');
        } else if (i < index - 1) {
          slide.classList.add('prev');
        }
      });
      
      dots.forEach(dot => dot.classList.remove('active'));
      if (dots.length > 0) dots[index - 1].classList.add('active');
      
      document.getElementById('current-slide').textContent = index;
    }
    
    function resetAutoSlide() {
      if (autoSlideInterval) clearInterval(autoSlideInterval);
      autoSlideInterval = setInterval(() => {
        if (totalSlides > 1 && !isDragging) changeSlide(1);
      }, 6000);
    }
    
    // Drag functionality
    const sliderContainer = document.querySelector('.slider-container');
    
    // Mouse events
    sliderContainer.addEventListener('mousedown', handleStart);
    sliderContainer.addEventListener('mousemove', handleMove);
    sliderContainer.addEventListener('mouseup', handleEnd);
    sliderContainer.addEventListener('mouseleave', handleEnd);
    
    // Touch events
    sliderContainer.addEventListener('touchstart', handleStart);
    sliderContainer.addEventListener('touchmove', handleMove);
    sliderContainer.addEventListener('touchend', handleEnd);
    
    function handleStart(e) {
      isDragging = true;
      startX = e.type === 'mousedown' ? e.clientX : e.touches[0].clientX;
      sliderContainer.style.cursor = 'grabbing';
    }
    
    function handleMove(e) {
      if (!isDragging) return;
      e.preventDefault();
      currentX = e.type === 'mousemove' ? e.clientX : e.touches[0].clientX;
    }
    
    function handleEnd(e) {
      if (!isDragging) return;
      isDragging = false;
      sliderContainer.style.cursor = 'grab';
      
      const diffX = startX - currentX;
      const threshold = 50; // Minimum drag distance to trigger slide change
      
      if (Math.abs(diffX) > threshold) {
        if (diffX > 0) {
          // Dragged left, go to next slide
          changeSlide(1);
        } else {
          // Dragged right, go to previous slide
          changeSlide(-1);
        }
      }
    }
    
    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowLeft') changeSlide(-1);
      if (e.key === 'ArrowRight') changeSlide(1);
    });
    
    // Initialize auto-slide
    resetAutoSlide();
    </script>
    @else
    <div class="vehicle-image-slider">
      <div class="slider-container">
        <div class="slider-slide active">
          <img src="{{ asset('legacy/assets/images/car_755x430.png') }}" alt="No Image Available">
        </div>
      </div>
    </div>
    @endif
  @else
    <div class="vehicle-image-slider">
      <div class="slider-container">
        <div class="slider-slide active">
          <img src="{{ asset('legacy/assets/images/car_755x430.png') }}" alt="Vehicle not found">
        </div>
      </div>
    </div>
  @endif
</section>

<section class="listing-detail">
  <div class="container">
    <div class="listing_detail_head row">
      <div class="col-md-9">
        <h2>{{ $vehicle->BrandName ?? 'Brand' }} , {{ $vehicle->VehiclesTitle ?? 'Title' }}</h2>
      </div>
      <div class="col-md-3">
        <div class="price_info">
          <p>${{ $vehicle->PricePerDay ?? '0' }} </p>Per Day
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-9">
        <div class="main_features">
          <ul>
            <li> <i class="fa fa-calendar" aria-hidden="true"></i>
              <h5>{{ $vehicle->ModelYear ?? '' }}</h5>
              <p>Reg.Year</p>
            </li>
            <li> <i class="fa fa-cogs" aria-hidden="true"></i>
              <h5>{{ $vehicle->FuelType ?? '' }}</h5>
              <p>Fuel Type</p>
            </li>
            <li> <i class="fa fa-user-plus" aria-hidden="true"></i>
              <h5>{{ $vehicle->SeatingCapacity ?? '' }}</h5>
              <p>Seats</p>
            </li>
          </ul>
        </div>
        <div class="listing_more_info">
          <div class="listing_detail_wrap"> 
            <ul class="nav nav-tabs gray-bg" role="tablist">
              <li role="presentation" class="active"><a href="#vehicle-overview " aria-controls="vehicle-overview" role="tab" data-toggle="tab">Vehicle Overview </a></li>
              <li role="presentation"><a href="#accessories" aria-controls="accessories" role="tab" data-toggle="tab">Accessories</a></li>
            </ul>
            <div class="tab-content"> 
              <div role="tabpanel" class="tab-pane active" id="vehicle-overview">
                @if(!empty($vehicle->VehiclesOverview))
                  <p>{{ $vehicle->VehiclesOverview }}</p>
                @else
                  <div class="vehicle-details">
                    <h4>{{ $vehicle->BrandName ?? 'Brand' }} {{ $vehicle->VehiclesTitle ?? 'Vehicle' }} Details</h4>
                    <div class="row">
                      <div class="col-md-6">
                        <ul class="vehicle-specs">
                          <li><strong>Brand:</strong> {{ $vehicle->BrandName ?? 'N/A' }}</li>
                          <li><strong>Model:</strong> {{ $vehicle->VehiclesTitle ?? 'N/A' }}</li>
                          <li><strong>Year:</strong> {{ $vehicle->ModelYear ?? 'N/A' }}</li>
                          <li><strong>Fuel Type:</strong> {{ $vehicle->FuelType ?? 'N/A' }}</li>
                        </ul>
                      </div>
                      <div class="col-md-6">
                        <ul class="vehicle-specs">
                          <li><strong>Seating Capacity:</strong> {{ $vehicle->SeatingCapacity ?? 'N/A' }} persons</li>
                          <li><strong>Price Per Day:</strong> ${{ $vehicle->PricePerDay ?? 'N/A' }}</li>
                          <li><strong>Registration Date:</strong> {{ $vehicle->RegDate ? date('M d, Y', strtotime($vehicle->RegDate)) : 'N/A' }}</li>
                        </ul>
                      </div>
                    </div>
                    <div class="vehicle-description">
                      <p><strong>About this vehicle:</strong></p>
                      <p>This {{ $vehicle->BrandName ?? 'vehicle' }} {{ $vehicle->VehiclesTitle ?? '' }} is a {{ $vehicle->ModelYear ?? 'modern' }} model featuring {{ strtolower($vehicle->FuelType ?? 'efficient') }} engine and comfortable seating for {{ $vehicle->SeatingCapacity ?? 'multiple' }} passengers. Perfect for city drives and long trips with a competitive daily rental rate.</p>
                    </div>
                  </div>
                @endif
              </div>
              <div role="tabpanel" class="tab-pane" id="accessories"> 
                <div class="accessories-grid">
                  <h4>Vehicle Features & Accessories</h4>
                  <div class="row">
                    @php
                      $accessories = [
                        'AirConditioner' => 'Air Conditioner',
                        'AntiLockBrakingSystem' => 'ABS (Anti-Lock Braking)',
                        'PowerSteering' => 'Power Steering', 
                        'PowerWindows' => 'Power Windows',
                        'CDPlayer' => 'CD Player',
                        'LeatherSeats' => 'Leather Seats',
                        'CentralLocking' => 'Central Locking',
                        'PowerDoorLocks' => 'Power Door Locks',
                        'BrakeAssist' => 'Brake Assist',
                        'DriverAirbag' => 'Driver Airbag',
                        'PassengerAirbag' => 'Passenger Airbag',
                        'CrashSensor' => 'Crash Sensor'
                      ];
                    @endphp
                    @foreach($accessories as $field => $label)
                      <div class="col-md-6 col-sm-12">
                        <div class="accessory-item {{ !empty($vehicle->$field) ? 'available' : 'not-available' }}">
                          <i class="fa {{ !empty($vehicle->$field) ? 'fa-check-circle' : 'fa-times-circle' }}" aria-hidden="true"></i>
                          <span>{{ $label }}</span>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <aside class="col-md-3">
        <div class="share_vehicle">
          <p>Share: <a href="#"><i class="fa fa-facebook-square" aria-hidden="true"></i></a> <a href="#"><i class="fa fa-twitter-square" aria-hidden="true"></i></a> <a href="#"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a> <a href="#"><i class="fa fa-google-plus-square" aria-hidden="true"></i></a> </p>
        </div>
        <div class="sidebar_widget">
          <div class="widget_heading">
            <h5><i class="fa fa-envelope" aria-hidden="true"></i>Book Now</h5>
          </div>
          <form method="post" action="{{ url('vehicle/' . $id . '/book') }}">
            @csrf
            <div class="form-group">
              <label>From Date:</label>
              <input type="date" class="form-control" name="fromdate" placeholder="From Date" required>
            </div>
            <div class="form-group">
              <label>To Date:</label>
              <input type="date" class="form-control" name="todate" placeholder="To Date" required>
            </div>
            <div class="form-group">
              <textarea rows="4" class="form-control" name="message" placeholder="Message" required></textarea>
            </div>
            @if(session('login'))
              <div class="form-group">
                <input type="submit" class="btn"  name="submit" value="Book Now">
              </div>
            @else
              <a href="#loginform" class="btn btn-xs uppercase" data-toggle="modal" data-dismiss="modal">Login For Book</a>
            @endif
          </form>
        </div>
      </aside>
    </div>
    <div class="space-20"></div>
    <div class="divider"></div>
    <div class="similar_cars">
      <h3>Similar Cars</h3>
      <div class="row">
        @forelse($similar ?? [] as $s)
        <div class="col-md-3 grid_listing">
          <div class="product-listing-m gray-bg">
            <div class="product-listing-img"> 
              <a href="{{ url('vehicle/' . $s->id) }}">
                @php
                  $img = $s->Vimage1 ?? '';
                  $paths = [
                    "legacy/admin/img/vehicleimages/{$img}",
                    "legacy/admin-img/vehicleimages/{$img}",
                    "legacy/img/vehicleimages/{$img}",
                    "legacy/admin-img/{$img}",
                    "legacy/admin/{$img}",
                    "legacy/img/{$img}",
                    "legacy/assets/images/{$img}",
                  ];
                  $src = asset('legacy/assets/images/car_755x430.png');
                  foreach($paths as $p){ 
                    $full = public_path($p); 
                    if($img && file_exists($full)){ 
                      $src = asset($p); 
                      break; 
                    } 
                  }
                @endphp
                <img src="{{ $src }}" class="img-responsive" alt="{{ $s->BrandName }} {{ $s->VehiclesTitle }}" /> 
              </a>
            </div>
            <div class="product-listing-content">
              <h5><a href="{{ url('vehicle/' . $s->id) }}">{{ $s->BrandName }} {{ $s->VehiclesTitle }}</a></h5>
              <p class="list-price">${{ number_format($s->PricePerDay, 0) }}/day</p>
              @if($s->FuelType)
                <p class="fuel-type"><i class="fa fa-tint"></i> {{ $s->FuelType }}</p>
              @endif
              @if($s->SeatingCapacity)
                <p class="seating"><i class="fa fa-users"></i> {{ $s->SeatingCapacity }} Seats</p>
              @endif
            </div>
          </div>
        </div>
        @empty
        <div class="col-md-12">
          <div class="alert alert-info text-center">
            <i class="fa fa-info-circle"></i> 
            No similar {{ $vehicle->BrandName ?? 'vehicles' }} available at the moment. Please check our other available vehicles.
          </div>
        </div>
        @endforelse
      </div>
    </div>
  </div>
</section>

@endsection
