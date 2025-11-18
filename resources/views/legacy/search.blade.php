@extends('layouts.legacy')

@section('title', 'Car Listing - Search Results')

@section('content')
<!--Page Header-->
<section class="page-header listing_page">
  <div class="container">
    <div class="page-header_wrap">
      <div class="page-heading">
        <h1>
          @if(isset($search) && $search)
            Search Result for "{{ $search }}"
          @else
            Car Listing
          @endif
        </h1>
      </div>
      <ul class="coustom-breadcrumb">
        <li><a href="{{ url('/') }}">Home</a></li>
        <li>Car Listing</li>
      </ul>
    </div>
  </div>
  <!-- Dark Overlay-->
  <div class="dark-overlay"></div>
</section>
<!-- /Page Header--> 

<!--Listing-->
<section class="listing-page">
  <div class="container">
    <div class="row">
      <div class="col-md-9 col-md-push-3">
        <div class="result-sorting-wrapper">
          <div class="sorting-count">
            <p><span>{{ $count }} Listings found</span></p>
          </div>
        </div>

        @if(count($vehicles) > 0)
          @foreach($vehicles as $result)
          <div class="product-listing-m gray-bg">
            <div class="product-listing-img">
              @php
                $img = $result->Vimage1 ?? '';
                $paths = [
                  "legacy/admin-img/vehicleimages/{$img}",
                  "legacy/admin/img/vehicleimages/{$img}", 
                  "legacy/img/vehicleimages/{$img}",
                  "legacy/assets/images/featured-img-3.jpg"
                ];
                $imgPath = "legacy/assets/images/featured-img-3.jpg";
                foreach($paths as $path) {
                  if(file_exists(public_path($path))) {
                    $imgPath = $path;
                    break;
                  }
                }
              @endphp
              <img src="{{ asset($imgPath) }}" class="img-responsive" alt="Image" /> 
            </div>
            <div class="product-listing-content">
              <h5><a href="{{ url('vehicle/' . $result->id) }}">{{ $result->BrandName ?? 'N/A' }}, {{ $result->VehiclesTitle ?? 'N/A' }}</a></h5>
              <p class="list-price">${{ $result->PricePerDay ?? '0' }} Per Day</p>
              <ul>
                <li><i class="fa fa-user" aria-hidden="true"></i>{{ $result->SeatingCapacity ?? 'N/A' }} seats</li>
                <li><i class="fa fa-calendar" aria-hidden="true"></i>{{ $result->ModelYear ?? 'N/A' }} model</li>
                <li><i class="fa fa-car" aria-hidden="true"></i>{{ $result->FuelType ?? 'N/A' }}</li>
              </ul>
              <a href="{{ url('vehicle/' . $result->id) }}" class="btn">View Details <span class="angle_arrow"><i class="fa fa-angle-right" aria-hidden="true"></i></span></a>
            </div>
          </div>
          @endforeach
        @else
          <div class="product-listing-m gray-bg">
            <div class="text-center" style="padding: 40px;">
              <h4>No vehicles found</h4>
              <p>Sorry, no vehicles match your search criteria.</p>
              <a href="{{ url('/') }}" class="btn">Back to Home</a>
            </div>
          </div>
        @endif
      </div>
      
      <!--Side-Bar-->
      <aside class="col-md-3 col-md-pull-9">
        <div class="sidebar_widget">
          <div class="widget_heading">
            <h5><i class="fa fa-filter" aria-hidden="true"></i> Find Your Car</h5>
          </div>
          <div class="sidebar_filter">
            <form action="{{ url('/car-listing') }}" method="get">
              <div class="form-group select">
                <select class="form-control" name="brand">
                  <option value="">Select Brand</option>
                  @foreach($brands as $brand)
                  <option value="{{ $brand->id }}">{{ $brand->BrandName }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group select">
                <select class="form-control" name="fueltype">
                  <option value="">Select Fuel Type</option>
                  <option value="Petrol">Petrol</option>
                  <option value="Diesel">Diesel</option>
                  <option value="CNG">CNG</option>
                </select>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-block"><i class="fa fa-search" aria-hidden="true"></i> Search Car</button>
              </div>
            </form>
          </div>
        </div>

        <div class="sidebar_widget">
          <div class="widget_heading">
            <h5><i class="fa fa-car" aria-hidden="true"></i> Recently Listed Cars</h5>
          </div>
          <div class="recent_addedcars">
            <ul>
              @foreach($recent as $recentCar)
              <li class="gray-bg">
                <div class="recent_post_img"> 
                  @php
                    $img = $recentCar->Vimage1 ?? '';
                    $paths = [
                      "legacy/admin-img/vehicleimages/{$img}",
                      "legacy/admin/img/vehicleimages/{$img}",
                      "legacy/img/vehicleimages/{$img}",
                      "legacy/assets/images/featured-img-3.jpg"
                    ];
                    $imgPath = "legacy/assets/images/featured-img-3.jpg";
                    foreach($paths as $path) {
                      if(file_exists(public_path($path))) {
                        $imgPath = $path;
                        break;
                      }
                    }
                  @endphp
                  <a href="{{ url('vehicle/' . $recentCar->id) }}"><img src="{{ asset($imgPath) }}" alt="image"></a> 
                </div>
                <div class="recent_post_title"> 
                  <a href="{{ url('vehicle/' . $recentCar->id) }}">{{ $recentCar->BrandName ?? 'N/A' }}, {{ $recentCar->VehiclesTitle ?? 'N/A' }}</a>
                  <p class="widget_price">${{ $recentCar->PricePerDay ?? '0' }} Per Day</p>
                </div>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
      </aside>
    </div>
  </div>
</section>
<!--/Listing--> 

@endsection