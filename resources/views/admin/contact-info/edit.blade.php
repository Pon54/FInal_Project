@extends('admin.layouts.master')

@section('title','Update Contact Info')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <h4><i class="fa fa-cog"></i> Update Contact Information</h4>
    <small class="text-muted">Manage your business contact details displayed on the website</small>
  </div>
  <div class="panel-body">
    @if(session('msg')) 
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fa fa-check-circle"></i> {{ session('msg') }}
      </div> 
    @endif

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-2">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="row">
      <div class="col-md-8">
        <form method="POST" action="{{ route('admin.contact-info.update') }}" class="contact-info-form">
          @csrf
          @method('PUT')
          
          <div class="form-section">
            <h5><i class="fa fa-info-circle"></i> Business Information</h5>
            
            <div class="form-group">
              <label for="Address" class="control-label">
                <i class="fa fa-map-marker"></i> Business Address *
              </label>
              <textarea name="Address" 
                       id="Address" 
                       class="form-control" 
                       rows="3" 
                       placeholder="Enter your complete business address..."
                       required>{{ old('Address', $contactInfo->Address ?? '') }}</textarea>
              <small class="help-block">This address will be displayed on your website's contact page.</small>
            </div>

            <div class="form-group">
              <label for="EmailId" class="control-label">
                <i class="fa fa-envelope"></i> Business Email *
              </label>
              <input type="email" 
                     name="EmailId" 
                     id="EmailId" 
                     class="form-control" 
                     value="{{ old('EmailId', $contactInfo->EmailId ?? '') }}"
                     placeholder="business@yourcompany.com"
                     required>
              <small class="help-block">Main email address for customer inquiries and support.</small>
            </div>

            <div class="form-group">
              <label for="ContactNo" class="control-label">
                <i class="fa fa-phone"></i> Business Phone Number *
              </label>
              <input type="text" 
                     name="ContactNo" 
                     id="ContactNo" 
                     class="form-control" 
                     value="{{ old('ContactNo', $contactInfo->ContactNo ?? '') }}"
                     placeholder="e.g., +1 (555) 123-4567 or 09123456789"
                     required>
              <small class="help-block">Primary contact number for customer service and support.</small>
            </div>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">
              <i class="fa fa-save"></i> Update Contact Information
            </button>
            <button type="reset" class="btn btn-default btn-lg">
              <i class="fa fa-undo"></i> Reset Changes
            </button>
          </div>
        </form>
      </div>

      <div class="col-md-4">
        <div class="info-panel">
          <h5><i class="fa fa-lightbulb-o"></i> Information</h5>
          <div class="alert alert-info">
            <p><strong>Where this information appears:</strong></p>
            <ul class="fa-ul">
              <li><i class="fa fa-li fa-check"></i> Website header</li>
              <li><i class="fa fa-li fa-check"></i> Contact Us page</li>
              <li><i class="fa fa-li fa-check"></i> Footer section</li>
              <li><i class="fa fa-li fa-check"></i> Email signatures</li>
            </ul>
          </div>

          <div class="current-info">
            <h5><i class="fa fa-eye"></i> Current Information</h5>
            <div class="info-display">
              <div class="info-item">
                <strong>Address:</strong>
                <p>{{ $contactInfo->Address ?? 'Not set' }}</p>
              </div>
              <div class="info-item">
                <strong>Email:</strong>
                <p>{{ $contactInfo->EmailId ?? 'Not set' }}</p>
              </div>
              <div class="info-item">
                <strong>Phone:</strong>
                <p>{{ $contactInfo->ContactNo ?? 'Not set' }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('.contact-info-form').on('submit', function(e) {
        let isValid = true;
        
        // Check required fields
        $(this).find('input[required], textarea[required]').each(function() {
            if (!$(this).val().trim()) {
                isValid = false;
                $(this).addClass('error');
            } else {
                $(this).removeClass('error');
            }
        });

        // Email validation
        const email = $('#EmailId').val();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email && !emailRegex.test(email)) {
            isValid = false;
            $('#EmailId').addClass('error');
            alert('Please enter a valid email address.');
        }

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields correctly.');
        }
    });

    // Remove error class on input
    $('input, textarea').on('input', function() {
        $(this).removeClass('error');
    });

    // Auto-save notification
    let originalData = $('.contact-info-form').serialize();
    setInterval(function() {
        let currentData = $('.contact-info-form').serialize();
        if (originalData !== currentData) {
            $('.form-actions').addClass('has-changes');
        } else {
            $('.form-actions').removeClass('has-changes');
        }
    }, 1000);
});
</script>
@endpush

<style>
.panel-heading h4 {
    margin: 0;
    color: #333;
}
.panel-heading small {
    display: block;
    margin-top: 5px;
}
.form-section {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 25px;
    margin-bottom: 25px;
}
.form-section h5 {
    color: #495057;
    margin-bottom: 20px;
    border-bottom: 2px solid #007bff;
    padding-bottom: 8px;
}
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
}
.form-group .help-block {
    color: #6c757d;
    font-style: italic;
    margin-top: 5px;
}
.form-control {
    border-radius: 6px;
    border: 2px solid #dee2e6;
    padding: 12px;
    transition: all 0.3s ease;
}
.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}
.form-control.error {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220,53,69,.25);
}
.form-actions {
    text-align: center;
    padding-top: 20px;
    border-top: 2px solid #dee2e6;
    transition: all 0.3s ease;
}
.form-actions.has-changes {
    background: #fff3cd;
    border-color: #ffc107;
    margin: 0 -25px -25px -25px;
    padding: 20px 25px 25px 25px;
}
.info-panel {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
}
.info-panel h5 {
    color: #495057;
    margin-bottom: 15px;
    border-bottom: 2px solid #28a745;
    padding-bottom: 8px;
}
.current-info .info-item {
    margin-bottom: 15px;
    padding: 10px;
    background: #fff;
    border-radius: 5px;
    border-left: 4px solid #007bff;
}
.current-info .info-item strong {
    color: #495057;
    font-size: 12px;
    text-transform: uppercase;
}
.current-info .info-item p {
    margin: 5px 0 0 0;
    color: #212529;
}
.btn-lg {
    padding: 12px 25px;
    font-size: 16px;
    margin-right: 10px;
}
</style>
@endsection