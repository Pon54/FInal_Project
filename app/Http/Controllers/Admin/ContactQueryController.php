<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactQuery;
use Illuminate\Support\Facades\Mail;

class ContactQueryController extends Controller
{
    public function index()
    {
        $queries = ContactQuery::orderBy('id', 'desc')->paginate(20);
        return view('admin.contact-queries.index', compact('queries'));
    }

    public function show($id)
    {
        $query = ContactQuery::findOrFail($id);
        return response()->json([
            'success' => true,
            'query' => [
                'id' => $query->id,
                'name' => $query->name,
                'email' => $query->EmailId,
                'phone' => $query->ContactNumber,
                'message' => $query->Message,
                'date' => $query->PostingDate ? date('M d, Y h:i A', strtotime($query->PostingDate)) : 'Unknown'
            ]
        ]);
    }

    public function reply(Request $request)
    {
        $request->validate([
            'query_id' => 'required|exists:tblcontactusquery,id',
            'to_email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string'
        ]);

        try {
            // In a real application, you would send an actual email here
            // For now, we'll create a mailto link response
            $mailtoUrl = 'mailto:' . $request->to_email . 
                        '?subject=' . urlencode($request->subject) . 
                        '&body=' . urlencode($request->message);
            
            return response()->json([
                'success' => true,
                'message' => 'Reply email template prepared successfully.',
                'mailto_url' => $mailtoUrl
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to prepare reply email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function markAsRead($id)
    {
        $query = ContactQuery::findOrFail($id);
        // Add a status field to mark as read if needed
        // For now, we'll just return success
        return response()->json([
            'success' => true,
            'message' => 'Query marked as read.'
        ]);
    }

    public function destroy($id)
    {
        ContactQuery::findOrFail($id)->delete();
        return redirect()->route('admin.contactqueries.index')->with('msg', 'Contact query deleted successfully.');
    }
}
