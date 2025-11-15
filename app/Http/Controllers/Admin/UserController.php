<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Designation;
use App\Models\Department;
use Spatie\Permission\Models\Role;
use DB;
use Auth;
use App\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Log;
use Exception;

class UserController extends Controller
{
    public function downloadUserExcel()
    {
        try {
            return Excel::download(new UserExport, 'userList.xlsx');
        } catch (Exception $e) {
            Log::error('Failed to export users to Excel: ' . $e);
            return redirect()->back()->with('error', 'Could not export Excel file. Please check logs.');
        }
    }

    public function downloadUserPdf()
    {
        try {
            $userList = User::where('id', '!=', 1)->where('user_type', 2)->latest()->get();
            $html = view('admin.users._partial.pdfSheet', ['userList' => $userList])->render();
            $mpdf = new Mpdf();
            $mpdf->WriteHTML($html);
            return response($mpdf->Output('userList.pdf', 'D'))
                ->header('Content-Type', 'application/pdf');
        } catch (Exception $e) {
            Log::error('Failed to export users to PDF: ' . $e);
            return response("Could not generate PDF. Please check logs.", 500);
        }
    }

    public function data(Request $request)
{
    // Eager load relationships
    $query = User::with(['department', 'designation', 'roles'])
                 ->where('id', '!=', 1)
                 ->where('user_type', 2)
                 ->where('status',1);

    // Search by name, phone, or email
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', "%{$request->search}%")
              ->orWhere('phone', 'like', "%{$request->search}%")
              ->orWhere('email', 'like', "%{$request->search}%");
        });
    }

    // Sorting
    $sort = $request->get('sort', 'id');
    $direction = $request->get('direction', 'desc');
     $query->orderBy('id','desc');

    // Pagination
    $perPage = $request->get('perPage', 10);
    $paginated = $query->paginate($perPage);

    // Transform each user record
    $data = $paginated->getCollection()->map(function ($user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
            'status' => $user->status,
            'viewpassword' => $user->viewpassword,
            'image' => $user->image,
            'signature' => $user->signature, 
            // Use eager-loaded relationships
            'department_name' => $user->department ? $user->department->name : null,
            'designation_name' => $user->designation ? $user->designation->name : null,
            'roles' => $user->roles->pluck('name'), // Pluck names from the loaded collection
        ];
    });

    return response()->json([
        'data' => $data,
        'total' => $paginated->total(),
        'current_page' => $paginated->currentPage(),
        'last_page' => $paginated->lastPage(),
        'can_edit' => Auth::user()->can('userUpdate'),
         'can_show' => Auth::user()->can('userView'),
        'can_delete' => Auth::user()->can('userDelete'),
    ]);
}

    public function index(Request $request): View
    {
        // CommonController::addToLog('user page view');
        $data = User::where('id', '!=', 1)->where('user_type', 2)->latest()->get();
        return view('admin.users.index', compact('data'));
    }

    public function activeOrInActiveUser($status, $id): RedirectResponse
    {
        try {
            // CommonController::addToLog('user active or inactive');
            $user = User::findOrFail($id);
            $user->status = $status;
            $user->save();
            Log::info('User status updated successfully.', ['user_id' => $id, 'new_status' => $status]);
            return redirect()->route('users.index')->with('success', 'User status updated successfully');
        } catch (Exception $e) {
            Log::error("Failed to update status for user ID {$id}: " . $e);
            return redirect()->route('users.index')->with('error', 'Failed to update user status.');
        }
    }

    public function create(): View
    {
        try {
            // CommonController::addToLog('user create');
            $roles = Role::pluck('name', 'name')->all();
            $designationList = Designation::latest()->get();
            $departmentList = Department::latest()->get();
            return view('admin.users.create', compact('roles', 'designationList', 'departmentList'));
        } catch (Exception $e) {
            Log::error('Failed to load create user page: ' . $e);
            return redirect()->back()->with('error', 'Could not load the page.');
        }
    }

    public function store(Request $request): RedirectResponse
    {

        //dd($request->all());
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password|max:8', // Updated
            'roles' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:300', // Updated
            'signature' => 'nullable|image|mimes:png|max:300' // Added
        ]);

        CommonController::addToLog('user store');

        $time_dy = time().date("Ymd");
    
        $input = $request->all();
        $input['viewpassword'] = $input['password'];
        $input['password'] = Hash::make($input['password']);
        
        // Handle Profile Image
        if ($request->hasfile('image')) {
            $productImage = $request->file('image');
            $imageName = 'profileImage'.$time_dy.$productImage->getClientOriginalName();
            $directory = 'public/uploads/';
            $imageUrl = $directory.$imageName;

            $img=Image::read($productImage)->resize(300,300); // Resize to 300x300
            $img->save($imageUrl);

            $input['image'] =  'public/uploads/'.$imageName;
        }else{
            $input['image'] = null;
        }

        // Handle Signature
        if ($request->hasfile('signature')) {
            $signatureFile = $request->file('signature');
            $signatureName = 'signature'.$time_dy.$signatureFile->getClientOriginalName();
            $directory = 'public/uploads/signatures/';
            if (!File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }
            $signatureUrl = $directory.$signatureName;

            $img=Image::read($signatureFile)->resize(300,80); // Resize to 300x80
            $img->save($signatureUrl);

            $input['signature'] = $signatureUrl;
        } else {
            $input['signature'] = null;
        }

        // $input['is_shareholder'] = $request->boolean('is_shareholder'); // Removed
        $input['status'] = 1;
        $input['user_type'] = 2;
    
        $user = User::create($input);
        $user->assignRole($request->input('roles'));
    
        return redirect()->route('users.index')
                        ->with('success','User created successfully');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        // Eager load relationships
        $user = User::with(['department', 'designation'])->find($id);

        return view('admin.users.show',compact('user'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {

        CommonController::addToLog('user edit');

        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        $designationList = Designation::latest()->get();
    

            $departmentList = Department::latest()->get();


        return view('admin.users.edit',compact('user','roles','userRole','designationList','departmentList'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|same:confirm-password|max:8', // Updated
            'roles' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:300', // Updated
            'signature' => 'nullable|image|mimes:png|max:300' // Added
        ]);

        CommonController::addToLog('user update');
    
        $input = $request->all();
        $user = User::find($id);
        $time_dy = time().date("Ymd");


        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
             $input['viewpassword'] = $request->password;
        }else{
            $input = Arr::except($input,array('password'));    
             // Keep old viewpassword if password is not changed
             $input['viewpassword'] = $user->viewpassword;
        }

        // Handle Profile Image
        if ($request->hasfile('image')) {
            // Delete old image
            if (File::exists(public_path($user->image))) {
                File::delete(public_path($user->image));
            }

            $productImage = $request->file('image');
            $imageName = 'profileImage'.$time_dy.$productImage->getClientOriginalName();
            $directory = 'public/uploads/';
            $imageUrl = $directory.$imageName;

            $img=Image::read($productImage)->resize(300,300); // Resize to 300x300
            $img->save($imageUrl);

            $input['image'] = 'public/uploads/'.$imageName;
        }else{
            $input['image'] = $user->image;
        }
        
        // Handle Signature
        if ($request->hasfile('signature')) {
            // Delete old signature
            if (File::exists(public_path($user->signature))) {
                File::delete(public_path($user->signature));
            }

            $signatureFile = $request->file('signature');
            $signatureName = 'signature'.$time_dy.$signatureFile->getClientOriginalName();
            $directory = 'public/uploads/signatures/';
             if (!File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }
            $signatureUrl = $directory.$signatureName;

            $img=Image::read($signatureFile)->resize(300,80); // Resize to 300x80
            $img->save($signatureUrl);

            $input['signature'] = $signatureUrl;
        } else {
            $input['signature'] = $user->signature;
        }


         // $input['is_shareholder'] = $request->boolean('is_shareholder'); // Removed
        $input['status'] = 1;
         $input['user_type'] = 2;
    
        
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        $user->assignRole($request->input('roles'));
    
        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        try {
            // CommonController::addToLog('user delete');
            User::findOrFail($id)->delete();
            Log::info('User deleted successfully.', ['user_id' => $id]);
            return redirect()->route('users.index')->with('success', 'User deleted successfully');
        } catch (Exception $e) {
            Log::error("Failed to delete user ID {$id}: " . $e);
            return redirect()->route('users.index')->with('error', 'Failed to delete user.');
        }
    }

    public function shareholderIndex(): View
    {
        try {
            return view('admin.users.shareholders');
        } catch (Exception $e) {
            Log::error('Failed to load shareholder index page: ' . $e);
            return redirect()->back()->with('error', 'Could not load the page.');
        }
    }

    public function shareholdersData(Request $request)
    {
        try {
            // OPTIMIZED: Eager load department
            $query = User::with('department')->where('is_shareholder', true);

            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")
                      ->orWhere('email', 'like', "%{$request->search}%");
                });
            }

            $query->orderBy($request->get('sort', 'id'), $request->get('direction', 'desc'));
            $paginated = $query->paginate($request->get('perPage', 10));

            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'from' => $paginated->firstItem(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'can_edit' => Auth::user()->can('userUpdate'),
                'can_delete' => Auth::user()->can('userDelete'),
                'can_show' => Auth::user()->can('userView'),
            ]);
        } catch (Exception $e) {
            Log::error('Failed to fetch shareholder data: ' . $e);
            return response()->json(['error' => 'Failed to retrieve data.'], 500);
        }
    }
}