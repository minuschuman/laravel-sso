<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Teamwork\TeamController;
use App\Http\Controllers\Teamwork\TeamMemberController;
use App\Http\Controllers\Teamwork\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


Route::prefix('admin')->namespace('App\Http\Controllers\Admin')->middleware(['auth'])->group(function () {
    Route::resource('permission', PermissionController::class);
    Route::resource('role', RoleController::class);
    Route::resource('user', UserController::class);
});

Route::get('/owner', function () {
    return "Owner of current team.";
})->middleware('auth', 'teamowner');


/**
 * Teamwork routes
 */
Route::group(['prefix' => 'teams', 'namespace' => 'Teamwork'], function () {
    Route::get('/', [TeamController::class, 'index'])->name('teams.index');

    Route::middleware('role:super-admin')->get('create', [TeamController::class, 'create'])->name('teams.create');
    // Route::get('create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('teams', [TeamController::class, 'store'])->name('teams.store');
    Route::get('edit/{id}', [TeamController::class, 'edit'])->name('teams.edit');
    Route::put('edit/{id}', [TeamController::class, 'update'])->name('teams.update');
    Route::delete('destroy/{id}', [TeamController::class, 'destroy'])->name('teams.destroy');
    Route::get('switch/{id}', [TeamController::class, 'switchTeam'])->name('teams.switch');

    Route::get('members/resend/{invite_id}', [TeamMemberController::class, 'resendInvite'])->name('teams.members.resend_invite');
    Route::post('members/{id}', [TeamMemberController::class, 'invite'])->name('teams.members.invite');
    Route::get('accept/{token}', [AuthController::class, 'acceptInvite'])->name('teams.accept_invite');

    Route::get('{team}/members', [TeamMemberController::class, 'index'])->name('teams.members.index');
    Route::get('{team}/members/add', [TeamMemberController::class, 'create'])->name('teams.user.add');
    Route::post('{team}/members/store', [TeamMemberController::class, 'store'])->name('teams.members.store');
    Route::delete('{team}/members/{user_id}', [TeamMemberController::class, 'destroy'])->name('teams.members.destroy');
});
