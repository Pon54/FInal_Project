@extends('admin.layouts.master')

@section('title','Manage Vehicles')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    Manage Vehicles
    <a href="{{ route('admin.vehicles.create') }}" class="btn btn-success btn-xs pull-right">Add Vehicle</a>
  </div>
  <div class="panel-body">
    @if(session('msg')) <div class="alert alert-success">{{ session('msg') }}</div> @endif
    <form id="bulkForm" method="POST" action="{{ route('admin.vehicles.bulkDelete') }}">
      @csrf
      <div class="mb-2">
        <button type="button" id="selectAll" class="btn btn-sm btn-default">Select All</button>
        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete selected?')">Delete Selected</button>
        <a href="{{ route('admin.vehicles.export') }}" class="btn btn-primary btn-sm">Export CSV</a>
      </div>
      <table class="table table-striped table-bordered" id="vehicles-table">
        <thead>
          <tr><th><input type="checkbox" id="masterCheck"></th><th>#</th><th>Title</th><th>Brand</th><th>Price/Day</th><th>Image</th><th>Actions</th></tr>
        </thead>
        <tbody>
          @foreach($vehicles as $v)
          <tr>
            <td><input type="checkbox" class="rowCheck" name="ids[]" value="{{ $v->id }}"></td>
            <td>{{ $v->id }}</td>
            <td>{{ $v->VehicleTitle }}</td>
            <td>{{ $v->brand->BrandName ?? '—' }}</td>
            <td>{{ $v->PricePerDay }}</td>
            <td><img src="{{ asset('legacy/admin/img/vehicleimages/'.$v->Vimage1) }}" style="height:50px"></td>
            <td>
              <a href="{{ route('admin.vehicles.edit',$v->id) }}" class="btn btn-primary btn-xs">Edit</a>
              <form action="{{ route('admin.vehicles.destroy',$v->id) }}" method="POST" style="display:inline">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-xs" onclick="return confirm('Delete vehicle?')">Delete</button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </form>
    <div class="mt-3">{{ $vehicles->links() }}</div>
  </div>
</div>

@push('scripts')
<script>
document.getElementById('masterCheck')?.addEventListener('change', function(e){
  document.querySelectorAll('.rowCheck').forEach(cb => cb.checked = e.target.checked);
});
document.getElementById('selectAll')?.addEventListener('click', function(){
  const all = Array.from(document.querySelectorAll('.rowCheck'));
  const anyUnchecked = all.some(cb => !cb.checked);
  all.forEach(cb => cb.checked = anyUnchecked);
});
</script>
@endpush
@endsection
