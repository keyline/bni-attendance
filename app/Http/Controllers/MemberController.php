<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MemberController extends Controller
{

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
                         return redirect()->route('club-meeting-day', ['club_id'=>Helper::encoded($user->club_id)]);
                       }
                }
            }

            //  If user not found or password mismatch
            return back()->withErrors(['phoneOrEmail' => 'Invalid credentials']);
        }

        return view('Admin.signIn');
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

        return view('Admin.admin-dashboard', ['admin' => $admin, 'members' => $members, 'clubs' => $clubs]);
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

        return view('Admin.user-dashboard', ['user' => $user, 'members' => $members, 'club' => $club]);
    }

    // ADD MEMBER (ADMIN ONLY)
    public function add(Request $request, $club_id = null, $member_id = null)
    {     
        $clubId = Helper::decoded($club_id);
        $memberId = $member_id ? Helper::decoded($member_id) : null;
        try{
        $admin = session('user');
        $member = DB::table('member')->where('id', $memberId)->first();      
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
                'club_id'     => $clubId ?? $admin->club_id,
                'member_type' => $data['user_type'],
            ];

            if (!empty($data['password'])) {
                // $items['password'] = $data['password'];
                 $items['password'] = Hash::make($data['password']);
            }


               if ($memberId) {
                DB::table('member')->where('id', $memberId)->update($items);
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
        return view('Admin.add-edit', ['clubs' => $clubs, 'admin' => $admin, 'member' => $member]);
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
    public function index($club_id)
    {
        $clubId = Helper::decoded($club_id);
        $club = DB::table('club')->where('id', '=', $clubId)->first();
        return view('Admin.index')->with('club', $club);
    }

    public function userSignIn(Request $request, $club_id)
    {
            $clubId = Helper::decoded($club_id);
            $club = DB::table('club')->where('id', '=', $clubId)->first();
            // Helper::pr($club); die;
                if ($request->isMethod('post')) {
            // Validate input
            $data = $request->validate([
                'phone' => 'required|regex:/^\d{10}$/',
            ]);


            // Find user by phone
            $user = DB::table('member')
                ->where('phone', $data['phone'])
                ->where('club_id', $clubId)
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
                                ->route('attending-listing', ['club_id' => Helper::encoded($clubId)])
                              ->with(['members' => $members, 'club' => $club,  'user' => $user])
                              ->with('success', 'Welcome ' . $user->name . ', your attendance has been marked successfully.');
                    }else{
                        return back()->withErrors(['Today is not your meeting day.']);
                    }

            }
            return back()->withErrors(['Error!! Please call admin.']);
        }
        return view('User.user-signIn')->with('club', $club);
      
    }
    
    public function substituteSignIn(Request $request, $club_id)
    {
            $clubId = Helper::decoded($club_id);
            $club = DB::table('club')->where('id', '=', $clubId)->first();
            $members = DB::table('member')->where('club_id', '=', $clubId)->get();
                if ($request->isMethod('post')) {
            // Validate input
            $data = $request->validate([
                'memberId' => 'required',
                'substituteName' => 'required',
                'substitutePhone' => 'required',
            ]);


            // Find user by phone
            $user = DB::table('member')
                ->where('id', $data['memberId'])
                ->where('club_id', $clubId)
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
                                ->where('club_id', $user->club_id)
                                ->where('date', date('Y-m-d')) // exact match, works since it's DATE
                                ->exists();


                        if ($alreadyExists) {
                            return back()->withErrors(['You have already marked attendance today.']);
                        }
                     $items = [
                         'member_id' => $user->id,
                         'club_id' => $user->club_id,
                         'is_substitute' => 1,
                         'substitute_name' => $data['substituteName'],
                         'substitute_phone' => $data['substitutePhone'],
                         'date'      => date('Y-m-d'),   // fills the DATE column
                         'time'      => date('H:i:s'),   // fills the TIME column
                     ];
                     DB::table('attendance')->insert($items);

                     
                        $members = DB::table('member')->where('club_id', '=', $user->club_id)->get();
                        $club = DB::table('club')->where('id', '=', $user->club_id)->first();

                     return redirect()
                                ->route('substitute-attending-listing', ['club_id' => Helper::encoded($clubId)])
                              ->with(['members' => $members, 'club' => $club,  'user' => $user, 'substituteName' => $data['substituteName']])
                              ->with('success', 'Welcome ' . $data['substituteName'] . "( Substitute of " . $user->name . '), your attendance has been marked successfully.');
                    }else{
                        return back()->withErrors(['Today is not your meeting day.']);
                    }

            }
            return back()->withErrors(['Error!! Please call admin.']);
        }
        return view('Substitute.substitute-signIn')->with('club', $club)->with('members', $members);
      
    }

    public function guestSignIn(Request $request, $club_id = null)
    {
        // 1) Prefer path param; fallback to posted club_id
        $rawClubId = $club_id ?? $request->input('club_id');

        if (empty($rawClubId)) {
            return redirect()->back()->withErrors(['Club ID missing.']);
        }

        // 2) Decode if needed (allow numeric ids too)
        try {
            if (is_numeric($rawClubId)) {
                $clubId = (int) $rawClubId;
            } else {
                $clubId = Helper::decoded($rawClubId);
            }
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['Invalid club id.']);
        }

        // 3) Load club once and check
        $club = DB::table('club')->where('id', '=', $clubId)->first();
        $members = DB::table('member')->where('club_id', '=', $clubId)->get();
        if (! $club) {
            return redirect()->back()->withErrors(['Club not found.']);
        }

        // 4) Handle POST
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'memberId'  => 'required',
                'guestName'  => 'required|string|max:255',
                'guestPhone' => ['required', 'regex:/^[6-9]\d{9}$/'],
            ]);

            // If phone belongs to an existing member, block or handle accordingly
            $user = DB::table('member')->where('phone', $data['guestPhone'])->first();
            if ($user) {
                return back()->withErrors(['This phone belongs to a registered member.']);
            }

            // Check meeting day
            if ($club->meeting_day !== date('l')) {
                return back()->withErrors(['Today is not your meeting day.']);
            }

            // Prevent duplicate guest attendance
            $alreadyExists = DB::table('guest_attendance')
                ->where('phone', $data['guestPhone'])
                ->where('club_id', $clubId)
                ->where('date', date('Y-m-d'))
                ->exists();

            if ($alreadyExists) {
                return back()->withErrors(['You have already marked attendance today.']);
            }

            DB::table('guest_attendance')->insert([
                'member_id'   => $data['memberId'],
                'club_id'     => $clubId,
                'name'  => $data['guestName'],
                'phone' => $data['guestPhone'],
                'date'        => date('Y-m-d'),
                'time'        => date('H:i:s'),
            ]);

            $club = DB:: table('club')->where('id', '=', $clubId)->first();

            return redirect()
                ->route('guest-attending-listing', ['club_id' => Helper::encoded($clubId)])
                ->with(['guestName' => $data['guestName']])
                ->with('success', 'Welcome ' . $data['guestName'] . ' (Guest of ' . $club->club_name . '), your attendance has been marked successfully.');
        }

        // 5) GET — show view
        return view('Guest.guest-signIn')->with('club', $club)->with('members', $members);
    }


    // ATTENDING LISTING
    public function attendingListing($club_id)
    {
        $user = session('user');
        $clubId = Helper::decoded($club_id);
            $attendances = DB::table('attendance')->where('member_id', $user->id)->get();
            $club = DB::table('club')->where('id', '=', $clubId)->first();
            return view('User.user-attending-listing', ['attendances' => $attendances, 'club' => $club, 'user' => $user]);
    }

    // SUBSTITUE ATTENDING  LISTING
    public function substituteAttendingListing($club_id)
    {
        $user = session('user');
        $clubId = Helper::decoded($club_id);
        $substituteName = session('substituteName');
            $attendances = DB::table('attendance')->where('member_id', $user->id)->get();
            $club = DB::table('club')->where('id', '=', $clubId)->first();
            return view('Substitute.substitute-attending-listing', ['attendances' => $attendances, 'club' => $club, 'user' => $user, 'substituteName' => $substituteName]);
    }    

    // GUEST ATTENDING  LISTING
    public function guestAttendingListing($club_id)
    {
        $user = session('user');
        $clubId = Helper::decoded($club_id);
        // echo ($user['guestName']); die;
        // $substituteName = session('substituteName');
            // $attendances = DB::table('guest_attendance')->where('member_id', $user['guestPhone'])->get();
            $club = DB::table('club')->where('id', '=', $clubId)->first();
            return view('Guest.guest-attending-listing', ['club' => $club, 'user' => $user]);
    } 
    

    // ADD CLUB (SUPER ADMIN ONLY)
    public function addClub(Request $request, $club_id = null)
    {
        // if coming from POST, club_id will be in hidden input
        $club_id = $request->input('club_id', $club_id);

        // Fetch club data if editing (GET request)
        $club = null;
        if ($request->isMethod('get') && $club_id) {
            $club = DB::table('club')->where('id', Helper::decoded($club_id))->first();
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
        return view('Admin.add-club', compact('club'));
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
        // dd($clubs);
        return view('Admin.club-listing', ['supadmin' => $supadmin, 'clubs' => $clubs, 'admins' => $admins]);
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
      return view('Admin.add-edit', ['member' => $member ?? '','clubs' => $clubs, 'admin' => $admin]);
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
       

        public function clubMeetingDay($club_id)
    {
           $clubId = Helper::decoded($club_id);
            $admin = session('user');
        if (!$admin || $admin->member_type != 3 && $admin->member_type != 1) {
            // dd($admin); die;
            return redirect()->route('signIn')->withErrors(['You must be a super admin to access this page']);
        }
                $club = DB::table('attendance')->where('club_id', $clubId)->get();
                    if ($club->isEmpty()) {
                    return back()->withErrors(['No attendance records found for this club']);
                    }
                // $club_id = $club->first()->club_id;
                $clubDates = $club->pluck('date')
                                  ->unique()
                                  ->sortDesc()   
                                  ->values()
                                  ->all();

                $selected_club = DB::table('club')->where('id', $clubId)->first();
            $clubs = DB::table('club')->get();
            return view('Admin.club-meetings', [
                'admin' => $admin,
                'selected_club' => $selected_club,
                'dates' => $clubDates,
                'clubs' => $clubs
            ]);
    }

    public function clubMeetingAttendMember($selected_club, $clubMeetingDate)
    {  
        $selectedClubId = Helper::decoded($selected_club);
        $admin = session('user');
        if (!$admin || $admin->member_type != 3 && $admin->member_type != 1) {
            return redirect()->route('signIn')->withErrors(['You must be a super admin to access this page']);
        }

        $members = DB::table('member')
            ->where('club_id', $selectedClubId)
            ->get();
        $clubs = DB::table('club')
            ->get();
              $clubMeetingDateFormatted = Carbon::parse($clubMeetingDate)->format('d/m/Y');
                $attdArray = DB::table('attendance')
                            ->where('club_id', $selectedClubId)
                            ->where('date', $clubMeetingDate)
                            ->get(['member_id', 'time', 'is_substitute', 'substitute_name', 'substitute_phone'])
                            ->keyBy('member_id')
                            ->toArray();
                $guests = DB::table('guest_attendance')
                            ->where('club_id', $selectedClubId)
                            ->where('date', $clubMeetingDate)
                            ->get()
                            ->toArray();            
            $present = collect($attdArray)->sortByDesc(fn($m) => Carbon::parse($m->time));
            $absent = $members->whereNotIn('id', array_keys($attdArray));
            // Helper::pr($present); die;
            return view('Admin.club-meeting-attend-member', [
                'admin' => $admin,
                'selected_club' => DB::table('club')->where('id', $selectedClubId)->first(),
                // 'attendMembers' => $attendMembers,
                'members' => $members,
                'present' => $present,
                'absent' => $absent,
                'clubs' => $clubs,
                'clubMeetingDate' => $clubMeetingDateFormatted,
                'allguests' => $guests
            ]);

    }


    public function getMembers($clubId)
    {
        $members = DB::table('member')->where('club_id', $clubId)->get();
        return response()->json($members);
    }

      
}



