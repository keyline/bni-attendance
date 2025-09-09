<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon; 

class MemberController extends Controller
{
    // SIGN IN METHOD
    // public function signIn(Request $request)
    // {
    //     if ($request->isMethod('post')) {
    //         // Validate input
    //         $data = $request->validate([
    //             'phoneOrEmail' => 'required|string',
    //             'password'     => 'required|string',
    //         ]);

    //         // Find user by phone OR email + password (plain text)
    //         $user = DB::table('member')
    //             ->where(function ($query) use ($data) {
    //                 $query->where('phone', $data['phoneOrEmail'])
    //                       ->orWhere('email', $data['phoneOrEmail']);
    //             })
    //             ->where('password', $data['password'])
    //             ->first();

    //         if ($user) {
    //             // Store in session
    //             session(['user' => $user]);

    //             // Redirect based on role
    //             if ($user->member_type == 2) {
    //         return back()->withErrors(['You must be an admin.']);
    //             } else {
    //                 return redirect()->route('admin-listing');
    //             }
    //         }

    //         return back()->withErrors(['phoneOrEmail' => 'Invalid credentials']);
    //     }

    //     return view('signIn');
    // }

    // Sign in
    public function signIn(Request $request)
    {
        if ($request->isMethod('post')) {
            // Validate input
            $data = $request->validate([
                'phoneOrEmail' => 'required|string',
                'password'     => 'required|string',
            ]);

            // Find user by phone OR email (don't check password here)
            $user = DB::table('member')
                ->where('phone', $data['phoneOrEmail'])
                ->orWhere('email', $data['phoneOrEmail'])
                ->first();

            if ($user && Hash::check($data['password'], $user->password)) {
                //  Password matched

                // Store in session
                session(['user' => $user]);

                // Redirect based on role
                if ($user->member_type == 2) {
                    return back()->withErrors(['You must be an admin.']);
                } else {
                       // return redirect()->route('admin-listing');
                       if ($user->member_type == 3) {
                        return redirect()->route('club-listing');
                       }else{
                         return redirect()->route('club-meeting-day', ['club_id'=>$user->club_id]);
                       }
                }
            }

            //  If user not found or password mismatch
            return back()->withErrors(['phoneOrEmail' => 'Invalid credentials']);
        }

        return view('signIn');
    }


    // ADMIN LISTING
    public function listing(Request $request)
    {
        $admin = session('user');
      //   dd($admin); die;
        if (!$admin || $admin->member_type == 2) {
            return redirect()->route('signIn')->withErrors(['You must be an admin to access this page']);
        }

        if($admin->member_type == 3) {
                $members = DB::table('member')->where('id', '!=', $admin->id)->get();
        }elseif($admin->member_type == 1){
          $members = DB::table('member')->where('id', '!=', $admin->id)->where('club_id', '=', $admin->club_id)->get();
        }
       //    dd($members); die;
        $clubs = DB::table('club')->get();

        return view('admin-dashboard', ['admin' => $admin, 'members' => $members, 'clubs' => $clubs]);
    }

    // USER PROFILE
    public function userProfile(Request $request)
    {
        $user = session('user');

        if (!$user || $user->member_type != 2) {
            return redirect()->route('signIn')->withErrors(['Please log in as user']);
        }

        $members = DB::table('member')->where('club_id', '=', $user->club_id)->get();
        $club = DB::table('club')->where('id', '=', $user->club_id)->first();
        // $meeting = DB::table('meeting')->where('club_id', '=', $user->club_id)->first();

        return view('user-dashboard', ['user' => $user, 'members' => $members, 'club' => $club]);
    }

    // ADD MEMBER (ADMIN ONLY)
    public function add(Request $request, $club_id = null, $member_id = null)
    { try{
        $admin = session('user');
        $member = DB::table('member')->where('id', $member_id)->first();      
        if (!$admin || $admin->member_type == 2) {
            return redirect()->route('signIn')->withErrors(['You must be an admin to add members']);
        }

        if ($request->isMethod('post')) {
            $data = $request->validate([
                'name'     => 'required|string',
                'phone'    => 'required|numeric',
                'email'    => 'required|email',
                'password' => 'nullable|string|min:3',
                'user_type' => 'required|in:1,2', // 1 for admin, 2 for user
            ]);

            $items = [
                'name'        => $data['name'],
                'phone'       => $data['phone'],
                'email'       => $data['email'],
                'club_id'     => $club_id ?? $admin->club_id,
                'member_type' => $data['user_type'],
            ];

            if (!empty($data['password'])) {
                // $items['password'] = $data['password'];
                 $items['password'] = Hash::make($data['password']);
            }


               if ($member_id) {
                DB::table('member')->where('id', $member_id)->update($items);
                $message = 'Member updated successfully';
            } else {
                // dd($items); die;
               $data =  DB::table('member')->insert($items);
            //    dd($data); die;
                $message = 'Member added successfully';
            };

            return redirect()->route('admin-listing')->with('success', 'Member added successfully');
        }else {
                    $clubs = DB::table('club')->get();

        // return view('add-edit')->with('clubs', DB::table('club')->get());
        return view('add-edit', ['clubs' => $clubs, 'admin' => $admin, 'member' => $member]);
        }
    }catch (\Exception $e) {

        echo "Error: " . $e->getMessage();
        exit;
              }
 
    }

    // LOGOUT
    public function logout()
    {   
        if(session('user')->member_type == 2){
            session()->forget('user');
            return redirect()->route('user-signin')->with('success', 'Logged out successfully');
        }else{
            session()->forget('user');
            return redirect()->route('signIn')->with('success', 'Logged out successfully');
        }
    }

    // ATTENDING METHOD
    public function userSignIn(Request $request)
    {
                if ($request->isMethod('post')) {
            // Validate input
            $data = $request->validate([
                'phone' => 'required|regex:/^\d{10}$/',
            ]);


            // Find user by phone
            $user = DB::table('member')
                ->where('phone', $data['phone'])
                ->first();

            if ($user) {
                 if($user->member_type == 3){
                    return back()->withErrors(['This route not for super admin']);
                 }
                session(['user' => $user]);
                $meeting = DB::table('club')->where('id', '=', $user->club_id)->first();
                       if($meeting->meeting_day == date('l')) {
                            $alreadyExists = DB::table('attendance')
                                ->where('member_id', $user->id)
                                ->where('date', date('Y-m-d')) // exact match, works since it's DATE
                                ->exists();


                        if ($alreadyExists) {
                            return back()->withErrors(['You have already marked attendance today.']);
                        }
                     $items = [
                         'member_id' => $user->id,
                         'club_id' => $user->club_id,
                         'date'      => date('Y-m-d'),   // fills the DATE column
                         'time'      => date('H:i:s'),   // fills the TIME column
                     ];
                     DB::table('attendance')->insert($items);

                     
                        $members = DB::table('member')->where('club_id', '=', $user->club_id)->get();
                        $club = DB::table('club')->where('id', '=', $user->club_id)->first();

                     return redirect()
                                ->route('attending-listing')
                              ->with(['members' => $members, 'club' => $club,  'user' => $user])
                              ->with('success', 'Welcome ' . $user->name . ', your attendance has been marked successfully.');
                    }else{
                        return back()->withErrors(['Today is not your meeting day.']);
                    }

            }
            return back()->withErrors(['Error!! Please call admin.']);
        }
        return view('user-signIn');
      
    }
    
    // ATTENDING LISTING
    public function attendingListing()
    {
        $user = session('user');
        
        // if ($user && $user->member_type == 2) {
            $attendances = DB::table('attendance')->where('member_id', $user->id)->get();
            $clubs = DB::table('club')->get();
            return view('user-attending-listing', ['attendances' => $attendances, 'clubs' => $clubs, 'user' => $user]);
        // }
        // elseif($user && ($user->member_type == 1 || $user->member_type == 3)) {
        //        if($user->member_type == 3) {
        //             $attendances = DB::table('attendance')->get();
        //        }else{
        //            $attendances = DB::table('attendance')->where('club_id', $user->club_id)->get();
        //        }
             
        //         //   dd($attendances); die;
        //        $clubs = DB::table('club')->get();
        //        $members = DB::table('member')->where('id', '!=', $user->id)->get();
        //        return view('admin-attending-listing', ['attendances' => $attendances, 'clubs' => $clubs,  'admin' => $user, 'members' => $members]);
        // }
        //  return redirect()->route('signIn')->withErrors(['Please log in as user']);
    }

    // ADD CLUB (SUPER ADMIN ONLY)
    public function addClub(Request $request, $club_id = null)
    {
        // if coming from POST, club_id will be in hidden input
        $club_id = $request->input('club_id', $club_id);

        // Fetch club data if editing (GET request)
        $club = null;
        if ($club_id) {
            $club = DB::table('club')->where('id', $club_id)->first();
        }

        // Handle form submit
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'club_name'    => 'required|string',
                'meeting_day'  => 'required|string',
            ]);

            $items = [
                'club_name'   => $data['club_name'],
                'meeting_day' => $data['meeting_day'],
            ];

            if ($club_id) {
                // update
                $items['updated_at'] = Carbon::now();
                DB::table('club')->where('id', $club_id)->update($items);
                return redirect()->route('club-listing')->with('success', 'Club updated successfully');
            } else {
                // insert
                // echo $club_id ; die;
                DB::table('club')->insert($items);
                return redirect()->route('club-listing')->with('success', 'Club added successfully');
            }
        }

        // load form (either empty for add, or prefilled for edit)
        return view('add-club', compact('club'));
    }



    // CLUB LISTING (SUPER ADMIN ONLY)
    public function clubListing(Request $request)
    {
        $supadmin = session('user');
        if (!$supadmin || $supadmin->member_type != 3) {
            return redirect()->route('signIn')->withErrors(['You must be a super admin to access this page']);
        }


        $clubs = DB::table('club')->get();
        $admins = DB::table('member')->where('member_type', '=', '1')->get();
        return view('club-listing', ['supadmin' => $supadmin, 'clubs' => $clubs, 'admins' => $admins]);
    }

    // CLUB MEMBER EDIT (SUPER ADMIN ONLY)
    public function clubMemberEdit(Request $request, $id)
    {
         $admin = session('user');
        $member = DB::table('member')->where('id', $id)->first();
        if($request->isMethod('post')) {
            $data = $request->validate([
                'name'     => 'required|string',
                'phone'    => 'required|numeric',
                'email'    => 'required|email',
                'password' => 'nullable|string|min:3',
                'club_id'  => 'required|exists:club,id',
            ]);
            
            $items = [
                'name'     => $data['name'],
                'phone'    => $data['phone'],
                'email'    => $data['email'],
                'club_id'  => $data['club_id'],
            ];
     }
      $clubs = DB::table('club')->get();
      return view('add-edit', ['member' => $member ?? '','clubs' => $clubs, 'admin' => $admin]);
    }

    // Checking duplicate email 
    public function checkEmail(Request $request)
    {
            $query = DB::table('member')->where('email', $request->email);

            // If editing, ignore this member’s own email
            if ($request->member_id) {
                $query->where('id', '!=', $request->member_id);
            }

            $exists = $query->exists();

            return response()->json(['exists' => $exists]);
    }

    // Checking duplicate phone
    public function checkPhone(Request $request)
    {
            $query = DB::table('member')->where('phone', $request->phone);

            // If editing, ignore this member’s own email
            if ($request->member_id) {
                $query->where('id', '!=', $request->member_id);
            }

            $exists = $query->exists();

            return response()->json(['exists' => $exists]);
    }
        
    // CLUB Meeting Day
    public function clubMeetingDay($clubId)
    {
            $admin = session('user');
        if (!$admin || $admin->member_type != 3 && $admin->member_type != 1) {
            // dd($admin); die;
            return redirect()->route('signIn')->withErrors(['You must be a super admin to access this page']);
        }

        $club = DB::table('club')->where('id', $clubId)->first();
            // dd($club); die;

            // start date = created_at
            // $startDate = Carbon::parse($club->updated_at);

            // last meeting date (from attendance, fallback = today)
                // $endDate = Carbon::today();

            // Generate all dates between
            // $allDates = [];
            // for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            //     $allDates[] = $date->toDateString();
            // }
            $startDate = Carbon::parse($club->updated_at)->startOfDay();
            $endDate   = Carbon::today(); // already date-only at midnight

            $allDates = [];
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $allDates[] = $date->toDateString();
            }


            
            // $oldStartDate = Carbon::parse($club->created_at);
            // $oldEndDate = Carbon::parse($club->updated_at);
            // Generate all dates between
            // $allOldDates = [];
            // for ($date = $oldStartDate->copy(); $date->lte($oldEndDate); $date->addDay()) {
            //     $allOldDates[] = $date->toDateString();
            // }
            //  dd($allDates); die;
             $clubs = DB::table('club')->get();
            //  dd($clubs);die();
            return view('club-meetings', [
                'admin' => $admin,
                'selected_club' => $club,
                'dates' => $allDates,
                // 'oldDates' => $allOldDates,
                'clubs' => $clubs
            ]);
    }

    public function clubMeetingAttendMember($selectedClubId, $clubMeetingDate)
    {
        $admin = session('user');
        if (!$admin || $admin->member_type != 3 && $admin->member_type != 1) {
            return redirect()->route('signIn')->withErrors(['You must be a super admin to access this page']);
        }
        // $attendMembers = DB::table('attendance')
        //                  ->where('date', $clubMeetingDate)
        //                  ->where('club_id', $selectedClubId)->get();

                        //  dd($attendMembers); die; // Debugging line, remove in production

        $members = DB::table('member')
            ->where('club_id', $selectedClubId)
            ->get();
        $clubs = DB::table('club')
            ->get();
              $clubMeetingDateFormatted = Carbon::parse($clubMeetingDate)->format('d/m/Y');
            //   dd($members); die;

                $attdArray = DB::table('attendance')
                   ->where('club_id', $selectedClubId)
                   ->where('date', $clubMeetingDate)
                  ->pluck('time', 'member_id')
                  ->toArray();
            $present = collect($attdArray)
                ->sortByDesc(fn($time) => Carbon::parse($time)); 
            $absent = $members->whereNotIn('id', array_keys($attdArray));

            return view('club-meeting-attend-member', [
                'admin' => $admin,
                'selected_club' => DB::table('club')->where('id', $selectedClubId)->first(),
                // 'attendMembers' => $attendMembers,
                'members' => $members,
                'present' => $present,
                'absent' => $absent,
                'clubs' => $clubs,
                'clubMeetingDate' => $clubMeetingDateFormatted
            ]);

    }

      
}



    // ATTENDING METHOD  First Attempt
    // public function attending(Request $request)
    // {
    //             if ($request->isMethod('post')) {
    //         // Validate input
    //         $data = $request->validate([
    //             'phoneOrEmail' => 'required|string',
    //             'password'     => 'required|string',
    //         ]);


    //         // Find user by phone OR email + password (plain text)
    //         $user = DB::table('member')
    //             ->where(function ($query) use ($data) {
    //                 $query->where('phone', $data['phoneOrEmail'])
    //                       ->orWhere('email', $data['phoneOrEmail']);
    //             })
    //             ->where('password', $data['password'])
    //             ->first();

    //         if ($user && $user->email == session('user')->email) {
    //             $meeting = DB::table('meeting')->where('club_id', '=', $user->club_id)->first();
    //                  $items = [
    //                      'member_id' => $user->id,
    //                      'meeting_id' => $meeting->id,
    //                  ];
    //                  DB::table('attendance')->insert($items);
    //                  return redirect()->route('user-listing')->with('success', 'Attendance marked successfully');
    //         }

    //         return back()->withErrors(['phoneOrEmail' => 'Invalid credentials']);
    //     }
    //    $user = session('user');
    //   $meeting = DB::table('meeting')->where('club_id', '=', $user->club_id)->first();
    //    if($meeting->day == date('l')) {
    //           $alreadyExists = DB::table('attendance')
    //         ->where('member_id', $user->id)
    //         ->where('date', date('Y-m-d'))
    //         ->exists();

    //     if ($alreadyExists) {
    //         return redirect()->route('user-listing')->withErrors(['You have already marked attendance today.']);
    //     }
    //     return view('attending'); 
    //   } else {
    //     // return redirect()->route('user-listing')->withErrors(['Attendance is only available on the meeting day']);
    //     return back()->withErrors(['Attendance is only available on the meeting day']);
    //   }
      
    // }

