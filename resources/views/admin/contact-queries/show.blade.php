@extends('admin.layouts.master')

@section('title', 'Contact Query Details')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">
        <h4><i class="fa fa-envelope"></i> Contact Query Details #{{ $query->id }}</h4>
        <a href="{{ route('admin.contactqueries.index') }}" class="btn btn-sm btn-default pull-right">
            <i class="fa fa-arrow-left"></i> Back to Queries
        </a>
        <div class="clearfix"></div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-8">
                <div class="well">
                    <h4><i class="fa fa-user"></i> Customer Information</h4>
                    <table class="table table-borderless">
                        <tr>
                            <td width="150px"><strong>Name:</strong></td>
                            <td>{{ $query->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>
                                <a href="mailto:{{ $query->EmailId }}" class="text-primary">
                                    <i class="fa fa-envelope"></i> {{ $query->EmailId }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Phone:</strong></td>
                            <td>
                                @if($query->ContactNumber)
                                    <a href="tel:{{ $query->ContactNumber }}" class="text-primary">
                                        <i class="fa fa-phone"></i> {{ $query->ContactNumber }}
                                    </a>
                                @else
                                    <span class="text-muted">Not provided</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Submitted:</strong></td>
                            <td>
                                @if($query->PostingDate)
                                    <i class="fa fa-calendar"></i> {{ date('F d, Y', strtotime($query->PostingDate)) }}
                                    <small class="text-muted">at {{ date('h:i A', strtotime($query->PostingDate)) }}</small>
                                @else
                                    <span class="text-muted">Unknown date</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="well">
                    <h4><i class="fa fa-comment"></i> Customer Message</h4>
                    <div class="message-content" style="background: #f9f9f9; padding: 15px; border-radius: 5px; border-left: 4px solid #007bff;">
                        {!! nl2br(e($query->Message ?? 'No message provided')) !!}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="well">
                    <h4><i class="fa fa-cogs"></i> Actions</h4>
                    <div class="btn-group-vertical btn-block">
                        <button type="button" class="btn btn-success btn-block" onclick="openReplyModal('{{ $query->EmailId }}', '{{ $query->name ?? 'Customer' }}')">
                            <i class="fa fa-reply"></i> Reply via Email
                        </button>
                        
                        @if($query->ContactNumber)
                        <button type="button" class="btn btn-info btn-block" onclick="window.open('tel:{{ $query->ContactNumber }}')">
                            <i class="fa fa-phone"></i> Call Customer
                        </button>
                        @endif
                        
                        <button type="button" class="btn btn-warning btn-block" onclick="copyQueryDetails()">
                            <i class="fa fa-copy"></i> Copy Details
                        </button>
                        
                        <button type="button" class="btn btn-default btn-block" onclick="printQuery()">
                            <i class="fa fa-print"></i> Print Query
                        </button>
                        
                        <hr>
                        
                        <form method="POST" action="{{ route('admin.contactqueries.destroy', $query->id) }}" onsubmit="return confirm('Are you sure you want to delete this query?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fa fa-trash"></i> Delete Query
                            </button>
                        </form>
                    </div>
                </div>

                <div class="well">
                    <h5><i class="fa fa-info-circle"></i> Quick Stats</h5>
                    <ul class="list-unstyled">
                        <li><strong>Query ID:</strong> #{{ $query->id }}</li>
                        <li><strong>Word Count:</strong> {{ str_word_count($query->Message ?? '') }} words</li>
                        <li><strong>Character Count:</strong> {{ strlen($query->Message ?? '') }} characters</li>
                    </ul>
                </div>
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
                <button type="button" class="btn btn-success" onclick="sendReply()">
                    <i class="fa fa-send"></i> Send Reply
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const queryData = {
    id: {{ $query->id }},
    name: '{{ $query->name ?? "N/A" }}',
    email: '{{ $query->EmailId }}',
    phone: '{{ $query->ContactNumber ?? "N/A" }}',
    message: `{{ addslashes($query->Message ?? '') }}`,
    date: '{{ $query->PostingDate ? date("F d, Y h:i A", strtotime($query->PostingDate)) : "Unknown" }}'
};

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
    
    // Create mailto link
    const mailtoLink = `mailto:${email}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(message)}`;
    window.open(mailtoLink);
    
    $('#replyModal').modal('hide');
    showNotification('Reply email template opened in your default email client!', 'success');
}

function copyQueryDetails() {
    const text = `Contact Query Details:
ID: ${queryData.id}
Name: ${queryData.name}
Email: ${queryData.email}
Phone: ${queryData.phone}
Date: ${queryData.date}
Message: ${queryData.message}`;
    
    navigator.clipboard.writeText(text).then(function() {
        showNotification('Query details copied to clipboard!', 'success');
    }).catch(function() {
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

function printQuery() {
    const printContent = `
        <div style="font-family: Arial, sans-serif; padding: 20px; max-width: 800px;">
            <h2>Contact Query Details</h2>
            <hr style="border: 1px solid #ddd;">
            <table style="width: 100%; margin-bottom: 20px;">
                <tr><td style="width: 150px;"><strong>Query ID:</strong></td><td>#${queryData.id}</td></tr>
                <tr><td><strong>Customer Name:</strong></td><td>${queryData.name}</td></tr>
                <tr><td><strong>Email:</strong></td><td>${queryData.email}</td></tr>
                <tr><td><strong>Phone:</strong></td><td>${queryData.phone}</td></tr>
                <tr><td><strong>Date Submitted:</strong></td><td>${queryData.date}</td></tr>
            </table>
            <hr style="border: 1px solid #ddd;">
            <h3>Customer Message:</h3>
            <div style="border: 1px solid #ddd; padding: 15px; background: #f9f9f9; border-radius: 5px;">
                ${queryData.message.replace(/\\n/g, '<br>')}
            </div>
            <hr style="border: 1px solid #ddd; margin-top: 30px;">
            <small style="color: #666;">Generated on ${new Date().toLocaleString()} - Car Rental Portal</small>
        </div>
    `;
    
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Contact Query #${queryData.id}</title>
            <style>
                body { margin: 0; padding: 20px; font-family: Arial, sans-serif; }
                @media print { body { margin: 0; } }
            </style>
        </head>
        <body>
            ${printContent}
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

function showNotification(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-info';
    const notification = `<div class="alert ${alertClass} alert-dismissible" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>${type === 'success' ? 'Success!' : 'Info:'}</strong> ${message}
    </div>`;
    
    $('body').append(notification);
    
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 4000);
}
</script>

<style>
.well h4, .well h5 {
    margin-top: 0;
    color: #333;
}
.message-content {
    line-height: 1.6;
    font-size: 14px;
}
.btn-group-vertical .btn {
    margin-bottom: 5px;
}
.table-borderless td {
    border: none;
    padding: 8px 0;
}
</style>

@endsection