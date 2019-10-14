<?php
/**
 * Controller genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
use Auth;

/**
 * Class DashboardController
 * @package App\Http\Controllers
 */
class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $userId         = Auth::user()->context_id;
        $creationDate   = date("Y-m-d");
        
          $evaluationCount = DB::table('evaluation_periods')
                            ->select('id', 'evaluation_period', 'status')
                            ->whereNull('deleted_at')
                            ->where('start_date','<=',$creationDate)
                            ->where('end_date','>=',$creationDate)
                            ->count(); 
         
        if($evaluationCount > 0 ) {
            $evaluation     = DB::table('evaluation_periods')
                             ->select('id', 'evaluation_period', 'status')
                             ->whereNull('deleted_at')
                             ->where('start_date','<=',$creationDate)
                             ->where('end_date','>=',$creationDate)
                             ->first();
                
               
            $evaluationId       = $evaluation->id;
            $evaluationStatus   = $evaluation->status; 
            $evaluationPeriod  = $evaluation->evaluation_period;
            $lastEligibleDay   = strstr($evaluationPeriod, '-', true)."-12-31";
           // Checking whether the user has allowed to create performance appraisal document
            $accessCount =  DB::table('performance_appraisals_details')->select('id')->where([
                                 ['is_allowed', '=', 1],
                                 ['employee_id', '=', $userId]
                             ])->count();      
            $accessCount1 = DB::table('performance_appraisals_details')->select('id')->where([
                                 ['is_allowed', '=', 2],
                                 ['employee_id', '=', $userId]
                             ])->count();  
           
            $output = '';
            $records = array();
            $recCount = 0; 
           
           // if ($evaluationStatus == 'Goal-Setting') {
                 $recCount = DB::table('performance_appraisals_details')->select('id')->where([
                               ['deleted_at', '=', NULL],
                               ['evaluation_period', '=', $evaluationId],
                               ['employee_id', '=', $userId]
                             ])->count(); 
            //}
              
                
                 $employees = DB::table('employees')->where([
                                ['id', '=',$userId],
                              ])->first();
                 
                 $dateHire = $employees->date_hire; 
                 if ($accessCount == 1 || ($lastEligibleDay >= $dateHire && $userId !=1  && ($recCount == 0) && ($evaluationStatus != 'Closed' && $evaluationStatus != 'Completed' )))
                    $output =  '<a href="'.url('/performance_appraisals/create').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;">Add Performance Appraisal</a>';
                  //  && ($recCount == 0 || $records == 0)
        }
        else 
        { 
            $output = '';
            $accessCount = 0;
            $accessCount1 = 0;
            $evaluationStatus = '';
        }
      
          $records = DB::table('performance_appraisals')
                    ->leftJoin('performance_appraisals_details', 'performance_appraisals.id', '=', 'performance_appraisals_details.performance_appraisals_id')
                    ->leftJoin('employees', 'performance_appraisals_details.manager_id', '=', 'employees.id')
                    ->leftJoin('departments', 'performance_appraisals_details.department', '=', 'departments.id')
                    ->leftJoin('evaluation_periods', 'performance_appraisals_details.evaluation_period', '=', 'evaluation_periods.id')
                    ->select('performance_appraisals.id', 'employees.name as managerName', 'departments.name', 'evaluation_periods.evaluation_period', 'performance_appraisals_details.status', 'performance_appraisals_details.start_date' , 'performance_appraisals_details.end_date')
                    ->where('performance_appraisals_details.employee_id', '=', $userId)
                    ->whereNull('performance_appraisals_details.deleted_at')
                    ->orderBy('performance_appraisals.id', 'desc')
                    ->get();
            
            return view('la.dashboard' , [
                'userId' => $userId,
                'output' => $output,
                'records' => $records,
                'evaluationStatus' => $evaluationStatus,
                'accessCount' => $accessCount,
                'accessCount1' => $accessCount1,
             ]);
    }
    
   
    
    /**
     * Get file
     *
     * @return \Illuminate\Http\Response
     */
    public function get_file($name)
    {
        
        return response()->download(storage_path ('app\\public\\Templates\\').$name);
        
    }
    
}