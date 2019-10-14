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
use Excel;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;
use Zizaco\Entrust\EntrustFacade as Entrust;
use App\Models\Performance_Appraisal;
use App\Models\Evaluation_Period;

class Performance_AppraisalsController extends Controller
{
    public $show_action = true;
    public $view_col = 'employee_id';
    public $listing_cols = ['id' ];
    public $listing_cols_data_table = ['id',  'employee', 'department', 'manager', 'evaluation period', 'start date', 'end date', 'status'];
    public $members = array();
    public function __construct() {
        // Field Access of Listing Columns
        if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
            $this->middleware(function ($request, $next) {
                $this->listing_cols = ModuleFields::listingColumnAccessScan('Performance_Appraisals', $this->listing_cols);
                return $next($request);
            });
        } else {
            $this->listing_cols = ModuleFields::listingColumnAccessScan('Performance_Appraisals', $this->listing_cols);
        }
    }
    
    /**
     * Display a listing of the Performance_Appraisals.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $creationDate   = date("Y-m-d");
        $userId = Auth::user()->context_id;
        $roleId = DB::table('role_user')->where([['user_id', '=', $userId]])->value('role_id');
        $evaluation     = DB::table('evaluation_periods')
                            ->select('id', 'evaluation_period', 'status')
                            ->whereNull('deleted_at')
                            ->where('start_date','<=',$creationDate)
                            ->where('end_date','>=',$creationDate)
                            ->first();
        
        // $evaluationItems = Evaluation_period::pluck('evaluation_period', 'id')->reverse()->toArray();
        $evaluationItems = Evaluation_period::orderBy('id', 'desc')->pluck('evaluation_period', 'id')->toArray();
        if(count($evaluation) > 0) {
            $evaluationStatus = $evaluation->status;
            $evaluationId = $evaluation->id;
        }
        else {
            $evaluationStatus  = '';
            $evaluationId      = 0;
        }
        
        $memberCount = DB::table('employees')->select('id')
                            ->whereNull('deleted_at')
							->whereNull('date_left')
                            ->where('manager', '=', $userId)
                            ->count();
        
        $teams = array();
        
        $this->getMembers( $userId);
        $teams = $this->members;
        array_push($teams, $userId);
    
        $docuemntCount = DB::table('Performance_Appraisals_details')->select('id')
                        ->whereNull('deleted_at')
                        ->whereIn('manager_id', $teams)
                        ->count();
        
        if($memberCount > 0  ||  $docuemntCount > 0 || Entrust::hasRole('SUPER_ADMIN')) {
            $module = Module::get('Performance_Appraisals');
            
            if(Module::hasAccess($module->id)) {
                return View('la.performance_appraisals.custom-index', [
                    'show_actions' => $this->show_action,
                    'listing_cols' => $this->listing_cols,
                    'listing_cols_data_table' => $this->listing_cols_data_table,
                    'module' => $module,
                    'evaluationStatus' => $evaluationStatus,
                    'evaluationItems' => $evaluationItems,
                    'evaluationId' => $evaluationId
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
     * Show the form for creating a new performance_appraisal.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $creationDate       = date("Y-m-d");
        $userId             = Auth::user()->context_id;
        $evaluationCount    = DB::table('evaluation_periods')
                                ->select('id', 'evaluation_period', 'status')
                                ->whereNull('deleted_at')
                                ->where('start_date','<=',$creationDate)
                                ->where('end_date','>=',$creationDate)
                                ->count();
        $memberCount        = DB::table('employees')->select('id')
                                ->whereNull('deleted_at')
                                ->where('manager', '=', $userId)
                                ->count();
        
        if($evaluationCount > 0 ) {
            $evaluation   = DB::table('evaluation_periods')
                                ->select('id', 'evaluation_period', 'status', 'start_date', 'end_date', 'evaluation_period')
                                ->whereNull('deleted_at')
                                ->where('start_date','<=',$creationDate)
                                ->where('end_date','>=',$creationDate)
                                ->first();
            
            $evaluationId      = $evaluation->id;
            $evaluationStatus  = $evaluation->status;
            $evaluationStartAt = date_format(date_create($evaluation->start_date), "d-m-Y");
            $evaluationEndAt   = date_format(date_create($evaluation->end_date), "d-m-Y");
            $evaluationPeriod  = $evaluation->evaluation_period;
            $lastEligibleDay   = strstr($evaluationPeriod, '-', true)."-12-31";
            
            
            $recCount = DB::table('performance_appraisals_details')->select('id')->where([
                ['deleted_at', '=', NULL],
                ['evaluation_period', '=', $evaluationId],
                ['employee_id', '=', $userId]
            ])->count();
            
            $accessCount = DB::table('performance_appraisals_details')->select('id')->where([
                ['is_allowed', '=', 1],
                ['evaluation_period', '=', $evaluationId],
                ['employee_id', '=', $userId]
            ])->count();
            //Getting start and end date based on the new access
            if( $accessCount >  0 ) {
                $performanceDetails =   DB::table('performance_appraisals_details')->select('id', 'start_date', 'end_date')->where([
                    ['is_allowed', '=', 1],
                    ['evaluation_period', '=', $evaluationId],
                    ['employee_id', '=', $userId]
                ])->first();
                
                $evaluationStartAt  = date_format(date_create($performanceDetails->start_date), "d-m-Y");
                $evaluationEndAt    = date_format(date_create($performanceDetails->end_date), "d-m-Y");
            }
            
            
            if( ($accessCount == 0 ) && ( $evaluationStatus == 'Closed' || $evaluationStatus == 'Completed'))
                return redirect('/dashboard');
                
                $employees          =   DB::table('employees')->where([
                    ['id', '=',$userId],
                ])->first();
                $dateHire           =   $employees->date_hire;
                
                if ($lastEligibleDay < $dateHire && $accessCount == 0 ) {
                    return redirect()->back()->withErrors('You are not eligible to create a performance document.');
                }
                
                $records = DB::table('employees')
                            ->join('departments', 'departments.id', '=', 'employees.dept')
                            ->select('departments.id', 'departments.name', 'departments.template_name')
                            ->where([['employees.id', '=', $userId]]) ->get();
                            
                foreach ($records as $record) :
                    $deptId      = ucwords($record->id);
                    $deptName    = ucwords($record->name);
                    $templateId  = $record->template_name;
                endforeach;
                
                $templateName    =   DB::table('uploads')->where('id', '=', $templateId)->value('name');
                
                if( $templateName == '') {
                    return redirect()->back()->withErrors('Error: Template file is missing.');
                }
                
                 
                $path_parts = pathinfo(storage_path('uploads/'.$templateName));
                
                
                if(!file_exists(storage_path('uploads/'.$templateName)   )) {
                    return redirect()->back()->withErrors('Error: Template file is missing.');
                }
                
                
                if($path_parts['extension'] != 'xlsx') {
                    return redirect()->back()->withErrors('Error: Invalid file type. Please choose valid xlsx file in the department page.');
                }
                
                $file = 'storage/uploads/'.$templateName;
                $tempData = [];
                
                Excel::selectSheets('Sheet1')->load($file, function($reader) use (&$tempData) {
                    $reader->ignoreEmpty();
                    $reader->each(function($row) use (&$tempData) {
                        $tempData[$row->label] =  $row->values;
                    });
                        
                });
                   
                    $managerId = '';
                    
                    $managerId = DB::table('employees')->where([
                        ['id', '=', $userId]
                    ])->value('manager');
                    
                    $managerName = DB::table('employees')->where([
                        ['id', '=', $managerId]
                    ])->value('name');
                    
                    //Counting no of appraisal between employee and manger
                    $records = DB::table('performance_appraisals_details')->select('id')->where([
                        ['deleted_at', '=', NULL],
                        ['evaluation_period', '=', $evaluationId],
                        ['employee_id', '=', $userId],
                        ['manager_id', '=', $managerId]
                    ])->count();
                                                          
                    if ($evaluationId > 0 || $accessCount > 0) { // add $records == 0 if you want to restrict 1-1 with manager
                        
                        $roleId = DB::table('role_user')->where([['user_id', '=', $userId]])->value('role_id');
                        $employeeName = DB::table('employees')->where([
                            ['id', '=', $userId]
                        ])->value('name');
                       
                        if ($recCount == 0 || $accessCount > 0){
                            
                            $module = Module::get('performance_appraisals');
                            return View('la.performance_appraisals.custom-create', [
                                'module' => $module,
                                'action' => "create",
                                'roleId' => $roleId,
                                'evaluationId' => $evaluationId,
                                'evaluationStartAt' => $evaluationStartAt,
                                'evaluationEndAt' => $evaluationEndAt,
                                'evaluationStatus' => $evaluationStatus,
                                'tempData' => $tempData,
                                'employeeName' => $employeeName,
                                'appraisalPeriod' => $evaluationPeriod,
                                'deptName' => $deptName,
                                'managerName' => $managerName,
                                'memberCount' => $memberCount
                            ]);
                        }
                        else {
                           
                             return redirect('/dashboard');
                        }
                    }
                    else
                    {
                        return redirect('/dashboard');
                    }
        }
        else
        {
            // Template file is missing in the storage folder
            return redirect()->back()->withErrors('There is a problem with creating new document.');
        }
    }
    /**
     * Store a newly created performance_appraisals in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Module::hasAccess("Performance_Appraisals", "create")) {
            
            
            $creationDate       = date("Y-m-d");
            $userId             = Auth::user()->context_id;
            //lastEligibleDay  	= date("Y-m-d",strtotime("last year December 31st"));
            //$lastEligibleDay    = date("Y-m-d",strtotime("last day of December this year"));
            $evaluationCount    = DB::table('evaluation_periods')
                                ->select('id', 'evaluation_period', 'status')
                                ->whereNull('deleted_at')
                                ->where('start_date','<=',$creationDate)
                                ->where('end_date','>=',$creationDate)
                                ->count();
            $memberCount        = DB::table('employees')->select('id')
                                ->whereNull('deleted_at')
                                ->where('manager', '=', $userId)
                                ->count();
            
            
            if($evaluationCount > 0 ) {
                $evaluation   = DB::table('evaluation_periods')
                                ->select('id', 'evaluation_period', 'status', 'start_date', 'end_date', 'evaluation_period')
                                ->whereNull('deleted_at')
                                ->where('start_date','<=',$creationDate)
                                ->where('end_date','>=',$creationDate)
                                ->first();
                
                $evaluationId      = $evaluation->id;
                $evaluationStatus  = $evaluation->status;
                $evaluationStartAt = date_format(date_create($evaluation->start_date), "d-m-Y");
                $evaluationEndAt   = date_format(date_create($evaluation->end_date), "d-m-Y");
                $evaluationPeriod  = $evaluation->evaluation_period;
                $lastEligibleDay   = strstr($evaluationPeriod, '-', true)."-12-31";
                
                
                $recCount = DB::table('performance_appraisals_details')->select('id')->where([
                            ['deleted_at', '=', NULL],
                            ['evaluation_period', '=', $evaluationId],
                            ['employee_id', '=', $userId]
                        ])->count();
                
                $accessCount = DB::table('performance_appraisals_details')->select('id')->where([
                            ['is_allowed', '=', 1],
                            ['evaluation_period', '=', $evaluationId],
                            ['employee_id', '=', $userId]
                        ])->count();
                
                
                
                if($recCount == 0 || $accessCount == 1 )    {
                    
                    $rules = Module::validateRules("Performance_Appraisals", $request);
                    
                    $validator = Validator::make($request->all(), $rules);
                    
                    if ($validator->fails()) {
                        return redirect()->back()->withErrors($validator)->withInput();
                    }
                    
                    // Getting user realated information
                    $userId             = Auth::user()->context_id;
                    $employeeDetails    =  DB::table('employees')->select('id', 'dept', 'manager')->where([
                        ['id', '=', $userId]
                    ])->first();
                    
                    
                    $insert_id      = Module::insert("Performance_Appraisals", $request);
                    $now            = date("Y-m-d H:i:s");
                    $creationDate   = date("Y-m-d");
                   
                    $evaluationCount    = DB::table('evaluation_periods')
                                        ->select('id', 'evaluation_period', 'status')
                                        ->whereNull('deleted_at')
                                        ->where('start_date','<=',$creationDate)
                                        ->where('end_date','>=',$creationDate)
                                        ->count();
                    if($evaluationCount > 0 ) {
                        $evaluationData     = DB::table('evaluation_periods')
                                                ->select('id', 'evaluation_period', 'status', 'start_date', 'end_date', 'evaluation_period')
                                                ->whereNull('deleted_at')
                                                ->where('start_date','<=',$creationDate)
                                                ->where('end_date','>=',$creationDate)
                                                ->first();
                    }
                                      
                    // Checking the employee appraisal data in the performance appraisal details table
                    $dataCount =   DB::table('performance_appraisals_details')
                                    ->whereNull('deleted_at')
                                    ->where('is_allowed','=', 1)
                                    ->where('evaluation_period','=',$evaluationData->id)
                                    ->where('employee_id','=', $userId)
                                    ->count();
                    if($dataCount == 0) {
                        DB::table('performance_appraisals_details')->insert(
                            [
                                'employee_id'=>  $employeeDetails->id,
                                'manager_id' =>  $employeeDetails->manager,
                                'department' =>  $employeeDetails->dept,
                                'evaluation_period' => $evaluationData->id,
                                'performance_appraisals_id' => $insert_id,
                                'start_date' => $evaluationData->start_date,
                                'end_date' => $evaluationData->end_date,
                                'steps' => '1',
                                'status' => '0',
                                'is_allowed' => 0,
                                'created_at' => $now,
                                'updated_at' => $now
                            ]
                            );
                    }
                    else
                    {
                        DB::table('performance_appraisals_details')
                            ->whereNull('deleted_at')
                            ->where('is_allowed','=', 1)
                            ->where('employee_id','=', $userId)
                            ->where('evaluation_period','=', $evaluationData->id)
                            ->update(['manager_id' =>  $employeeDetails->manager,
                                'department' =>  $employeeDetails->dept,
                                'performance_appraisals_id' => $insert_id,
                                'steps' => '1',
                                'status' => 0,
                                'is_allowed' => 2,
                                'updated_at' => $now]) ;
                        
                        
                    }
                    return redirect("/performance_appraisals/".$insert_id."/edit");
                    
                  } 
                  else {
                      return redirect("/dashboard");
                  }
                  
                }
                else {
                    return redirect("/dashboard");
                }
        }
    }
    
    /**
     * Display the specified performance_appraisal.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)    {
        
        $performanceappraisal = Performance_Appraisal::find($id);
        if(isset($performanceappraisal->id)) {
            $userId = Auth::user()->context_id;
            $performanceData =  DB::table('performance_appraisals')
                                ->join('performance_appraisals_details', 'performance_appraisals.id', '=', 'performance_appraisals_details.performance_appraisals_id')
                                ->join('employees', 'performance_appraisals_details.manager_id', '=', 'employees.id')
                                ->join('departments', 'performance_appraisals_details.department', '=', 'departments.id')
                                ->select('performance_appraisals.*', 'performance_appraisals_details.*', 'employees.name as managerName', 'departments.name as departmentName')
                                ->where('performance_appraisals.id', '=', $id)
                                ->first();
            
            $empId              = $performanceData->employee_id;
            $managerId          = $performanceData->manager_id;
            $currentStatus      = $performanceData->status;
            $deptName           = ucwords($performanceData->departmentName);
            $managerName        = ucwords($performanceData->managerName);
            $startDate          = date_format(date_create($performanceData->start_date), "d-m-Y");
            $endDate            = date_format(date_create($performanceData->end_date), "d-m-Y");
            
            
            
            $memberCount        = DB::table('employees')->select('id')
                                    ->whereNull('deleted_at')
                                    ->where('manager', '=', $performanceData->employee_id)
                                    ->count();
            
            
            $teams = array();
          
            $this->getMembers( $userId);
            $teams = $this->members;
            array_push($teams, $userId);
            //Check the current Profile is belongs to logged in user or his team members
             if ($empId == Auth::user()->context_id  || in_array($managerId, $teams)    || Entrust::hasRole('SUPER_ADMIN') || Entrust::hasRole('HR_MANAGER')) {
                if(Module::hasAccess("Performance_Appraisals", "view")) {
                    
                    $employeeName = DB::table('employees')->where([
                                    ['id', '=', $performanceData->employee_id]
                                ])->value('name');
                    
                    $evaluation = DB::table('evaluation_periods')->where([
                                    ['id', '=', $performanceData->evaluation_period]
                                ])->first();
                    $evaluationStatus = $evaluation->status;
                    $evaluationPeriod = $evaluation->evaluation_period;
                    
                    if(isset($performanceData->id)) {
                        
                        $performanceappraisal = Performance_Appraisal::find($id);
                        $module = Module::get('Performance_Appraisals');
                        $module->row = $performanceappraisal;
                        
                        return view('la.performance_appraisals.custom-show', [
                            'module' => $module,
                            'view_col' => $this->view_col,
                            'no_header' => true,
                            'userId' => Auth::user()->context_id,
                            'employeeId'   => $performanceData->employee_id,
                            'no_padding' => "no-padding",
                            'evaluationStatus' => $evaluationStatus,
                            'employeeName' => $employeeName,
                            'appraisalPeriod' => $evaluationPeriod,
                            'deptName' => $deptName,
                            'managerName' => $managerName,
                            'memberCount' => $memberCount,
                            'startDate' => $startDate,
                            'endDate' => $endDate,
                            'currentStatus' => $currentStatus
                        ])->with('performance_appraisal', $performanceappraisal);
                    } else {
                        return view('errors.404', [
                            'record_id' => $id,
                            'record_name' => ucfirst("performance_appraisal"),
                        ]);
                    }
                } else {
                    return redirect("/dashboard");
                }
            }
            else
                return redirect("/dashboard");
        }
        else
            return redirect("/dashboard");
    }
    
    /**
     * Show the form for editing the specified performance_appraisal.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)  {
        $performanceappraisal = Performance_Appraisal::find($id);
        if(isset($performanceappraisal->id)) {
            $userId = Auth::user()->context_id;
            $teams = array();
            $buttonText = "Save";
            $submitButtonText = "Submit";
            
            // Getting team members ids
            $this->getMembers( $userId);
            $teams = $this->members;
            array_push($teams, $userId);
            
            $performanceData =  DB::table('performance_appraisals')
                                ->join('performance_appraisals_details', 'performance_appraisals.id', '=', 'performance_appraisals_details.performance_appraisals_id')
                                ->join('employees', 'performance_appraisals_details.manager_id', '=', 'employees.id')
                                ->join('departments', 'performance_appraisals_details.department', '=', 'departments.id')
                                ->select('performance_appraisals.*', 'performance_appraisals_details.*', 'employees.name as managerName', 'departments.name as departmentName')
                                ->where('performance_appraisals.id', '=', $id)
                                ->first();
            
            $empId              = $performanceData->employee_id;
            $managerId          = $performanceData->manager_id;
            $evaluation_period  = $performanceData->evaluation_period;
            $currentStatus      = $performanceData->status;
            $currentStep        = $performanceData->steps;
            $deptName           = ucwords($performanceData->departmentName);
            $managerName        = ucwords($performanceData->managerName);
            $startDate          = date_format(date_create($performanceData->start_date), "d-m-Y");
            $endDate            = date_format(date_create($performanceData->end_date), "d-m-Y");
            
            //Check the current Profile is belongs to logged in user or his team members
            if ($empId == Auth::user()->context_id  || in_array($managerId, $teams) || Entrust::hasRole('SUPER_ADMIN') || Entrust::hasRole('HR_MANAGER')   ) {
                if(Module::hasAccess("Performance_Appraisals", "edit")) {
                    $evaluation = DB::table('evaluation_periods')->where([
                        ['id', '=', $evaluation_period],
                    ])->first();
                    
                    $evaluationStatus = $evaluation->status;
                    
                    // Checking whether the user has allowed to create performance appraisal process
                    $accessCount = DB::table('performance_appraisals_details')->select('id')->where([
                                    ['is_allowed', '>=', 1],
                                    ['employee_id', '=', $empId]
                                ])->count();
                    
                    $accessCount1 = DB::table('performance_appraisals_details')->select('id')->where([
                                    ['is_allowed', '>=', 2],
                                    ['employee_id', '=', $empId]
                                ])->count();
                    
                    $roleId = DB::table('role_user')->where([
                                ['user_id', '=', $performanceData->employee_id]
                            ])->value('role_id');
                    
                    if($currentStatus == 1 &&  $empId == Auth::user()->context_id ) {
                        return redirect('/dashboard');
                    }
                    
                    if($currentStatus == 2 &&  $empId == Auth::user()->context_id && $accessCount1 == 1 &&  ( $evaluationStatus == 'Goal-Setting' || $evaluationStatus == "Mid-Year-Revision")) {
                        return redirect('/dashboard');
                    }
                    
                    if($currentStatus == 2 &&  $empId == Auth::user()->context_id && $accessCount1 == 1 &&  ( $evaluationStatus == 'Closed') ) {
                        return redirect('/dashboard');
                    }
                 
                    if(($currentStatus == 2 &&  $empId == Auth::user()->context_id && $accessCount1 == 0) &&  ( $evaluationStatus != 'Final-Review')) {
                        return redirect('/dashboard');
                    }
                    
                    if($currentStatus == 5 &&  $empId == Auth::user()->context_id ) {
                        return redirect('/dashboard');
                    }
                    
                    if($currentStatus == 2 &&  $empId != Auth::user()->context_id && $accessCount1 != 1 &&  ( $evaluationStatus == 'Closed') ) {
                        return redirect("/performance_appraisals");
                    }
                    
                    
                    if($currentStatus == 4 &&  $evaluationStatus == "Mid-Year-Revision")   {
                        return redirect('/dashboard');
                    }
                    if($currentStatus == 2 &&  $empId != Auth::user()->context_id && $accessCount1 != 1 &&  ( $evaluationStatus == 'Goal-Setting' || $evaluationStatus == 'Closed') ) {
                        return redirect("/performance_appraisals");
                    }
                    
                    
                    if(($evaluationStatus == 'Closed' || $evaluationStatus == 'Completed' ) && $accessCount == 0 &&  ($currentStatus !=5 && $currentStatus !=6 && $currentStatus != 1)) {
                        return redirect('/dashboard');
                    }
                    
                    else if  ($empId != Auth::user()->context_id &&  $accessCount1 == 0 && $currentStatus == 0)  {
                        return redirect("/performance_appraisals");
                    }
                    else if  ($empId != Auth::user()->context_id &&  $accessCount1 == 0 && $currentStatus == 4 &&  $evaluationStatus == 'Final-Review')  {
                        return redirect("/performance_appraisals");
                    }
                    else if  ($empId == Auth::user()->context_id && ( $currentStatus >= 2   &&  $currentStatus >= 4 ) &&  $evaluationStatus != 'Final-Review')  {
                        return redirect('/dashboard');
                    }
                    else if  ($empId == Auth::user()->context_id &&  $currentStatus == 5)  {
                        return redirect('/dashboard');
                    }
                    else if  ( $currentStatus == 7) {
                        return redirect('/dashboard');
                    }
                    
                    $nextStatus = 0;
                    $steps  = 1;
                    
                    if(($evaluationStatus == "Goal-Setting"  || $evaluationStatus == "Mid-Year-Revision"|| $evaluationStatus == "Closed"  )  && $empId == Auth::user()->context_id )
                    {
                        $nextStatus = 1;
                    }
                    else if($evaluationStatus == "Mid-Year-Revision"  && $empId != Auth::user()->context_id   )
                    {
                        $steps  = 11;
                        $nextStatus = 4;
                    }
                    else if(($evaluationStatus == "Goal-Setting" || $evaluationStatus == "Closed"  || $accessCount > 0 || $accessCount1 > 0) && $empId != Auth::user()->context_id &&  $evaluationStatus != "Final-Review" && $evaluationStatus != "Completed" )
                    {
                        $steps  = 6;
                        $nextStatus = 2;
                        
                    }
                    else if((  $evaluationStatus == "Completed" || $accessCount > 0) && $empId != Auth::user()->context_id )
                    {
                        $steps  = 6;
                        $nextStatus = 6;
                        if($nextStatus == 6) $nextStatus = 7;
                    }
                    
                    
                    //   else if($evaluationStatus == "Final-Review"  && $empId == Auth::user()->context_id &&  $accessCount > 0)
                    else if($evaluationStatus == "Final-Review"  && $empId == Auth::user()->context_id  )
                    {
                        $steps  = 21;
                        $nextStatus = 5;
                    }// else if(($evaluationStatus == "Final-Review"  || $evaluationStatus == "Completed" ) && $empId != Auth::user()->context_id &&  $accessCount > 0)
                    else if(($evaluationStatus == "Final-Review"  || $evaluationStatus == "Completed" ) && $empId != Auth::user()->context_id  )
                    {
                        $nextStatus = 6;
                        if($nextStatus == 6) $nextStatus = 7;
                    }
                    else {
                        $nextStatus = $performanceData->status;
                        $steps      = $performanceData->steps;
                    }
                    
                    
                    // Setting Button text
                    if ($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed')
                    {
                        $buttonText = "Save";
                    }
                    
                    if ( $empId == Auth::user()->context_id )
                    {
                        $submitButtonText = "Submit";
                    }
                    else if ( $empId != Auth::user()->context_id && $nextStatus == 7)
                    {
                        $submitButtonText = "Complete";
                    }
                    
                    $employeeName = DB::table('employees')->where([
                                        ['id', '=', $empId]
                                    ])->value('name');
                    $appraisalPeriod = DB::table('evaluation_periods')->where([
                                            ['id', '=', $evaluation_period]
                                        ])->value('evaluation_period');
                                        
                    // Overall Rating calculation
                    $overallRatingByAppraisee = $this->appraiseeOverallRating($id, $performanceData->employee_id);
                    $overallRatingByAppraiser = $this->appraiserOverallRating($id, $performanceData->employee_id);
                    
                    
                    
                    if(isset($performanceData->id)) {
                        $module = Module::get('Performance_Appraisals');
                        $module->row = $performanceData;
                        
                        $performanceappraisal = Performance_Appraisal::find($id);
                        
                        $memberCount        = DB::table('employees')->select('id')
                                            ->whereNull('deleted_at')
                                            ->where('manager', '=', $performanceData->employee_id)
                                            ->count();
                        
                        
                        return view('la.performance_appraisals.custom-edit', [
                            'module' => $module,
                            'roleId' => $roleId,
                            'userId' => Auth::user()->context_id,
                            'employeeId'   => $performanceData->employee_id,
                            'view_col' => $this->view_col,
                            'evaluationStatus' => $evaluationStatus,
                            'buttonText' => $buttonText,
                            'submitButtonText' => $submitButtonText,
                            'steps' => $steps,
                            'currentStep'   => $performanceData->steps,
                            'currentStatus' => $currentStatus,
                            'nextStatus' => $nextStatus,
                            'startDate' => $startDate,
                            'endDate' => $endDate,
                            'employeeName' => $employeeName,
                            'appraisalPeriod' => $appraisalPeriod,
                            'overallRatingByAppraisee' => $overallRatingByAppraisee,
                            'overallRatingByAppraiser' => $overallRatingByAppraiser,
                            'deptName' => $deptName,
                            'managerName' => $managerName,
                            'accessCount' => $accessCount,
                            'memberCount' => $memberCount
                        ])->with('performance_appraisal', $performanceappraisal);
                    } else {
                        return view('errors.404', [
                            'record_id' => $id,
                            'record_name' => ucfirst("performance_appraisal"),
                        ]);
                    }
                } else {
                    return redirect('/dashboard');
                }
            }
            else
                return redirect('/dashboard');
        } else {
            return redirect('/dashboard');
        }
    }
    
    /**
     * Update the specified performance_appraisal in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Module::hasAccess("Performance_Appraisals", "edit")) {
            
            
            $performanceappraisal = DB::table('performance_appraisals_details')
                                    ->select('id', 'employee_id', 'manager_id', 'evaluation_period', 'status')
                                    ->where('performance_appraisals_id', $id)->first();
            
            $evaluation = DB::table('evaluation_periods')->where([
                ['id', '=',$performanceappraisal->evaluation_period],
                
            ])->first();
            
            $evaluationId = $evaluation->id;
            $evaluationStatus = $evaluation->status;
            $evaluationPeriod = $evaluation->evaluation_period;
            
            $insert_id = Module::updateRow("Performance_Appraisals", $request, $id);
            
            $overallRatingByAppraisee = $this->appraiseeOverallRating($insert_id, $performanceappraisal->employee_id);
            $overallRatingByAppraiser = $this->appraiserOverallRating($insert_id, $performanceappraisal->employee_id);
            
            DB::table('performance_appraisals')
            ->where('id', $id)
            ->update(['overall_rating_by_appraisee' => $overallRatingByAppraisee, 'overall_rating_by_appraiser' => $overallRatingByAppraiser]);
            
            
            
            DB::table('performance_appraisals_details')
            ->where('performance_appraisals_id', $id)
            ->update(['steps' => $request->steps, 'status' => $request->status ]) ;
            
            $performanceappraisal = DB::table('performance_appraisals_details')
                                        ->select('id', 'employee_id', 'manager_id', 'evaluation_period', 'status')
                                        ->where('performance_appraisals_id', $id)
                                        ->first();
            
            //Updating user create performance appraisal process status
            
            
            $accessCount = DB::table('performance_appraisals_details')
                            ->select('id')
                            ->where([
                                ['is_allowed', '=', 2],
                                ['performance_appraisals_id', '=', $id]
                            ])->count();
            
            if ($performanceappraisal->status >= 0 && $performanceappraisal->status < 7 && $accessCount == 1 )
                $is_allowed = 2;
                else
                    $is_allowed = 0;
                    
                    DB::table('performance_appraisals_details')
                    ->where('id', $performanceappraisal->id)
                    ->update(['is_allowed' => $is_allowed]) ;
                    
                    if($performanceappraisal->employee_id == Auth::user()->context_id && ( $performanceappraisal->status == 1  ||  $performanceappraisal->status ==  5))
                        return redirect('dashboard')->withSuccess('The performance appraisal document has been updated successfully.');
                        
                        if ((   $performanceappraisal->status == 2  ) && $performanceappraisal->employee_id != Auth::user()->context_id && $evaluationStatus != 'Mid-Year-Revision' &&  $evaluationStatus != 'Final-Review')
                            return redirect()->route( 'performance_appraisals.index')->withSuccess('The performance appraisal document has been updated successfully.');
						
                       if ($performanceappraisal->status == 7 || $performanceappraisal->status == 4  && $performanceappraisal->employee_id == Auth::user()->context_id)  
                            return redirect()->route('performance_appraisals.index')->withSuccess('The performance appraisal document has been submitted successfully.');
                            
                            if ($performanceappraisal->status == 4 && $evaluationStatus == 'Mid-Year-Revision')
                                return redirect()->route('performance_appraisals.index')->withSuccess('The performance appraisal document has been updated successfully.');
                               
				  return redirect("/performance_appraisals/".$insert_id."/edit")->withSuccess('The performance appraisal document has been updated successfully.');
                                
        } else {
            
            return   redirect('dashboard');
        }
    }
    
    /**
     * Remove the specified performance_appraisal from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        if(Module::hasAccess("Performance_Appraisals", "delete")) {
            Performance_Appraisal::find($id)->delete();
            
            DB::table('performance_appraisals_details')
            ->where('performance_appraisals_id', $id)
            ->update(['deleted_at' =>  date("Y-m-d H:i:s")]);
            
            // Redirecting to index() method
            return redirect()->route('performance_appraisals.index')->withSuccess('The performance appraisal document has been deleted successfully.');
        } else {
            return   redirect('dashboard');
        }
    }
    
    /**
     * Datatable Ajax fetch
     *
     * @return
     */
    public function dtajax($id)
    {
         
        $creationDate   = date("Y-m-d");
        $evaluationCount = 0;
        if ($id != '' && $id > 0) {
            $evaluationCount =  DB::table('evaluation_periods')
                                    ->select('evaluation_periods.*')
                                    ->whereNull('deleted_at')
                                    ->where('id','=',$id)
                                    ->count();
            $evaluation      =  DB::table('evaluation_periods')
                                    ->select('evaluation_periods.*')
                                    ->whereNull('deleted_at')
                                    ->where('id','=',$id)
                                    ->first();
            $evaluationId       = $evaluation->id;
            $evaluationStatus   = $evaluation->status;
        }
        else
        {
            $evaluationCount     = DB::table('evaluation_periods')
                                    ->select('evaluation_periods.*')
                                    ->whereNull('deleted_at')
                                    ->where('start_date','<=',$creationDate)
                                    ->where('end_date','>=',$creationDate)
                                    ->count();
            if ( $evaluationCount > 0) {
                $evaluation      =  DB::table('evaluation_periods')
                                    ->select('evaluation_periods.*')
                                    ->whereNull('deleted_at')
                                    ->orderBy('id', 'desc')
                                    ->first();
                $evaluationId       = $evaluation->id;
                $evaluationStatus   = $evaluation->status;
            } else
            {
                $evaluationId       = 0;
                $evaluationStatus   = "";
                
            }
        }
        
        
        
        $userId = Auth::user()->context_id;
        $teamMemberIds = array();
        
        if (Entrust::hasRole('SUPER_ADMIN')) {
            $teamMembers = DB::select("select id from employees ");
            foreach ($teamMembers as $user) {
                array_push( $teamMemberIds, $user->id );
            }
            
        }
        else {
            
            $this->getMembers( $userId);
            $teamMemberIds = $this->members;
        }
       
        array_push($teamMemberIds, $userId);
        
        if (Entrust::hasRole('SUPER_ADMIN')) {
            
           $values =  DB::table('performance_appraisals')
                        ->leftJoin('performance_appraisals_details', 'performance_appraisals.id', '=', 'performance_appraisals_details.performance_appraisals_id')
                        ->leftJoin('employees', 'performance_appraisals_details.employee_id', '=', 'employees.id')
                        ->leftJoin('departments', 'performance_appraisals_details.department', '=', 'departments.id')
                        ->leftJoin('evaluation_periods', 'performance_appraisals_details.evaluation_period', '=', 'evaluation_periods.id')
                        ->select('performance_appraisals.id', 'employees.name as employee_name', 'departments.name as department_name', 'performance_appraisals_details.manager_id', 'evaluation_periods.evaluation_period',  'performance_appraisals_details.start_date' , 'performance_appraisals_details.end_date', 'performance_appraisals_details.status')
                        ->whereIn('performance_appraisals_details.employee_id', $teamMemberIds)
                        ->whereNull('performance_appraisals.deleted_at')
                        ->where( 'performance_appraisals_details.status', '>', '0' )
                        ->where('performance_appraisals_details.evaluation_period', '=',$evaluationId);
                       
        }
        else {
            $values =  DB::table('performance_appraisals')
                        ->leftJoin('performance_appraisals_details', 'performance_appraisals.id', '=', 'performance_appraisals_details.performance_appraisals_id')
                        ->leftJoin('employees', 'performance_appraisals_details.employee_id', '=', 'employees.id')
                        ->leftJoin('departments', 'performance_appraisals_details.department', '=', 'departments.id')
                        ->leftJoin('evaluation_periods', 'performance_appraisals_details.evaluation_period', '=', 'evaluation_periods.id')
                        ->select('performance_appraisals.id', 'employees.name as employee_name', 'departments.name as department_name', 'performance_appraisals_details.manager_id', 'evaluation_periods.evaluation_period',  'performance_appraisals_details.start_date' , 'performance_appraisals_details.end_date', 'performance_appraisals_details.status')
                        ->whereIn('performance_appraisals_details.manager_id', $teamMemberIds)
                        ->whereNull('performance_appraisals.deleted_at')
                        ->where( 'performance_appraisals_details.status', '>', '0' )
                        ->where('performance_appraisals_details.evaluation_period', '=',$evaluationId);
         }
        
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        for($i=0; $i < count($data->data); $i++) {
            for ($j=0; $j < count($this->listing_cols_data_table); $j++) {
                if($j== 0) {
                    $id =  $data->data[$i][0];
                    
                }
                $col = $this->listing_cols_data_table[$j];
                if($col == 'id' && $data->data[$i][7] != 7 && ( $evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed') )
                    $data->data[$i][$j] = '<input type="checkbox" class="record" name="id[]" value="'.$data->data[$i][0].'">'  ;
                    else if($col == 'id' )
                        $data->data[$i][$j] = '';
                        
                        if($col == 'manager')
                            $data->data[$i][$j] = $this->getManager($data->data[$i][$j]);
                            
                            if($col == 'employee') {
                                
                                $data->data[$i][$j] = '<a href="'.url('/performance_appraisals/'.$id).'">'.$data->data[$i][$j].'</a>';
                            }
                            
            }
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("Performance_Appraisals", "edit")) {
                    if ( $data->data[$i][7] == 1
                        || ( $data->data[$i][7] < 7 &&  ( ($data->data[$i][7] == 2  )  && ($evaluationStatus == 'Mid-Year-Revision' ) ))
                        
                        || ( $data->data[$i][7] == 5 )
                        || ( $data->data[$i][7] == 6  )
                        || ( $data->data[$i][7] != 4 && $data->data[$i][7] != 7 && (  $evaluationStatus == 'Final-Review'
                            ||  $evaluationStatus == 'Completed')))
                        $output .= '<a title="Edit" href="'.url('/performance_appraisals/'.$id.'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                }
                
                if(Module::hasAccess("Performance_Appraisals", "delete") && $data->data[$i][7] != 7) {
                    $output .= Form::open(['route' => [ 'performance_appraisals.destroy', $id], 'method' => 'delete', 'style'=>'display:inline']);
                    $output .= ' <button title="Delete" class="btn btn-danger btn-xs btn-delete" type="button"><i class="fa fa-times"></i></button>';
                    $output .= Form::close();
                }
                
                if($col == 'employee') {
                    
                    $data->data[$i][$j] = '<a href="'.url( '/performance_appraisals/'.$id).'">'.$data->data[$i][$j].'</a>';
                }
                // Appraisal status
                $status = '';
                switch ($data->data[$i][7]) {
                    case 0:
                        $status = 'Goal setting is in progress.';
                        break ;
                    case 1:
                        $status = 'Goal settings is completed by Appraisee';
                        break ;
                    case 2:
                        $status = 'Goal settings is completed by Appraiser';
                        break ;
                    case 3:
                        $status = '';
                        break ;
                    case 4:
                        $status = 'Mid Year Revision is completed by Appraiser';
                        break ;
                    case 5:
                        $status = 'Self rating is completed by Appraisee';
                        break ;
                    case 6:
                        $status = ' Final Review is in progress';
                        break ;
                    case 7:
                        $status = 'Final Review is completed by Appraiser';
                        break ;
                }
                $data->data[$i][7] = $status;
                $data->data[$i][] = (string)$output;
            }
        }
        
        
        $out->setData($data);
        return $out;
    }
    
    
    /**
     * Update final approval Ajax
     *
     * @return
     */
    public function updateStatusAjax(Request $request)  {
        // $insert_id = Module::updateRow("Performance_Appraisals", $request);
        $ids = $request->input( 'ids' );
        $ids = explode(",", $ids);
        $status = DB::table('Performance_Appraisals')
        ->whereIn('id', $ids)
        ->update(['status' => 7]);
        echo $status;
        
    }
    
    public function getManager($id) {
        return  DB::table('employees')->where([
            ['id', '=', $id]
        ])->value('name');
        
    }
    
    public function appraiseeOverallRating($profileId, $employeeId) {
        
        $rating = 0;
        $performance_appraisal = Performance_Appraisal::find($profileId);
        $memberCount           = DB::table('employees')->select('id')
        ->whereNull('deleted_at')
        ->where('manager', '=', $employeeId)
        ->count();
        $cnt  = 0;
        for ($i= 1; $i <= 10; $i++) {
            if(( ($performance_appraisal->{'goal_'.$i}  != 'N/A' && $performance_appraisal->{'goal_'.$i}  != '') &&  $performance_appraisal->{'manager_only_'.$i} == "No" ) || ( trim($performance_appraisal->{'manager_only_'.$i} ) == "Yes" && $memberCount > 0 )) {
                $cnt++;
                $rating = $rating +  ($performance_appraisal->{'rating_by_appraisee_'.$i} * $performance_appraisal->{'weightage_'.$i} / 100);
            }
        }
        $rating = number_format($rating, 2);
        return $rating;
    }
    
    public function appraiserOverallRating($profileId, $employeeId) {
        $rating = 0;
        $rating = 0;
        $performance_appraisal = Performance_Appraisal::find($profileId);
        $memberCount           = DB::table('employees')->select('id')
        ->whereNull('deleted_at')
        ->where('manager', '=', $employeeId)
        ->count();
        $cnt  = 0;
        for ($i= 1; $i <= 10; $i++) {
            if(( ($performance_appraisal->{'goal_'.$i}  != 'N/A' && $performance_appraisal->{'goal_'.$i}  != '') &&  $performance_appraisal->{'manager_only_'.$i} == "No" ) || ( trim($performance_appraisal->{'manager_only_'.$i} ) == "Yes" && $memberCount > 0 )) {
                $cnt++;
                $rating = $rating + ($performance_appraisal->{'rating_by_appraiser_'.$i} * $performance_appraisal->{'weightage_'.$i} / 100);
            }
        }
        $rating = number_format($rating, 2);
        return $rating;
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
            $this->getMembers($ch->id);
        }
        
    }
}
