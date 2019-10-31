<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use DB;
use Validator;
use Datatables;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;
use Zizaco\Entrust\EntrustFacade as Entrust;
use Dwij\Laraadmin\Helpers\LAHelper;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Models\Employee;
use App\Models\Department;
use App\Role;
use Mail;
use Log;
use File;
use Excel;
use Storage;

class EmployeesController extends Controller
{
    public $show_action = true;
    public $view_col = 'name';
    public $listing_cols = ['id', 'emp_id', 'name', 'designation', 'mobile', 'email', 'dept', 'manager', 'corp_id', 'date_left'];
    public $members = array();
    public function __construct() {
        
        // Field Access of Listing Columns
        if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
            $this->middleware(function ($request, $next) {
                $this->listing_cols = ModuleFields::listingColumnAccessScan('Employees', $this->listing_cols);
                return $next($request);
            });
        } else {
            $this->listing_cols = ModuleFields::listingColumnAccessScan('Employees', $this->listing_cols);
        }
    }
    
    /**
     * Display a listing of the Employees.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $evaluation = array();
        
        
        $module = Module::get('Employees');
        $creationDate   = date("Y-m-d");
        $evaluation     = DB::table('evaluation_periods')
        ->select('id', 'evaluation_period', 'status')
        ->whereNull('deleted_at')
        ->where('start_date','<=',$creationDate)
        ->where('end_date','>=',$creationDate)
        ->first();
        if(count($evaluation) > 0) {
            $evaluationStatus = $evaluation->status;
            $evaluationId = $evaluation->id;
        }
        else {
            $evaluationStatus  = '';
            $evaluationId      = 0;
        }
        
        $userId = Auth::user()->context_id;
        $memberCount = DB::table('employees')->select('id')
        ->whereNull('deleted_at')
        ->whereNull('date_left')
        ->where('manager', '=', $userId)
        ->count();
        $departments = Department::whereNull('deleted_at')->orderBy('name')->lists('name', 'id');
        $members     = Employee::whereNull('deleted_at')->where('id', '!=',$userId )->orderBy('name')->lists('name', 'id');
        //print_r($departments);exit;
        if($memberCount > 0 || Entrust::hasRole("SUPER_ADMIN") || Entrust::hasRole("HR_MANAGER")) {
            if(Module::hasAccess($module->id) || $id == Auth::user()->id) {
                return View('la.employees.index', [
                    'show_actions' => $this->show_action,
                    'listing_cols' => $this->listing_cols,
                    'employeeStatus' => '1',
                    'evaluationId'    => $evaluationId,
                    'departments' => $departments,
                    'members' => $members,
                    'module' => $module
                ]);
            } else {
                return redirect('/dashboard');
            }
        }
        else
        {
            return redirect('/dashboard');
        }
        
    }
    
    /**
     * Show the form for creating a new employee.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    
    /**
     * Store a newly created employee in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Module::hasAccess("Employees", "create")) {
            
            $rules = Module::validateRules("Employees", $request);
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            // generate password
            $password = LAHelper::gen_password();
            
            // Create Employee
            $request->date_left = NULL;
            $employee_id = Module::insert("Employees", $request);
            
            $employee = Employee::where('id', $employee_id)->first();
            $employee->corp_id = strtolower($request->corp_id);
            $employee->save();
            
            // Create User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'corp_id' => strtolower($request->corp_id),
                'password'=> bcrypt(strtolower($request->corp_id)),
                'context_id' => $employee_id,
                'type' => "Employee",
            ]);
            
            // update user role
            $user->detachRoles();
            $role = Role::find($request->role);
            $user->attachRole($role);
            
            if(env('MAIL_USERNAME') != null && env('MAIL_USERNAME') != "null" && env('MAIL_USERNAME') != "") {
                // Send mail to User his Password
                /* Mail::send('emails.send_login_cred', ['user' => $user, 'password' => $password], function ($m) use ($user) {
                 $m->from('hello@laraadmin.com', 'LaraAdmin');
                 $m->to($user->email, $user->name)->subject('LaraAdmin - Your Login Credentials');
                 });*/
            } else {
                Log::info("User created: username: ".$user->email." Password: ".$password);
            }
            
            return redirect()->route('employees.index')->withSuccess('The employee details has been added successfully.');
            
        } else {
            return redirect('/dashboard');
        }
    }
    
    /**
     * Display the specified employee.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        
        if (  $id == 'myprofile')
            $id = Auth::user()->context_id;
            
            $userId = Auth::user()->context_id;
            
            
            $teams = array();
            
            if (Entrust::hasRole('SUPER_ADMIN') || Entrust::hasRole('HR_MANAGER')) {
                $values = DB::select("select id from (select * from employees order by manager, id) employees,
(select @pv := $userId) initialisation where find_in_set(manager, @pv) >= 0 and @pv := concat(@pv, ',', id)");
                
            }
            else {
                $this->getMembers( $userId);
                $teams = $this->members;
            }
            
            
            if(Module::hasAccess("Employees", "view") && (Entrust::hasRole('SUPER_ADMIN') || Entrust::hasRole('HR_MANAGER') || $id == Auth::user()->id || in_array($id, $teams))) {
                
                $employee = Employee::find($id);
                if(isset($employee->id)) {
                    $module = Module::get('Employees');
                    $module->row = $employee;
                    
                    // Get User Table Information
                    $user = User::where('context_id', '=', $id)->firstOrFail();
                    
                    return view('la.employees.show', [
                        'userId' => $userId,
                        'user' => $user,
                        'module' => $module,
                        'view_col' => $this->view_col,
                        'no_header' => true,
                        'no_padding' => "no-padding"
                    ])->with('employee', $employee);
                } else {
                    return view('errors.404', [
                        'record_id' => $id,
                        'record_name' => ucfirst("employee"),
                    ]);
                }
            } else {
                return redirect('/dashboard');
            }
    }
    
    
    /**
     * Show the form for editing the specified employee.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $userId = Auth::user()->context_id;
        $teams = array();
        
        if (Entrust::hasRole('SUPER_ADMIN') || Entrust::hasRole('HR_MANAGER')) {
            $values = DB::select("select id from (select * from employees order by manager, id) employees,
(select @pv := $userId) initialisation where find_in_set(manager, @pv) >= 0 and @pv := concat(@pv, ',', id)");
            
        }
        else {
            $this->getMembers( $userId);
            $teams = $this->members;
        }
        
        
        if(Module::hasAccess("Employees", "edit") && (Entrust::hasRole('SUPER_ADMIN') || Entrust::hasRole('HR_MANAGER') ||  $id == Auth::user()->id || in_array($id, $teams))) {
            
            $departments = Department::whereNull('deleted_at')->orderBy('name')->lists('name', 'id');
            $members     = Employee::whereNull('deleted_at')->orderBy('name')->lists('name', 'id');
            $employee = Employee::find($id);
            
            $dob =  implode("/", array_reverse(explode("-", $employee->date_birth)));
            $doh   =  implode("/", array_reverse(explode("-", $employee->date_hire)));
            $dol   =  implode("/", array_reverse(explode("-", $employee->date_left)));
            if(isset($employee->id)) {
                $module = Module::get('Employees');
                
                $module->row = $employee;
                
                // Get User Table Information
                $user = User::where('context_id', '=', $id)->firstOrFail();
                
                return view('la.employees.edit', [
                    'module' => $module,
                    'view_col' => $this->view_col,
                    'user' => $user,
                    'departments' => $departments,
                    'members' => $members,
                    'dob' => $dob,
                    'doh' => $doh,
                    'dol' => $dol,
                    
                ])->with('employee', $employee);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("employee"),
                ]);
            }
        } else {
            return redirect('/dashboard');
        }
        
        
    }
    
    /**
     * Update the specified employee in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Module::hasAccess("Employees", "edit")) {
            
            $rules = Module::validateRules("Employees", $request, true);
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();;
            }
            
            
            
            $employee_id = Module::updateRow("Employees", $request, $id);
            if($request->date_left == '') {
                $employee = Employee::where('id', $employee_id)->first();
                $employee->date_left = null;
                $employee->corp_id = strtolower($request->corp_id);
                $employee->save();
            }
            // Update User
            $user = User::where('context_id', $employee_id)->first();
            $user->name = $request->name;
            if( $request->email != '')
                $user->email = $request->email;
                if( $request->corp_id != '')
                    $user->corp_id = strtolower($request->corp_id);
                    $user->password =  bcrypt(strtolower($request->corp_id));
                    $user->save();
                    
                    
                    $user = User::where('context_id', $employee_id)->first();
                    //Checking user Role whether is modified or not
                    
                    $userRole = DB::table('role_user')->where([
                        ['user_id', '=', $employee_id]
                    ])->value('role_id');
                    
                    if ($userRole != $request->role) {
                        // update user role
                        $user->detachRoles();
                        $role = Role::find($request->role);
                        $user->attachRole($role);
                    }
                    if($id != Auth::user()->id)
                        return redirect()->route('employees.index')->withSuccess('The employee details has been updated successfully.');
                        else
                            return redirect('employees/'.$id)->withSuccess('Profile details has been updated successfully.');
                            
        } else {
            return redirect('/dashboard');
        }
    }
    
    /**
     * Remove the specified employee from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Module::hasAccess("Employees", "delete")) {
            //Validating whether the employee is a manager or not
            $recordCount = DB::table('employees')->select('id')
                        ->where('manager', '=', $id)
                        ->whereNull('deleted_at')
                        ->count();
            if ($recordCount ==0 )  {
                Employee::find($id)->delete();
                DB::table('performance_appraisals_details')
                ->where('employee_id', $id)
                ->where('status', '=', '0')
                ->update(['deleted_at' => date("Y-m-d H:i:s")]);
                // Redirecting to index() method
                return redirect()->route('employees.index')->withSuccess('The employee details has been deleted successfully.');
            }
            else {
                // Redirecting to index() method
                return redirect()->route('employees.index')->withErrors('Error: Could not delete this record. Some employees are reporting to this employee.');
                
            }
            
        } else {
            return redirect('/dashboard');
        }
    }
    
    /**
     * Datatable Ajax fetch
     *
     * @return
     */
    public function dtajax($dateLeft)
    {
        
        
        if(Entrust::hasRole('SUPER_ADMIN') || Entrust::hasRole('HR_MANAGER')) {
            if($dateLeft != '1')
                $values = DB::table('employees')
                ->join('users', 'employees.id', '=', 'users.context_id')
                ->join('role_user', 'users.id', '=', 'role_user.user_id')
                ->join('roles', 'role_user.role_id', '=', 'roles.id')
                ->select('employees.id', 'employees.emp_id', 'employees.name', 'employees.designation', 'employees.mobile', 'employees.email', 'employees.dept', 'employees.manager', 'employees.corp_id', 'employees.date_left', 'roles.display_name')
                ->whereNull('employees.deleted_at');
                else
                    $values = DB::table('employees')
                    ->join('users', 'employees.id', '=', 'users.context_id')
                    ->join('role_user', 'users.id', '=', 'role_user.user_id')
                    ->join('roles', 'role_user.role_id', '=', 'roles.id')
                    ->select('employees.id', 'employees.emp_id', 'employees.name', 'employees.designation', 'employees.mobile', 'employees.email', 'employees.dept', 'employees.manager', 'employees.corp_id', 'employees.date_left','roles.display_name' )
                    ->whereNull('employees.deleted_at')->whereNull('employees.date_left');
                    
                    $out = Datatables::of($values)->make();
                    $data = $out->getData();
                    
                    $fields_popup = ModuleFields::getModuleFields('Employees');
                    
                    for($i=0; $i < count($data->data); $i++) {
                        for ($j=0; $j < count($this->listing_cols); $j++) {
                            
                            if($j== 0) {
                                $id =  $data->data[$i][0];
                            }
                            
                            $col = $this->listing_cols[$j];
                            
                            if($fields_popup[$col] != null  && $j != 10 && starts_with($fields_popup[$col]->popup_vals, "@")) {
                                $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                            }
                            
                            if( $j == 0 && $id != 1 &&  (Entrust::hasRole('SUPER_ADMIN') || Entrust::hasRole('HR_MANAGER')) ) {
                                $data->data[$i][0] = '<input type="checkbox" class="record" name="id[]" value="'.$data->data[$i][0].'">'  ;
                            }
                            else if ($id == 1)
                                $data->data[$i][0] = '';
                                
                                if($col == $this->view_col) {
                                    $data->data[$i][$j] = '<a target="_blank" href="'.url( '/employees/'.$id).'">'.$data->data[$i][$j].'</a>';
                                }
                         }
                        
                        if($this->show_action) {
                            $output = '';
                            if(Module::hasAccess("Employees", "edit")) {
                                $output .= '<a title="Edit" href="'.url( '/employees/'.$id.'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                            }
                            
                            if(Module::hasAccess("Employees", "delete" && $id == 1)) {
                                $output .= Form::open(['route' => [ 'employees.destroy', $id], 'method' => 'delete', 'style'=>'display:inline']);
                                $output .= ' <button title="Delete" class="btn btn-danger btn-xs btn-delete" type="button"><i class="fa fa-times"></i></button>';
                                $output .= Form::close();
                            }
                            
                            $data->data[$i][] = (string)$output;
                        }
                    }
                    $out->setData($data);
                    return $out;
        }  else {
            
            
            $userId  = Auth::user()->context_id;
            $cols = implode(", ",$this->listing_cols);
            
            if ($dateLeft != "1")
                $where = " WHERE deleted_at IS NULL ";
                else
                    $where  = " WHERE date_left IS NULL and deleted_at IS NULL ";
                    $this->getMembers( $userId);
                    $memCondition = " ";
                    $mem = implode("," , $this->members);
                    if ($mem != '' )
                        $memCondition = " AND id in ($mem)";
                        else  $memCondition = " AND manager = $userId";
                        
                        $values = DB::select("select ".$cols." from  employees $where $memCondition");
                        
                        $values= collect($values);
                        
                        $out = Datatables::of($values)->make();
                        $data = $out->getData();
                        $fields_popup = ModuleFields::getModuleFields('Employees');
                        
                        for($i=0; $i < count($data->data); $i++) {
                            for ($j=0; $j < count($this->listing_cols); $j++) {
                                $col = $this->listing_cols[$j];
                                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                                }
                                if($col == $this->view_col) {
                                    $data->data[$i][$j] = '<a target="_blank" href="'.url('/employees/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
                                }
                            }
                          if($this->show_action) {
                                 $output = '';
                                 if(Module::hasAccess("Employees", "edit")) {
                                     $output .= '<a title="Edit" href="'.url('/employees/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                                 }
                                 
                                 if(Module::hasAccess("Employees", "delete")) {
                                     $output .= Form::open(['route' => ['employees.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
                                     $output .= ' <button title="Delete" class="btn btn-danger btn-xs btn-delete" type="button"><i class="fa fa-times"></i></button>';
                                     $output .= Form::close();
                                 }
                                 $data->data[$i][] = (string)$output;
                             }
                        }
                        $out->setData($data);
                        return $out;
        }
    }
    
    /**
     * Change Employee Password
     *
     * @return
     */
    public function change_password($id, Request $request) {
        
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
            'password_confirmation' => 'required|min:6|same:password'
        ]);
        
        if ($validator->fails()) {
            return \Redirect::to('/employees/'.$id)->withErrors($validator);
        }
        
        $employee = Employee::find($id);
        $user = User::where("context_id", $employee->id)->where('type', 'Employee')->first();
        $user->password = bcrypt($request->password);
        $user->save();
        
        // Send mail to User his new Password
        /*if(env('MAIL_USERNAME') != null && env('MAIL_USERNAME') != "null" && env('MAIL_USERNAME') != "") {
         // Send mail to User his new Password
         Mail::send('emails.send_login_cred_change', ['user' => $user, 'password' => $request->password], function ($m) use ($user) {
         $m->from(LAConfigs::getByKey('default_email'), LAConfigs::getByKey('sitename'));
         $m->to($user->email, $user->name)->subject('LaraAdmin - Login Credentials chnaged');
         });
         } else {
         Log::info("User change_password: username: ".$user->email." Password: ".$request->password);
         }*/
        
        return redirect('/employees/'.$id.'#tab-account-settings');
    }
    
    
    /**
     * Allow user to create a appraisal
     *
     * @return
     */
    public function addPerformanceAjax(Request $request)  {
        
        $result = array ('status'=> 'success',  'data' => 'The record has been successfully updated');
        $startDate =  implode("-", array_reverse(explode("/", $request->startDate)));
        $endDate   =  implode("-", array_reverse(explode("/", $request->endDate)));
        if($endDate >= $startDate) {
            
            $ids = $request->input( 'ids' );
            $ids = explode(",", $ids);
            $now = date("Y-m-d H:i:s");
            foreach ($ids as $id) {
                
                $employees =  DB::table('employees')->where([
                    ['id', '=', $id],
                ])->first();
                
                $dataCount =   DB::table('performance_appraisals_details')
                ->whereNull('deleted_at')
                ->where('evaluation_period','=', $request->evaluationPeriod)
                ->where('employee_id','=',$id)
                ->where('is_allowed','=', 1)
                ->count();
                if($dataCount == 0)   {
                    
                    $dataCount1 =  DB::table('performance_appraisals_details')
                    ->select('id')
                    ->whereNull('deleted_at')
                    ->where('employee_id','=', $id)
                    ->count();
                    if( $dataCount1 !=0 ) {
                        // Update the end date of last performance appraisal record
                        $recordId = DB::table('performance_appraisals_details')
                        ->select('id')
                        ->whereNull('deleted_at')
                        ->where('employee_id','=', $id)
                        ->orderBy('id', 'desc')
                        ->first();
                        //Set end date as previous day of the current start day
                        $previousRecordEndDate = date('Y-m-d', strtotime('-1 day', strtotime($startDate)));
                        DB::table('performance_appraisals_details')
                        ->where('id', $recordId->id)
                        ->update(['end_date' => $previousRecordEndDate, 'updated_at' => $now]);
                    }
                    if($request->evaluationPeriod > 0) {
                      $rowId  = DB::table('performance_appraisals_details')->insert([
                            'employee_id' => $id,
                            'manager_id' => $employees->manager,
                            'department' => $employees->dept,
                            'evaluation_period' => $request->evaluationPeriod,
                            'performance_appraisals_id' => 0,
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                            'steps'    => 0,
                            'status' => 0,
                            'is_allowed' => 1,
                            'created_at' => $now,
                            'updated_at' => $now
                        ]);
                      $result = array ('status'=> 'success',  'data' => 'The record has been successfully updated.');
                    }
                 }
              }
            }
            else {
                $result = array ('status'=> 'error',  'data' => 'End date should be equal or greater than Start date.');
               
            }
            echo json_encode($result);
    }
    
    
    public function getMembers($employeeId ) {
        
        $child = DB::table('employees')
                ->select('id')
                ->whereNull('deleted_at')
                ->whereNull('date_left')
                ->where('manager','=', $employeeId)
                ->orderBy('id', 'desc')
                ->get();
        foreach($child as $ch) {
            array_push($this->members, $ch->id);
            if($employeeId != $ch->id)
                $this->getMembers($ch->id);
        }
        
    }
    
    
    public function upload_files() {
        
        $excelColumnsNames    =  array("employee_id", "name", "designation", "gender", "mobile", "email", "department", "reporting_to", "corp_id", "doj", "role");
        $departments = Department::whereNull('deleted_at')->orderBy('name')->lists('name', 'id')->toArray();
        $roles       = Role::whereNull('deleted_at')->orderBy('name')->lists('name', 'id')->toArray();
        
        
        if(Module::hasAccess("Uploads", "create")) {
            $input = Input::all();
            
            if(Input::hasFile('employeeFile')) {
                
                /*  $rules = array(
                 'file' => 'mimes:xls',
                 );
                 $validation = Validator::make($input, $rules); */
                $validation=Validator::make($input,[
                    //use this
                    'employeeFile'=>'required|max:50000|mimes:xlsx'
                    
                ]);
                
                if ($validation->fails()) {
                    return redirect()->route('employees.index')->withErrors('Please upload valid xlsx file.');
                }
                
                $file = Input::file('employeeFile');
                $userId = Auth::user()->context_id;
                
                
                $folder = storage_path('uploads');
                $filename = $file->getClientOriginalName();
                
                $date_append = date("Y-m-d-His");
                $path_parts = pathinfo(storage_path('uploads/'.$filename));
                $newFileName = $path_parts['filename']."-".$date_append.".".$path_parts['extension'];
                $upload_success = Input::file('employeeFile')->move($folder, $newFileName);
                if( $upload_success ) {
                    $file = 'storage/uploads/'.$newFileName;
                    $tempData = [];
                    $colDiff  = [];
                    
                    Excel::selectSheets('Sheet1')->load($file, function($reader) use (&$tempData, &$excelColumnsNames, &$colDiff) {
                        //Reading column heading
                        $headerRow = $reader->first()->keys()->toArray();
                        $colDiff = array_diff($excelColumnsNames, $headerRow);
                        $reader->each(function($row) use (&$tempData) {
                            array_push( $tempData, $row);
                        });
                    });
                        if(count( $colDiff) > 0) {
                            unlink(storage_path('uploads/'.$newFileName));
                            return redirect()->route('employees.index')->withErrors('Error: Column mismatch. Please upload valid xlsx file.');
                        }
                        else {
                            //Import employees data from Excel to DB.
                            $now            = date("Y-m-d H:i:s");
                            $successCount  = 0;
                            $failCount     = 0;
                            $errorMsg      = '';
                            $logLink       = '';
                            foreach ($tempData as $temp) {
                                
                                $uniqueCount = DB::table('employees')->select('id')
                                ->where('corp_id', '=', $temp->corp_id)
                                ->orWhere('email', '=', $temp->email)
                                ->orWhere('emp_id', '=', $temp->employee_id)
                                ->count();
                                $mangerId = 0;
                                $deptId   = 1;
                                $roleId   = 3;
                                
                                $deptId = array_search($temp->department, $departments);
                                $roleId = array_search($temp->role, $roles);
                                
                                if(  $uniqueCount == 0 && trim($temp->employee_id) != '' && trim($temp->name) != '' && trim($temp->designation) != '' && trim($temp->gender) != '' && trim($temp->mobile) != '' && trim($temp->email) != '' && trim($temp->department) != '' && trim($temp->reporting_to) != ''
                                    && trim($temp->corp_id) != ''  && trim($temp->doj) != '') {
                                        
                                        
                                        //Insert data into Employee Table
                                        $id = DB::table('employees')->insertGetId(
                                            [
                                                'name'         =>  $temp->name,
                                                'designation'  =>  $temp->designation,
                                                'gender'       =>  $temp->gender,
                                                'mobile'       =>  $temp->mobile,
                                                'email'        =>  $temp->email,
                                                'dept'         =>  $deptId,
                                                'manager'      =>  $mangerId,
                                                'corp_id'=>  strtolower($temp->corp_id),
                                                'date_hire'    =>  $temp->doj,
                                                'created_at'   => $now,
                                                'updated_at'   => $now,
                                                'emp_id'       =>  $temp->employee_id,
                                            ]
                                            );
                                        //Insert data into Users Table
                                        DB::table('users')->insertGetId(
                                            [
                                                'name'         =>  $temp->name,
                                                'context_id'   =>  $id,
                                                'email'        =>  $temp->email,
                                                'password'     =>  bcrypt(strtolower($temp->corp_id)),
                                                'corp_id'=>  strtolower($temp->corp_id),
                                                'type'         =>  'Employee',
                                                'created_at'   => $now,
                                                'updated_at'   => $now,
                                            ]
                                            );
                                        //Insert data into Role_User Table
                                        DB::table('role_user')->insertGetId(
                                            [
                                                'role_id'         =>  $roleId,
                                                'user_id'         =>  $id,
                                                'created_at'      =>  $now,
                                                'updated_at'      =>  $now
                                            ]
                                            );
                                        $successCount++;
                                        
                                        
                                    } else
                                    {   $failCount++;
                                    $errorMsg .= " Employee_id :  ".$temp->employee_id.", Name : ".$temp->name."\r\n";
                                    
                                    }
                                    
                            }
                            //Update manager id for the employee record
                            foreach ($tempData as $temp) {
                                $mangerCount   = DB::table('employees')->select('id')
                                                ->where('emp_id', '=', $temp->reporting_to)
                                                ->count();
                                
                                if( $mangerCount  > 0 ) {
                                    $managerId = DB::table('employees')->where([
                                        ['emp_id', '=', $temp->reporting_to]
                                    ])->value('id');
                                    
                                    DB::table('employees')
                                    ->where('emp_id', $temp->employee_id)
                                    ->update(['manager' => $managerId]);
                                }
                            }
                            if($failCount > 0) {
                                $errorLogFileName = "Import_logs_".date("Y-m-d-H-i-s").".txt";
                                File::put(storage_path ('app\\public\\') .$errorLogFileName, $errorMsg);
                                
                                $logLink = " <br><a target ='new' href='".url("logs\\".$errorLogFileName)."'>Click here</a> to download the Error Log file.";
                            }
                            if($successCount > 0  ||  $failCount > 0) {
                                return redirect()->route('employees.index')->withSuccess( " Employee details(".$successCount.") has been successfully imported.".$logLink);
                            }
                        }
                        
                } else {
                    unlink(storage_path('uploads/'.$newFileName));
                    return redirect()->route('employees.index')->withErrors('Error: Please upload valid xlsx file.');
                }
            } else {
                return redirect()->route('employees.index')->withErrors('Error: Upload file not found.');
                
            }
        } else {
            return redirect()->route('employees.index')->withErrors('Error: Unauthorized Access.');
        }
    }
    
    /**
     * Get file
     *
     * @return \Illuminate\Http\Response
     */
    public function get_file($name)
    {
        
        return response()->download(storage_path ('app\\public\\').$name);
        
    }
    
}