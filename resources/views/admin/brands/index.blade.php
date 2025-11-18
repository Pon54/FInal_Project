@extends('admin.layouts.master')

@section('title','Manage Brands')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    Manage Vehicle Brands
    <a href="{{ route('admin.brands.create') }}" class="btn btn-success btn-xs pull-right">
      <i class="fa fa-plus"></i> Add New Brand
    </a>
  </div>
  <div class="panel-body">
    @if(session('msg')) 
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session('msg') }}
      </div> 
    @endif

    @if($brands->count() > 0)
    <div class="table-responsive">
      <table class="table table-striped table-bordered" id="brands-table">
        <thead>
          <tr>
            <th width="80px">#</th>
            <th>Brand Name</th>
            <th width="120px">Total Vehicles</th>
            <th width="150px">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($brands as $brand)
          <tr>
            <td>{{ $brand->id }}</td>
            <td>
              <strong>{{ $brand->BrandName }}</strong>
            </td>
            <td>
              <span class="badge badge-info">
                {{ \App\Models\Vehicle::where('VehiclesBrand', $brand->id)->count() }} vehicles
              </span>
            </td>
            <td>
              <div class="btn-group btn-group-xs">
                <a href="{{ route('admin.brands.edit', $brand->id) }}" class="btn btn-primary" title="Edit Brand">
                  <i class="fa fa-edit"></i> Edit
                </a>
                <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete('{{ $brand->BrandName }}')">
                  @csrf 
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger" title="Delete Brand">
                    <i class="fa fa-trash"></i> Delete
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-3 text-center">
      {{ $brands->links() }}
    </div>
    @else
    <div class="alert alert-info text-center">
      <i class="fa fa-info-circle fa-2x"></i>
      <h4>No Brands Found</h4>
      <p>No vehicle brands have been added yet.</p>
      <a href="{{ route('admin.brands.create') }}" class="btn btn-success">
        <i class="fa fa-plus"></i> Add First Brand
      </a>
    </div>
    @endif
  </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable for better functionality
    $('#brands-table').DataTable({
        "responsive": true,
        "pageLength": 25,
        "order": [[1, "asc"]], // Sort by brand name
        "columnDefs": [
            { "orderable": false, "targets": 3 } // Disable sorting on Actions column
        ]
    });
});

function confirmDelete(brandName) {
    return confirm('Are you sure you want to delete the brand "' + brandName + '"?\n\nThis action cannot be undone and may affect associated vehicles.');
}
</script>
@endpush

<style>
.badge {
    font-size: 11px;
}
.btn-group-xs > .btn {
    margin-right: 2px;
}
.panel-heading {
    background: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}
.table th {
    background: #f1f3f4;
    font-weight: 600;
}
.alert-dismissible {
    position: relative;
}
</style>
@endsection