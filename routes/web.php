<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\LegacyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\Admin\VehicleController as AdminVehicleController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DiagnosticsController as AdminDiagnosticsController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ContactQueryController as AdminContactQueryController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\SubscriberController as AdminSubscriberController;
use App\Http\Controllers\Admin\ContactInfoController as AdminContactInfoController;

/*
|--------------------------------------------------------------------------
| Public / Legacy routes
|--------------------------------------------------------------------------
*/
Route::get('/', [VehicleController::class, 'index']);
Route::get('/vehicle/{id}', [VehicleController::class, 'show']);

Route::match(['get','post'], '/search', [LegacyController::class, 'search']);
Route::get('/car-listing', [LegacyController::class, 'search']);
Route::get('/car-listing.php', function () { return redirect('/car-listing', 301); });

Route::get('/contact-us', [LegacyController::class, 'contact']);
Route::post('/contact-us', [ContactController::class, 'contact']);
Route::post('/contact', [ContactController::class, 'contact']);
Route::post('/subscribe', [ContactController::class, 'subscribe']);

Route::get('/page', [PageController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Auth (public)
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot', [AuthController::class, 'forgot']);
Route::get('/logout', [AuthController::class, 'logout']);

// User profile routes (protected)
Route::get('/profile', [LegacyController::class, 'profile']);
Route::post('/profile', [LegacyController::class, 'updateProfile']);
Route::get('/update-password', [LegacyController::class, 'showUpdatePassword']);
Route::post('/update-password', [LegacyController::class, 'updatePassword']);
Route::get('/my-booking', [LegacyController::class, 'myBooking']);
Route::get('/post-testimonial', [LegacyController::class, 'showPostTestimonial']);
Route::post('/post-testimonial', [LegacyController::class, 'postTestimonial']);
Route::get('/my-testimonials', [LegacyController::class, 'myTestimonials']);

/*
|--------------------------------------------------------------------------
| Bookings
|--------------------------------------------------------------------------
*/
Route::post('/vehicle/{id}/book', [BookingController::class, 'book']);

/*
|--------------------------------------------------------------------------
| Admin routes (prefix + middleware) - single, consistent group
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // public admin auth / login page
    Route::get('/', [AdminController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.post');

    // NOTE: removed dev-login helper for production safety. Use real admin login.

    // protected admin pages
    Route::middleware([AdminMiddleware::class])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/change-password', [AdminController::class, 'showChangePassword'])->name('change-password');
        Route::post('/change-password', [AdminController::class, 'changePassword'])->name('change-password');
        Route::get('/logout', [AdminController::class, 'logout'])->name('logout');

        // diagnostics
        Route::get('/diagnostics/images', [AdminDiagnosticsController::class, 'images'])->name('diagnostics.images');

        // Vehicles CRUD + bulk/export
        Route::get('/manage-vehicles', [AdminVehicleController::class,'index'])->name('vehicles.index');
        Route::get('/manage-vehicles/create', [AdminVehicleController::class,'create'])->name('vehicles.create');
        Route::post('/manage-vehicles', [AdminVehicleController::class,'store'])->name('vehicles.store');
        Route::get('/manage-vehicles/{id}/edit', [AdminVehicleController::class,'edit'])->name('vehicles.edit');
        Route::put('/manage-vehicles/{id}', [AdminVehicleController::class,'update'])->name('vehicles.update');
        Route::delete('/manage-vehicles/{id}', [AdminVehicleController::class,'destroy'])->name('vehicles.destroy');
        Route::post('/manage-vehicles/bulk-delete', [AdminVehicleController::class,'bulkDelete'])->name('vehicles.bulkDelete');
        Route::get('/manage-vehicles/export', [AdminVehicleController::class,'exportCsv'])->name('vehicles.export');

        // Brands CRUD (resource-like)
        Route::get('/manage-brands', [AdminBrandController::class,'index'])->name('brands.index');
        Route::get('/manage-brands/create', [AdminBrandController::class,'create'])->name('brands.create');
        Route::post('/manage-brands', [AdminBrandController::class,'store'])->name('brands.store');
        Route::get('/manage-brands/{id}/edit', [AdminBrandController::class,'edit'])->name('brands.edit');
        Route::put('/manage-brands/{id}', [AdminBrandController::class,'update'])->name('brands.update');
        Route::delete('/manage-brands/{id}', [AdminBrandController::class,'destroy'])->name('brands.destroy');

        // Bookings
        Route::get('/manage-bookings', [AdminBookingController::class,'index'])->name('bookings.index');
        Route::post('/manage-bookings/{id}/status', [AdminBookingController::class,'setStatus'])->name('bookings.setstatus');
        Route::get('/manage-bookings/{id}', [AdminBookingController::class,'show'])->name('bookings.show');

    // Other admin pages
    // Pages CRUD
    Route::get('/manage-pages', [AdminPageController::class,'index'])->name('pages.index');
    Route::get('/manage-pages/create', [AdminPageController::class,'create'])->name('pages.create');
    Route::post('/manage-pages', [AdminPageController::class,'store'])->name('pages.store');
    Route::get('/manage-pages/{id}/edit', [AdminPageController::class,'edit'])->name('pages.edit');
    Route::put('/manage-pages/{id}', [AdminPageController::class,'update'])->name('pages.update');
    Route::delete('/manage-pages/{id}', [AdminPageController::class,'destroy'])->name('pages.destroy');

    // Registered users (legacy)
    Route::get('/reg-users', [AdminUserController::class,'index'])->name('users.index');
    Route::get('/reg-users/{id}', [AdminUserController::class,'show'])->name('users.show');
    Route::delete('/reg-users/{id}', [AdminUserController::class,'destroy'])->name('users.destroy');

    // Contact Queries
    Route::get('/manage-conactusquery', [AdminContactQueryController::class,'index'])->name('contactqueries.index');
    Route::get('/manage-conactusquery/{id}', [AdminContactQueryController::class,'show'])->name('contactqueries.show');
    Route::post('/manage-conactusquery/reply', [AdminContactQueryController::class,'reply'])->name('contactqueries.reply');
    Route::post('/manage-conactusquery/{id}/read', [AdminContactQueryController::class,'markAsRead'])->name('contactqueries.markread');
    Route::delete('/manage-conactusquery/{id}', [AdminContactQueryController::class,'destroy'])->name('contactqueries.destroy');

    // Testimonials (approve/delete)
    Route::get('/testimonials', [AdminTestimonialController::class,'index'])->name('testimonials.index');
    Route::post('/testimonials/{id}/status', [AdminTestimonialController::class,'setStatus'])->name('testimonials.setstatus');
    Route::delete('/testimonials/{id}', [AdminTestimonialController::class,'destroy'])->name('testimonials.destroy');

    // Subscribers
    Route::get('/manage-subscribers', [AdminSubscriberController::class,'index'])->name('subscribers.index');
    Route::delete('/manage-subscribers/{id}', [AdminSubscriberController::class,'destroy'])->name('subscribers.destroy');

    // Contact Info Management
    Route::get('/contact-info', [AdminContactInfoController::class,'edit'])->name('contact-info.edit');
    Route::put('/contact-info', [AdminContactInfoController::class,'update'])->name('contact-info.update');
    });
});
