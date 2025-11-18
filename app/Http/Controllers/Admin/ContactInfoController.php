<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactInfo;

class ContactInfoController extends Controller
{
    public function edit()
    {
        $contactInfo = ContactInfo::first();
        if (!$contactInfo) {
            // Create default contact info if none exists
            $contactInfo = ContactInfo::create([
                'Address' => '',
                'EmailId' => '',
                'ContactNo' => ''
            ]);
        }
        return view('admin.contact-info.edit', compact('contactInfo'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'Address' => 'required|string|max:500',
            'EmailId' => 'required|email|max:255',
            'ContactNo' => 'required|string|max:20'
        ]);

        $contactInfo = ContactInfo::first();
        
        if ($contactInfo) {
            $contactInfo->update($request->only(['Address', 'EmailId', 'ContactNo']));
        } else {
            ContactInfo::create($request->only(['Address', 'EmailId', 'ContactNo']));
        }

        return redirect()->route('admin.contact-info.edit')->with('msg', 'Contact information updated successfully!');
    }
}