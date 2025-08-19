<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\DivorceCaseController;
use App\Http\Controllers\EpaymentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ObligationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfileRoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/profile-roles/export', [ProfileRoleController::class, 'export'])
//     ->name('profile-roles.export')
//     ->middleware('can:export,App\Models\ProfileRole');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('profile-roles', ProfileRoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('divorce-cases', DivorceCaseController::class);
    Route::post('/users/{user}/profile-roles/store', [ProfileRoleController::class, 'store'])->middleware(['auth', 'can:create,profileRole']);
    Route::patch('/users/{user}/profile-roles/{profileRole}', [ProfileRoleController::class, 'update'])->middleware(['auth', 'can:update,profileRole']);
    Route::patch('/profile-roles/{profileRole}/review', [ProfileRoleController::class, 'review'])->middleware('can:changeStatus,profileRole')->name('profile-roles.review');
    Route::get('/profile-roles/{profileRole}/review', [ProfileRoleController::class, 'showReview'])->middleware('can:changeStatus,profileRole')->name('profile-roles.show-review');

    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
    ->middleware('can:changeStatus,user')
    ->name('users.toggleStatus');

    Route::resource('divorce-cases', DivorceCaseController::class);
    Route::get('my-divorce-cases', [DivorceCaseController::class,'userIndex'])->name('divorce-cases.userIndex');

    Route::resource('divorce-cases.children', ChildController::class);
    Route::resource('divorce-cases.obligations', ObligationController::class)->except('index');
    Route::resource('divorce-cases.payments', PaymentController::class);
    Route::patch('/divorce-cases/{divorceCase}/payments/{payment}/pay', [PaymentController::class, 'pay'])->name('payments.pay');
    Route::patch('payments/{payment}/review', [PaymentController::class, 'review'])->name('payments.review');
    Route::get('payments/{payment}/epay', [PaymentController::class, 'listEpay'])->name('payments.epay');
    Route::post('payments/{payment}/success', [PaymentController::class, 'success'])->name('payments.success');
    Route::post('payments/{payment}/fail', [PaymentController::class, 'fail'])->name('payments.fail');

    Route::get('payments/{payment}/epayments', [EpaymentController::class, 'index'])->name('epayments.index');
    Route::get('payments/{payment}/epayments/{epayment}', [EpaymentController::class, 'show'])->name('epayments.show');

    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
    ->name('notifications.read');

    Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');


    Route::get('/payments/export-late', [PaymentController::class, 'exportLatePayments'])
    ->name('payments.export-late')
    ->middleware('can:exportLatePayments,App\Models\Payment');

    Route::get('/profiles/export', [ProfileRoleController::class, 'export'])
    ->name('profile-roles.export')
    ->middleware('can:export,App\Models\ProfileRole');


    
});

require __DIR__.'/auth.php';
