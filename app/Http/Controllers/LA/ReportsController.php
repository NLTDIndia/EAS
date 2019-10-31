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
use App\Models\Evaluation_Period;
use Zizaco\Entrust\EntrustFacade as Entrust;
use Datatables;

/**
 * Class ReportsController
 * @package App\Http\Controllers
 */
class ReportsController extends Controller
{
    public $show_action = true;
    public $members = array();
    public $listing_cols = ['id' ];
    public $listing_cols_data_table = ['doc id', 'emp id', 'employee', 'dept', 'manager', 'evaluation period', 'start date', 'end date', 'status'];
    
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
     * Show the application reports.
     *
     * @return Response
     */
    public function index()
    {
        $userId             = Auth::user()->context_id;
        $creationDate       = date("Y-m-d");
        $evaluationItems    = Evaluation_period::orderBy('id', 'desc')->pluck('evaluation_period', 'id')->toArray();
        $memberCount        = DB::table('employees')->select('id')
                                ->whereNull('deleted_at')
                                ->whereNull('date_left')
                                ->where('manager', '=', $userId)
                                ->count();
        $noOfEmployees      = DB::table('employees')->select('id')
                                ->whereNull('deleted_at')
                                ->whereNull('date_left')
                                ->count();
                            
        $evaluationStatus  = '';
        $evaluationId      = 0;
        $evaluationCount    = DB::table('evaluation_periods')
                                ->select('id', 'evaluation_period', 'status')
                                ->whereNull('deleted_at')
                                ->where('start_date','<=',$creationDate)
                                ->where('end_date','>=',$creationDate)
                                ->count(); 
        if($evaluationCount > 0 ) {
        $evaluation         = DB::table('evaluation_periods')
                                ->select('id', 'evaluation_period', 'status')
                                ->whereNull('deleted_at')
                                ->where('start_date','<=',$creationDate)
                                ->where('end_date','>=',$creationDate)
                                ->first();
       
       
        $evaluationStatus   = $evaluation->status;
        $evaluationId       = $evaluation->id;
        $evaluationPeriod   = $evaluation->evaluation_period;
        $lastEligibleDay    = strstr($evaluationPeriod, '-', true)."-12-31";
        $noOfEligibleEmployees      = DB::table('employees')->select('id')
                                        ->whereNull('deleted_at')
                                        ->whereNull('date_left')
                                        ->where('date_hire', '<=',$lastEligibleDay )
                                        ->count();
        }
         
        
                                
        $teams = array();
        
        $this->getMembers( $userId);
        $teams = $this->members;
        array_push($teams, $userId);
        
        $documentCount = DB::table('Performance_Appraisals_details')->select('id')
                        ->whereNull('deleted_at')
                        ->whereIn('manager_id', $teams)
                        ->count();
        
        if(Entrust::hasRole('SUPER_ADMIN') || Entrust::hasRole('HR_MANAGER')) {
           return View('la.reports.custom-index', [
                    'show_actions' => $this->show_action,
                    'listing_cols' => $this->listing_cols,
                    'listing_cols_data_table' => $this->listing_cols_data_table,
                    'evaluationStatus' => $evaluationStatus,
                    'evaluationItems' => $evaluationItems,
                    'evaluationId' => $evaluationId,
                    'noOfEmployees' => $noOfEmployees,
                    'noOfEligibleEmployees' => $noOfEligibleEmployees
                ]);
            } else {
                return redirect('/dashboard');
            }
     }
    
    public function ratings()
    { 
        $userId             = Auth::user()->context_id;
        $creationDate       = date("Y-m-d");
        $evaluationItems    = Evaluation_period::orderBy('id', 'desc')->pluck('evaluation_period', 'id')->toArray();
        $evaluation         = DB::table('evaluation_periods')
                            ->select('id', 'evaluation_period', 'status')
                            ->whereNull('deleted_at')
                            ->where('start_date','<=',$creationDate)
                            ->where('end_date','>=',$creationDate)
                            ->first();
        $memberCount        = DB::table('employees')->select('id')
                            ->whereNull('deleted_at')
                            ->whereNull('date_left')
                            ->where('manager', '=', $userId)
                            ->count();
        if(count($evaluation) > 0) {
            $evaluationStatus = $evaluation->status;
            $evaluationId = $evaluation->id;
        }
        else {
            $evaluationStatus  = '';
            $evaluationId      = 0;
        }
        
        $teams = array();
        
        $this->getMembers( $userId);
        $teams = $this->members;
        array_push($teams, $userId);
        
        $documentCount = DB::table('Performance_Appraisals_details')->select('id')
                            ->whereNull('deleted_at')
                            ->whereIn('manager_id', $teams)
                            ->count();
        
        if($memberCount > 0  ||  $documentCount > 0 || Entrust::hasRole('SUPER_ADMIN') || Entrust::hasRole('HR_MANAGER')) {
            if(1==1) {
                return View('la.reports.custom-ratings', [
                    'show_actions' => $this->show_action,
                    'listing_cols' => $this->listing_cols,
                    'listing_cols_data_table' => $this->listing_cols_data_table,
                    'evaluationStatus' => $evaluationStatus,
                    'evaluationItems' => $evaluationItems,
                    'evaluationId' => $evaluationId
                ]);
            } else {
                return redirect('/dashboard');
            }
        }
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
    
    public function getManager($id) {
        return  DB::table('employees')->where([
            ['id', '=', $id]
        ])->value('name');
        
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
            $evaluationPeriod  = $evaluation->evaluation_period;
            $lastEligibleDay   = strstr($evaluationPeriod, '-', true)."-12-31";
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
                $evaluationPeriod  = $evaluation->evaluation_period;
                $lastEligibleDay   = strstr($evaluationPeriod, '-', true)."-12-31";
            } else
            {
                $evaluationId       = 0;
                $evaluationStatus   = "";
                $lastEligibleDay    = "";
                
            }
        }
        
        
        
        $userId = Auth::user()->context_id;
        $teamMemberIds = array();
        
        if (Entrust::hasRole('SUPER_ADMIN') || Entrust::hasRole('HR_MANAGER')) {
           $teamMembers = DB::select("select id from employees ");
            foreach ($teamMembers as $user) {
                array_push( $teamMemberIds, $user->id );
            }
            array_push($teamMemberIds, $userId);
        }
       
       $values =  DB::table('evaluation_periods')
                   ->leftJoin('performance_appraisals_details', 'evaluation_periods.id' , '=', 'performance_appraisals_details.evaluation_period' )
                   ->leftJoin('performance_appraisals', 'performance_appraisals_details.id', '=' ,'performance_appraisals.id' )
                   ->rightJoin('employees', 'performance_appraisals_details.employee_id', '=', 'employees.id')
                   ->leftJoin('departments', 'employees.dept', '=', 'departments.id')
                   ->select('performance_appraisals_details.performance_appraisals_id', 'employees.emp_id as employee_id',  'employees.name as employee_name', 'departments.name as department_name',
                    DB::Raw('IFNULL(`performance_appraisals_details`.`manager_id`, `employees`.`manager` )'),
                    DB::Raw('(SELECT `evaluation_period` FROM evaluation_periods WHERE id = '.$evaluationId.')'),
                    DB::Raw('IFNULL(`performance_appraisals_details`.`start_date`, (SELECT `start_date` FROM evaluation_periods WHERE id = '.$evaluationId.'))'),
                    DB::Raw('IFNULL(`performance_appraisals_details`.`end_date`, (SELECT `end_date` FROM evaluation_periods WHERE id = '.$evaluationId.'))'),
                    DB::Raw('IFNULL(`performance_appraisals_details`.`status`, "-1" )'))
                    ->whereNull('employees.deleted_at')
                    ->where('employees.date_hire', '<=', $lastEligibleDay)
                    ->whereNull('performance_appraisals.deleted_at')
                    ->where('evaluation_periods.id', '=',$evaluationId)
                    ->orWhere('performance_appraisals_details.evaluation_period', '=',null)
                    ->whereNull('employees.deleted_at')
                    ->where('employees.date_hire', '<=', $lastEligibleDay)
                    ->whereNull('performance_appraisals.deleted_at'); 
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        for($i=0; $i < count($data->data); $i++) {
            for ($j=0; $j < count($this->listing_cols_data_table); $j++) {
               /*  if($j== 0) {
                    if( $data->data[$i][$j] == 0 ||  $data->data[$i][$j] == null ) 
                        $id = '#';
                    else $id =  $data->data[$i][0];
                } */
                $col = $this->listing_cols_data_table[$j];
                
                        if($col == 'manager')
                            $data->data[$i][$j] = $this->getManager($data->data[$i][$j]);
                            if($col == 'start date' || $col == 'end date')
                             	$data->data[$i][$j] = date_format(date_create($data->data[$i][$j]), "d-m-Y");
            }
            if($this->show_action) {
                $output = '';
               
                // Appraisal status
                $status = '';
                switch ($data->data[$i][8]) {
                    case -1:
                        $status = 'Goal setting is not started';
                        break ;
                    case 0:
                        $status = 'Goal setting is in progress';
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
                if($data->data[$i][0] == 0 || $data->data[$i][0] == null) 
                {
                    $status = 'Goal setting is not started';
                    $data->data[$i][0] = '#';
                }
                else 
                    $data->data[$i][0] = '<a target="_blank" href="'.url( '/performance_appraisals/'.$data->data[$i][0]).'">'.$data->data[$i][0].'</a>';
                
                $data->data[$i][8] = $status;
                
                $data->data[$i][] = (string)$output;
            }
        }
        
        
        $out->setData($data);
        return $out;
    }
    
    /**
     * Datatable Ajax fetch
     *
     * @return
     */
    public function dtratings($id)
    {
        
       
    }
    
}