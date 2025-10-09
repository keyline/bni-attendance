<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
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

// Route::get('/', function () {
//     return view('welcome');
// });
// signin and listing routes
Route::match(['get', 'post'], '/admin', [MemberController::class, 'signIn'])->name('signIn');
Route::match(['get', 'post'], '/admin/user-signin', [MemberController::class, 'userSignIn'])->name('user-signin');
Route::match(['get', 'post'], '/admin/substitute-signin', [MemberController::class, 'substituteSignIn'])->name('user-signin');
Route::match(['get', 'post'], '/admin/admin-listing', [MemberController::class, 'listing'])->name('admin-listing');
Route::match(['get', 'post'], '/admin/user-listing', [MemberController::class, 'userProfile'])->name('user-listing');
Route::match(['get', 'post'], '/admin/members/add/{club_id?}/{member_id?}', [MemberController::class, 'add'])->name('members.add');
Route::match(['get', 'post'], '/admin/members/logout', [MemberController::class, 'logout'])->name('member.logout');

//atteding routes
Route::match(['get', 'post'], '/attending', [MemberController::class, 'attending'])->name('attending');
Route::get('/attending-listing', [MemberController::class, 'attendingListing'])->name('attending-listing');

//Super Admin routes
Route::match(['get', 'post'], '/add-club/{club_id?}', [MemberController::class, 'addClub'])->name('add-club');
Route::match(['get', 'post'], '/club-listing', [MemberController::class, 'clubListing'])->name('club-listing');

// Member Edit Delete routes
Route::match(['get', 'post'], '/club-member-edit/{id}', [MemberController::class, 'clubMemberEdit'])->name('club-edit');
Route::match(['get', 'post'], '/club-member-delete/{id}', [MemberController::class, 'clubMemberDelete'])->name('club-delete');

// For checking email and phone
Route::post('/check-email', [MemberController::class, 'checkEmail'])->name('check.email');
Route::post('/check-phone', [MemberController::class, 'checkPhone'])->name('check.phone');

// club meting day listing
Route::match(['get', 'post'], '/club-meeting-day/{club_id}', [MemberController::class, 'clubMeetingDay'])->name('club-meeting-day');
Route::match(['get', 'post'], '/club-meeting-attend-member/{selected_club}/{club_meeting_date}', [MemberController::class, 'clubMeetingAttendMember'])->name('club-meeting-attend-member');


// Ajax member fetching
Route::prefix('admin')->group(function () {
    Route::get('/get-members/{clubId}', [MemberController::class, 'getMembers'])->name('get.members');
});
