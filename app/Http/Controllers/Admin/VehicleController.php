<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Brand;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with('brand')->orderBy('id','desc')->paginate(20);
        $brands = Brand::orderBy('BrandName')->get();
        return view('admin.vehicles.index', compact('vehicles','brands'));
    }

    public function create()
    {
        $brands = Brand::orderBy('BrandName')->get();
        return view('admin.vehicles.form', compact('brands'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'VehiclesTitle'=>'required|string|max:255',
            'PricePerDay'=>'required|numeric',
            'Vimage1'=>'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'Vimage2'=>'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'Vimage3'=>'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'Vimage4'=>'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'Vimage5'=>'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120'
        ], [
            'Vimage1.image' => 'The first image must be a valid image file (JPEG, PNG, GIF, or WebP).',
            'Vimage1.mimes' => 'The first image must be a JPEG, PNG, GIF, or WebP file.',
            'Vimage1.max' => 'The first image must not exceed 5MB in size.',
            '*.image' => 'All uploaded files must be valid images (JPEG, PNG, GIF, or WebP).',
            '*.mimes' => 'All images must be JPEG, PNG, GIF, or WebP files.',
            '*.max' => 'Each image must not exceed 5MB in size.'
        ]);

        // use legacy column names so admin-created records appear correctly on the public site
        $data = $r->only(['VehiclesTitle','PricePerDay','FuelType','ModelYear','SeatingCapacity','VehiclesBrand']);
        
        // Handle accessories - checkboxes are only sent if checked
        $accessoryFields = ['AirConditioner','AntiLockBrakingSystem','PowerSteering','PowerWindows',
                           'CDPlayer','LeatherSeats','CentralLocking','PowerDoorLocks',
                           'BrakeAssist','DriverAirbag','PassengerAirbag','CrashSensor'];
        foreach($accessoryFields as $field) {
            $data[$field] = $r->has($field) ? 1 : 0;
        }
        
        // Handle image uploads for all 5 images
        for ($i = 1; $i <= 5; $i++) {
            $imageField = "Vimage{$i}";
            if ($r->hasFile($imageField)) {
                $file = $r->file($imageField);
                $name = time() . '_img' . $i . '_' . $file->getClientOriginalName();
                $file->move(public_path('legacy/admin/img/vehicleimages'), $name);
                $data[$imageField] = $name;
            }
        }
    Vehicle::create($data);
        return redirect()->route('admin.vehicles.index')->with('msg','Vehicle created.');
    }

    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $brands = Brand::orderBy('BrandName')->get();
        return view('admin.vehicles.form', compact('vehicle','brands'));
    }

    public function update(Request $r, $id)
    {
        $r->validate([
            'VehiclesTitle'=>'required|string|max:255',
            'PricePerDay'=>'required|numeric',
            'Vimage1'=>'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'Vimage2'=>'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'Vimage3'=>'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'Vimage4'=>'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'Vimage5'=>'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120'
        ], [
            'Vimage1.image' => 'The first image must be a valid image file (JPEG, PNG, GIF, or WebP).',
            'Vimage1.mimes' => 'The first image must be a JPEG, PNG, GIF, or WebP file.',
            'Vimage1.max' => 'The first image must not exceed 5MB in size.',
            '*.image' => 'All uploaded files must be valid images (JPEG, PNG, GIF, or WebP).',
            '*.mimes' => 'All images must be JPEG, PNG, GIF, or WebP files.',
            '*.max' => 'Each image must not exceed 5MB in size.'
        ]);
        $vehicle = Vehicle::findOrFail($id);
        $data = $r->only(['VehiclesTitle','PricePerDay','FuelType','ModelYear','SeatingCapacity','VehiclesBrand']);
        
        // Handle accessories - checkboxes are only sent if checked
        $accessoryFields = ['AirConditioner','AntiLockBrakingSystem','PowerSteering','PowerWindows',
                           'CDPlayer','LeatherSeats','CentralLocking','PowerDoorLocks',
                           'BrakeAssist','DriverAirbag','PassengerAirbag','CrashSensor'];
        foreach($accessoryFields as $field) {
            $data[$field] = $r->has($field) ? 1 : 0;
        }
        
        // Handle image uploads for all 5 images
        for ($i = 1; $i <= 5; $i++) {
            $imageField = "Vimage{$i}";
            if ($r->hasFile($imageField)) {
                $file = $r->file($imageField);
                $name = time() . '_img' . $i . '_' . $file->getClientOriginalName();
                $file->move(public_path('legacy/admin/img/vehicleimages'), $name);
                $data[$imageField] = $name;
                
                // Delete old image if it exists
                $oldImage = $vehicle->$imageField;
                if ($oldImage && file_exists(public_path('legacy/admin/img/vehicleimages/' . $oldImage))) {
                    unlink(public_path('legacy/admin/img/vehicleimages/' . $oldImage));
                }
            }
        }
        $vehicle->update($data);
        return redirect()->route('admin.vehicles.index')->with('msg','Vehicle updated.');
    }

    public function destroy($id)
    {
        $v = Vehicle::findOrFail($id);
        $v->delete();
        return redirect()->route('admin.vehicles.index')->with('msg','Vehicle deleted.');
    }

    public function bulkDelete(Request $r)
    {
        $ids = $r->input('ids', []);
        if(empty($ids)){
            return redirect()->route('admin.vehicles.index')->with('error','No vehicles selected.');
        }
        Vehicle::whereIn('id', $ids)->delete();
        return redirect()->route('admin.vehicles.index')->with('msg','Selected vehicles deleted.');
    }

    public function exportCsv()
    {
    $columns = ['id','VehiclesTitle','VehiclesBrand','PricePerDay','FuelType','ModelYear','SeatingCapacity','Vimage1'];
        $response = new StreamedResponse(function() use ($columns) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);
            Vehicle::chunk(500, function($rows) use ($handle) {
                foreach($rows as $r){
                    fputcsv($handle, [
                        $r->id,$r->VehiclesTitle,$r->VehiclesBrand,$r->PricePerDay,$r->FuelType,$r->ModelYear,$r->SeatingCapacity,$r->Vimage1
                    ]);
                }
            });
            fclose($handle);
        });
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="vehicles.csv"');
        return $response;
    }
}
