@extends('admin.layouts.master')

@section('title','Manage Contact Us Queries')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <h4><i class="fa fa-envelope"></i> Manage Contact Us Queries</h4>
    <small class="text-muted">View and manage customer inquiries</small>
  </div>
  <div class="panel-body">
    @if(session('msg')) 
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session('msg') }}
      </div> 
    @endif

    @if($queries->count() > 0)
    <div class="table-responsive">
      <table class="table table-striped table-bordered" id="queries-table">
        <thead>
          <tr>
            <th width="50px">#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Contact No</th>
            <th width="200px">Message</th>
            <th width="100px">Posting Date</th>
            <th width="120px">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($queries as $query)
          <tr>
            <td>{{ $query->id }}</td>
            <td>
              <strong>{{ $query->name ?? 'N/A' }}</strong>
            </td>
            <td>
              <a href="mailto:{{ $query->EmailId }}">{{ $query->EmailId }}</a>
            </td>
            <td>
              @if($query->ContactNumber)
                <a href="tel:{{ $query->ContactNumber }}">{{ $query->ContactNumber }}</a>
              @else
                <span class="text-muted">N/A</span>
              @endif
            </td>
            <td>
              <div class="message-preview">
                {{ Str::limit($query->Message ?? 'No message', 50) }}
                @if(strlen($query->Message ?? '') > 50)
                  <a href="#" class="text-primary show-full-message" data-message="{{ htmlspecialchars($query->Message ?? '') }}">
                    <small>[Read More]</small>
                  </a>
                @endif
              </div>
            </td>
            <td>
              @if($query->PostingDate)
                <small>{{ date('M d, Y', strtotime($query->PostingDate)) }}</small><br>
                <small class="text-muted">{{ date('h:i A', strtotime($query->PostingDate)) }}</small>
              @else
                <span class="text-muted">Unknown</span>
              @endif
            </td>
            <td>
              <div class="btn-group btn-group-xs">
                <button type="button" class="btn btn-info view-query" 
                        data-id="{{ $query->id }}"
                        data-name="{{ $query->name ?? 'N/A' }}"
                        data-email="{{ $query->EmailId }}"
                        data-phone="{{ $query->ContactNumber ?? 'N/A' }}"
                        data-message="{{ htmlspecialchars($query->Message ?? '') }}"
                        data-date="{{ $query->PostingDate ? date('M d, Y h:i A', strtotime($query->PostingDate)) : 'Unknown' }}"
                        title="View Full Query Details">
                  <i class="fa fa-eye"></i> View
                </button>
                <button type="button" class="btn btn-success reply-query"
                        data-email="{{ $query->EmailId }}"
                        data-name="{{ $query->name ?? 'Customer' }}"
                        title="Reply via Email">
                  <i class="fa fa-reply"></i> Reply
                </button>
                <form action="{{ route('admin.contactqueries.destroy', $query->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                  @csrf 
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger" title="Delete Query">
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
      {{ $queries->links() }}
    </div>
    @else
    <div class="alert alert-info text-center">
      <i class="fa fa-inbox fa-3x"></i>
      <h4>No Contact Queries</h4>
      <p>No customer inquiries have been received yet.</p>
    </div>
    @endif
  </div>
</div>

<!-- Modal for viewing full query -->
<div class="modal fade" id="queryModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-envelope"></i> Contact Query #<span id="modal-query-id"></span></h4>
      </div>
      <div class="modal-body">
        <div class="customer-info-card">
          <h5><i class="fa fa-user"></i> Customer Information</h5>
          <div class="row">
            <div class="col-md-6">
              <div class="info-item">
                <strong><i class="fa fa-user-circle"></i> Full Name:</strong>
                <p id="modal-name" class="info-value"></p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="info-item">
                <strong><i class="fa fa-envelope"></i> Email Address:</strong>
                <p id="modal-email" class="info-value">
                  <a href="#" id="modal-email-link" class="text-primary"></a>
                </p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="info-item">
                <strong><i class="fa fa-phone"></i> Contact Number:</strong>
                <p id="modal-phone" class="info-value">
                  <a href="#" id="modal-phone-link" class="text-primary"></a>
                </p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="info-item">
                <strong><i class="fa fa-calendar"></i> Submitted Date:</strong>
                <p id="modal-date" class="info-value"></p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="message-card">
          <h5><i class="fa fa-comment"></i> Customer Message</h5>
          <div class="message-content" id="modal-message"></div>
        </div>
        
        <div class="actions-card">
          <h5><i class="fa fa-cogs"></i> Quick Actions</h5>
          <div class="btn-group">
            <button type="button" class="btn btn-success" id="modal-reply-btn">
              <i class="fa fa-reply"></i> Reply via Email
            </button>
            <button type="button" class="btn btn-info" id="modal-call-btn">
              <i class="fa fa-phone"></i> Call Customer
            </button>
            <button type="button" class="btn btn-warning" id="modal-copy-btn">
              <i class="fa fa-copy"></i> Copy Details
            </button>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          <i class="fa fa-times"></i> Close
        </button>
        <button type="button" class="btn btn-primary" id="modal-print-btn">
          <i class="fa fa-print"></i> Print Query
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><i class="fa fa-reply"></i> Reply to Customer</h4>
      </div>
      <div class="modal-body">
        <form id="replyForm">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><i class="fa fa-user"></i> Customer Name</label>
                <input type="text" class="form-control" id="reply-customer-name" readonly>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label><i class="fa fa-envelope"></i> Customer Email</label>
                <input type="email" class="form-control" id="reply-customer-email" readonly>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label><i class="fa fa-tag"></i> Subject</label>
            <input type="text" class="form-control" id="reply-subject" value="Re: Your inquiry - Car Rental Portal">
          </div>
          <div class="form-group">
            <label><i class="fa fa-comment"></i> Your Reply Message</label>
            <textarea class="form-control" id="reply-message" rows="8" placeholder="Type your reply message here...">Dear Customer,

Thank you for contacting us regarding your inquiry. We appreciate your interest in our car rental services.



Best regards,
Car Rental Portal Team</textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          <i class="fa fa-times"></i> Cancel
        </button>
        <button type="button" class="btn btn-success" id="send-reply-btn">
          <i class="fa fa-send"></i> Send Reply
        </button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
let currentQueryData = {};

$(document).ready(function() {
    // Initialize DataTable
    $('#queries-table').DataTable({
        "responsive": true,
        "pageLength": 25,
        "order": [[0, "desc"]], // Sort by ID descending (newest first)
        "columnDefs": [
            { "orderable": false, "targets": 6 } // Disable sorting on Actions column
        ]
    });

    // View query modal
    $('.view-query').click(function() {
        const data = {
            id: $(this).data('id'),
            name: $(this).data('name'),
            email: $(this).data('email'),
            phone: $(this).data('phone'),
            message: $(this).data('message'),
            date: $(this).data('date')
        };
        
        currentQueryData = data;
        
        // Populate modal
        $('#modal-query-id').text(data.id);
        $('#modal-name').text(data.name);
        $('#modal-email-link').text(data.email).attr('href', 'mailto:' + data.email);
        $('#modal-phone-link').text(data.phone).attr('href', 'tel:' + data.phone);
        $('#modal-message').html(data.message.replace(/\\n/g, '<br>'));
        $('#modal-date').text(data.date);
        
        // Setup action buttons
        $('#modal-reply-btn').data('email', data.email).data('name', data.name);
        $('#modal-call-btn').data('phone', data.phone);
        
        $('#queryModal').modal('show');
    });

    // Reply button in main table
    $('.reply-query').click(function() {
        openReplyModal($(this).data('email'), $(this).data('name'));
    });

    // Modal action buttons
    $('#modal-reply-btn').click(function() {
        openReplyModal($(this).data('email'), $(this).data('name'));
    });

    $('#modal-call-btn').click(function() {
        const phone = $(this).data('phone');
        if (phone && phone !== 'N/A') {
            window.open('tel:' + phone);
        } else {
            alert('No phone number available for this customer.');
        }
    });

    $('#modal-copy-btn').click(function() {
        copyQueryDetails();
    });

    $('#modal-print-btn').click(function() {
        printQueryDetails();
    });

    // Send reply functionality
    $('#send-reply-btn').click(function() {
        sendReply();
    });

    // Show full message in tooltip
    $('.show-full-message').click(function(e) {
        e.preventDefault();
        alert($(this).data('message'));
    });
});

function openReplyModal(email, name) {
    $('#reply-customer-email').val(email);
    $('#reply-customer-name').val(name);
    $('#replyModal').modal('show');
}

function sendReply() {
    const email = $('#reply-customer-email').val();
    const subject = $('#reply-subject').val();
    const message = $('#reply-message').val();
    
    if (!message.trim()) {
        alert('Please enter a reply message.');
        return;
    }
    
    // Create mailto link with pre-filled content
    const mailtoLink = `mailto:${email}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(message)}`;
    window.open(mailtoLink);
    
    $('#replyModal').modal('hide');
    
    // Show success message
    showNotification('Reply email template opened in your default email client!', 'success');
}

function copyQueryDetails() {
    const text = `Contact Query Details:
ID: ${currentQueryData.id}
Name: ${currentQueryData.name}
Email: ${currentQueryData.email}
Phone: ${currentQueryData.phone}
Date: ${currentQueryData.date}
Message: ${currentQueryData.message}`;
    
    navigator.clipboard.writeText(text).then(function() {
        showNotification('Query details copied to clipboard!', 'success');
    }, function() {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showNotification('Query details copied to clipboard!', 'success');
    });
}

function printQueryDetails() {
    const printContent = `
        <div style="font-family: Arial, sans-serif; padding: 20px;">
            <h2>Contact Query Details</h2>
            <hr>
            <p><strong>Query ID:</strong> ${currentQueryData.id}</p>
            <p><strong>Customer Name:</strong> ${currentQueryData.name}</p>
            <p><strong>Email:</strong> ${currentQueryData.email}</p>
            <p><strong>Phone:</strong> ${currentQueryData.phone}</p>
            <p><strong>Date Submitted:</strong> ${currentQueryData.date}</p>
            <hr>
            <h3>Customer Message:</h3>
            <div style="border: 1px solid #ddd; padding: 15px; background: #f9f9f9;">
                ${currentQueryData.message.replace(/\\n/g, '<br>')}
            </div>
        </div>
    `;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.print();
}

function showNotification(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-info';
    const notification = `<div class="alert ${alertClass} alert-dismissible" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        ${message}
    </div>`;
    
    $('body').append(notification);
    
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
}

function confirmDelete() {
    return confirm('Are you sure you want to delete this contact query?\\n\\nThis action cannot be undone.');
}
</script>
@endpush

<style>
.message-preview {
    max-width: 200px;
    word-wrap: break-word;
}
.panel-heading h4 {
    margin: 0;
    color: #333;
}
.panel-heading small {
    display: block;
    margin-top: 5px;
}
.table th {
    background: #f1f3f4;
    font-weight: 600;
}
.btn-group-xs > .btn {
    margin-right: 2px;
}
.well {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    padding: 15px;
    border-radius: 5px;
    max-height: 200px;
    overflow-y: auto;
}

/* Enhanced Modal Styling */
.modal-lg {
    width: 90%;
    max-width: 900px;
}
.customer-info-card, .message-card, .actions-card {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}
.customer-info-card h5, .message-card h5, .actions-card h5 {
    color: #495057;
    margin-bottom: 15px;
    border-bottom: 2px solid #007bff;
    padding-bottom: 8px;
}
.info-item {
    margin-bottom: 15px;
}
.info-item strong {
    color: #6c757d;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.info-value {
    margin-top: 5px;
    margin-bottom: 0;
    font-size: 14px;
    color: #212529;
}
.message-content {
    background: #ffffff;
    border: 1px solid #dee2e6;
    padding: 20px;
    border-radius: 5px;
    min-height: 100px;
    line-height: 1.6;
    font-size: 14px;
}
.bg-info {
    background-color: #17a2b8 !important;
}
.bg-success {
    background-color: #28a745 !important;
}
.text-white {
    color: #fff !important;
}
.actions-card .btn-group .btn {
    margin-right: 10px;
}
.modal-header.bg-info .close,
.modal-header.bg-success .close {
    opacity: 0.8;
}
.modal-header.bg-info .close:hover,
.modal-header.bg-success .close:hover {
    opacity: 1;
}
</style>
@endsection