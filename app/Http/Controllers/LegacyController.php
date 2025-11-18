<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\Vehicle;
use App\Models\User;

class LegacyController extends Controller
{
    public function index()
    {
        // delegate to the same data as VehicleController so '/' and legacy index match
        $vehicles = Vehicle::with('brand')->orderBy('id', 'desc')->take(9)->get();

        $contact_email = null;
        $contact_phone = null;
        if (Schema::hasTable('tblcontactusinfo')) {
            try {
                $c = DB::table('tblcontactusinfo')->first();
            } catch (\Exception $e) {
                $c = null;
            }
            if ($c) {
                $contact_email = $c->EmailId ?? $c->email ?? $c->Email ?? null;
                $contact_phone = $c->ContactNumber ?? $c->ContactNo ?? $c->phone ?? $c->Phone ?? null;
            }
        }

        return view('legacy.index', compact('vehicles','contact_email','contact_phone'));
    }

    public function search(Request $request)
    {
        // Accept search via POST or GET. If no search term, show all vehicles (legacy listing).
        $search = $request->input('searchdata') ?? $request->query('searchdata');
        $brand = $request->input('brand') ?? $request->query('brand');
        $fuel = $request->input('fuel') ?? $request->query('fuel');

        // Start with base query and join with brands table to get brand names
        $vehiclesQuery = Vehicle::select('tblvehicles.*', 'tblbrands.BrandName')
                                ->join('tblbrands', 'tblbrands.id', '=', 'tblvehicles.VehiclesBrand');

        // Apply search filters
        if ($search) {
            $vehiclesQuery->where(function($query) use ($search) {
                $query->where('VehiclesTitle', 'like', "%{$search}%")
                      ->orWhere('FuelType', 'like', "%{$search}%");
            });
        }

        if ($brand) {
            // Get the brand ID from brand name for proper filtering
            $brandModel = \App\Models\Brand::where('BrandName', $brand)->first();
            if ($brandModel) {
                $vehiclesQuery->where('VehiclesBrand', $brandModel->id);
            }
        }

        if ($fuel) {
            $vehiclesQuery->where('FuelType', $fuel);
        }

        $vehiclesQuery->orderBy('id', 'desc');

        // If we have a query builder, resolve it to a collection or paginator depending on context
        if (!isset($vehicles) && isset($vehiclesQuery)) {
            // For non-listing routes with no search, we prefer a collection for the legacy behaviour
            if ($request->is('car-listing')) {
                $vehicles = $vehiclesQuery->paginate(12);
            } else {
                $vehicles = $vehiclesQuery->get();
            }
        }

        $recent = Vehicle::select('tblvehicles.*', 'tblbrands.BrandName')
                         ->join('tblbrands', 'tblbrands.id', '=', 'tblvehicles.VehiclesBrand')
                         ->orderBy('tblvehicles.id', 'desc')
                         ->take(1)  // Only take 1 since we only have 1 vehicle
                         ->get();
        $brands = \App\Models\Brand::orderBy('BrandName')->get();
        $count = is_countable($vehicles) ? count($vehicles) : 0;
        return view('legacy.search', compact('search', 'vehicles', 'recent', 'count', 'brands'));
    }

    public function vehicle($id)
    {
        $vehicle = Vehicle::select('tblvehicles.*', 'tblbrands.BrandName')
                          ->join('tblbrands', 'tblbrands.id', '=', 'tblvehicles.VehiclesBrand')
                          ->find($id);
        
        $similar = collect();
        
        if ($vehicle) {
            // First, try to get vehicles from the same brand
            $sameBrand = Vehicle::select('tblvehicles.*', 'tblbrands.BrandName')
                              ->join('tblbrands', 'tblbrands.id', '=', 'tblvehicles.VehiclesBrand')
                              ->where('VehiclesBrand', $vehicle->VehiclesBrand)
                              ->where('tblvehicles.id', '!=', $id)
                              ->take(4)
                              ->get();
            
            $similar = $similar->merge($sameBrand);
            
            // If we need more similar cars, get vehicles with similar price range or seating capacity
            if ($similar->count() < 4) {
                $priceMin = $vehicle->PricePerDay * 0.7; // 30% lower
                $priceMax = $vehicle->PricePerDay * 1.3; // 30% higher
                
                $similarFeatures = Vehicle::select('tblvehicles.*', 'tblbrands.BrandName')
                                        ->join('tblbrands', 'tblbrands.id', '=', 'tblvehicles.VehiclesBrand')
                                        ->where('tblvehicles.id', '!=', $id)
                                        ->where('VehiclesBrand', '!=', $vehicle->VehiclesBrand)
                                        ->where(function($query) use ($vehicle, $priceMin, $priceMax) {
                                            $query->whereBetween('PricePerDay', [$priceMin, $priceMax])
                                                  ->orWhere('SeatingCapacity', $vehicle->SeatingCapacity)
                                                  ->orWhere('FuelType', $vehicle->FuelType);
                                        })
                                        ->take(4 - $similar->count())
                                        ->get();
                
                $similar = $similar->merge($similarFeatures)->unique('id')->take(4);
            }
        }
        return view('legacy.vehicle', compact('id', 'vehicle', 'similar'));
    }

    public function contact()
    {
        // contact form: placeholder. The view will post back to this route in future.
        $contact_info = null;
        return view('legacy.contact', compact('contact_info'));
    }

    public function profile()
    {
        if (!Auth::check()) {
            return redirect('/')->with('error', 'Please login first.');
        }
        
        $user = Auth::user();
        return view('legacy.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/')->with('error', 'Please login first.');
        }
        
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();
        
        if ($user) {
            $user->update([
                'FullName' => $request->fullname,
                'EmailId' => $request->email,
                'ContactNo' => $request->phone,
                'dob' => $request->dob,
                'Address' => $request->address,
                'Country' => $request->country,
                'City' => $request->city,
            ]);
        }

        return redirect()->back()->with('msg', 'Profile updated successfully!');
    }

    public function showUpdatePassword()
    {
        if (!Auth::check()) {
            return redirect('/')->with('error', 'Please login first.');
        }
        
        return view('legacy.update-password');
    }

    public function updatePassword(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/')->with('error', 'Please login first.');
        }
        
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed'
        ]);

        $user = Auth::user();
        
        if ($user && \Hash::check($request->current_password, $user->Password)) {
            $user->update(['Password' => bcrypt($request->new_password)]);
            return redirect()->back()->with('msg', 'Password updated successfully!');
        }

        return redirect()->back()->with('error', 'Current password is incorrect.');
    }

    public function myBooking()
    {
        if (!Auth::check()) {
            return redirect('/')->with('error', 'Please login first.');
        }
        
        $user = Auth::user();
        $bookings = \App\Models\Booking::where('userEmail', $user->EmailId)->orderBy('id', 'desc')->get();
        return view('legacy.my-booking', compact('bookings'));
    }

    public function showPostTestimonial()
    {
        if (!Auth::check()) {
            return redirect('/')->with('error', 'Please login first.');
        }
        
        return view('legacy.post-testimonial');
    }

    public function postTestimonial(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/')->with('error', 'Please login first.');
        }
        
        $request->validate([
            'testimonial' => 'required|string|max:1000'
        ]);

        $user = Auth::user();
        \App\Models\Testimonial::create([
            'UserEmail' => $user->EmailId,
            'Testimonial' => $request->testimonial,
            'PostingDate' => now(),
            'status' => 1
        ]);

        return redirect()->back()->with('msg', 'Testimonial posted successfully!');
    }

    public function myTestimonials()
    {
        if (!Auth::check()) {
            return redirect('/')->with('error', 'Please login first.');
        }
        
        $user = Auth::user();
        $testimonials = \App\Models\Testimonial::where('UserEmail', $user->EmailId)->orderBy('PostingDate', 'desc')->get();
        return view('legacy.my-testimonials', compact('testimonials'));
    }
}
