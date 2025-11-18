@extends('admin.layouts.master')

@section('title', isset($vehicle) ? 'Edit Vehicle' : 'Add Vehicle')

@section('content')
<div class="panel panel-default">
</div>
  <div class="panel-heading">{{ isset($vehicle) ? 'Edit Vehicle' : 'Post A Vehicle' }}</div>
  <div class="panel-body">
    @if($errors->any()) <div class="alert alert-danger">{{ implode(', ', $errors->all()) }}</div> @endif
    <form method="POST" action="{{ isset($vehicle) ? route('admin.vehicles.update',$vehicle->id) : route('admin.vehicles.store') }}" enctype="multipart/form-data">
      @csrf
      @if(isset($vehicle)) @method('PUT') @endif

      @php
        $vehicleAccessories = [];
        if(isset($vehicle) && !empty($vehicle->Accessories)) {
            $vehicleAccessories = array_map('trim', explode(',', $vehicle->Accessories));
        }
      @endphp

      <div class="row">
        <div class="col-md-8">
          <!-- Basic Info -->
          <div class="form-row">
            <div class="form-group col-md-8">
              <label>Vehicle Title *</label>
              <input class="form-control" name="VehiclesTitle" value="{{ old('VehiclesTitle', $vehicle->VehiclesTitle ?? '') }}" required>
            </div>
            <div class="form-group col-md-4">
              <label>Select Brand *</label>
              <select name="VehiclesBrand" class="form-control" required>
                <option value="">Select</option>
                @foreach(App\Models\Brand::orderBy('BrandName')->get() as $b)
                  <option value="{{ $b->id }}" {{ (old('VehiclesBrand', $vehicle->VehiclesBrand ?? '') == $b->id) ? 'selected' : '' }}>{{ $b->BrandName }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group">
            <label>Vehicle Overview *</label>
            <textarea name="VehiclesOverview" class="form-control" rows="4">{{ old('VehiclesOverview', $vehicle->VehiclesOverview ?? '') }}</textarea>
          </div>

          <div class="form-row">
            <div class="form-group col-md-4">
              <label>Price Per Day(in USD) *</label>
              <input class="form-control" name="PricePerDay" value="{{ old('PricePerDay', $vehicle->PricePerDay ?? '') }}" required>
            </div>
            <div class="form-group col-md-4">
              <label>Select Fuel Type *</label>
              <select name="FuelType" class="form-control">
                <option value="">Select</option>
                <option value="Petrol" {{ old('FuelType', $vehicle->FuelType ?? '') == 'Petrol' ? 'selected' : '' }}>Petrol</option>
                <option value="Diesel" {{ old('FuelType', $vehicle->FuelType ?? '') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                <option value="CNG" {{ old('FuelType', $vehicle->FuelType ?? '') == 'CNG' ? 'selected' : '' }}>CNG</option>
              </select>
            </div>
            <div class="form-group col-md-4">
              <label>Seating Capacity *</label>
              <input class="form-control" name="SeatingCapacity" value="{{ old('SeatingCapacity', $vehicle->SeatingCapacity ?? '') }}">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group col-md-4">
              <label>Model Year *</label>
              <input class="form-control" name="ModelYear" value="{{ old('ModelYear', $vehicle->ModelYear ?? '') }}">
            </div>
          </div>

          <!-- Upload Images -->
          <div class="form-group">
            <label>Upload Vehicle Images</label>
            <div class="row">
              <div class="col-md-3">
                <label>Image 1 * (Main)</label>
                <input type="file" name="Vimage1" class="form-control-file" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                @if(isset($vehicle) && $vehicle->Vimage1)
                  <small class="text-success">Current: {{ $vehicle->Vimage1 }}</small>
                @endif
              </div>
              <div class="col-md-3">
                <label>Image 2</label>
                <input type="file" name="Vimage2" class="form-control-file" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                @if(isset($vehicle) && $vehicle->Vimage2)
                  <small class="text-success">Current: {{ $vehicle->Vimage2 }}</small>
                @endif
              </div>
              <div class="col-md-3">
                <label>Image 3</label>
                <input type="file" name="Vimage3" class="form-control-file" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                @if(isset($vehicle) && $vehicle->Vimage3)
                  <small class="text-success">Current: {{ $vehicle->Vimage3 }}</small>
                @endif
              </div>
              <div class="col-md-3">
                <label>Image 4</label>
                <input type="file" name="Vimage4" class="form-control-file" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                @if(isset($vehicle) && $vehicle->Vimage4)
                  <small class="text-success">Current: {{ $vehicle->Vimage4 }}</small>
                @endif
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-md-3">
                <label>Image 5</label>
                <input type="file" name="Vimage5" class="form-control-file" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                @if(isset($vehicle) && $vehicle->Vimage5)
                  <small class="text-success">Current: {{ $vehicle->Vimage5 }}</small>
                @endif
              </div>
            </div>
          </div>

        </div>

        <div class="col-md-4">
          <!-- Accessories -->
          <div class="panel panel-default">
            <div class="panel-heading">Accessories</div>
            <div class="panel-body">
              @php
                $accessories = [
                  'AirConditioner' => 'Air Conditioner',
                  'PowerSteering' => 'Power Steering', 
                  'CDPlayer' => 'CD Player',
                  'PowerDoorLocks' => 'Power Door Locks',
                  'DriverAirbag' => 'Driver Airbag',
                  'CentralLocking' => 'Central Locking',
                  'AntiLockBrakingSystem' => 'AntiLock Braking System',
                  'PassengerAirbag' => 'Passenger Airbag',
                  'CrashSensor' => 'Crash Sensor',
                  'BrakeAssist' => 'Brake Assist',
                  'PowerWindows' => 'Power Windows',
                  'LeatherSeats' => 'Leather Seats'
                ];
              @endphp
              <div class="row">
                @foreach($accessories as $field => $label)
                  <div class="col-md-6">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="{{ $field }}" value="1" id="acc_{{ $field }}"
                        {{ old($field, isset($vehicle) && $vehicle->$field ? 1 : 0) ? 'checked' : '' }}>
                      <label class="form-check-label" for="acc_{{ $field }}">{{ $label }}</label>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>

          <div class="mt-3 text-center">
            <a href="{{ route('admin.vehicles.index') }}" class="btn btn-default">Cancel</a>
            <button class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>

    </form>
  </div>
</div>

@push('scripts')
<script>
document.getElementById('Vimage1Input')?.addEventListener('change', function(e){
  const f = e.target.files[0];
  if(!f) return;
  const reader = new FileReader();
  reader.onload = function(ev){
    const img = document.getElementById('Vimage1Preview');
    img.src = ev.target.result;
    img.style.display = 'block';
  }
  reader.readAsDataURL(f);
});
</script>
@endpush

@endsection
