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

use App\Models\Evaluation_Period;

class Evaluation_PeriodsController extends Controller
{
	public $show_action = true;
	public $view_col = 'evaluation_period';
	public $listing_cols = ['id', 'evaluation_period', 'status', 'start_date', 'end_date'];
	
	public function __construct() {
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Evaluation_Periods', $this->listing_cols);
				return $next($request);
			});
		} else {
			$this->listing_cols = ModuleFields::listingColumnAccessScan('Evaluation_Periods', $this->listing_cols);
		}
	}
	
	/**
	 * Display a listing of the Evaluation_Periods.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Evaluation_Periods');
		
		if(Module::hasAccess($module->id)) {
			return View('la.evaluation_periods.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'module' => $module
			]);
		} else {
		    return redirect('/dashboard');
        }
	}

	/**
	 * Show the form for creating a new evaluation_period.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created evaluation_period in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Evaluation_Periods", "create")) {
		
			$rules = Module::validateRules("Evaluation_Periods", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			$startDate =  implode("-", array_reverse(explode("/", $request->start_date)));
			$endDate   =  implode("-", array_reverse(explode("/", $request->end_date)));
			if ($startDate > $endDate)
			    return redirect()->back()->withErrors('Error : Please enter valid start end date. ');
			    
			//Validating start and End date
			
			
			if ($startDate > $endDate)
			    return redirect()->back()->withErrors('Error : Please enter valid end date. ');
			    
			$resultCount = 0;
			 
			$evaluationCount   =   DB::select("select count(id) as rowcount from `evaluation_periods` where deleted_at is null and ( '$startDate' between `start_date` and `end_date` ) OR ('$endDate' between `start_date` and `end_date`)");
			foreach ($evaluationCount as $eva) {
			    $resultCount = $eva->rowcount;
			}
			 
			if($resultCount > 0)
			    return redirect()->back()->withErrors('Error : Start date or end date is already exists.');
			    
			// Getting Template name from the department table and verifying the file in the storage folder
			
			$departmentsCount = DB::table('departments')
			->select('id', 'name', 'template_name')
			->whereNull('deleted_at')
			->count();
			
			if ($departmentsCount == 0)
			    return redirect()->back()->withErrors('Error : Department is missing.');
			    
			    $departments = DB::table('departments')
			                     ->leftJoin('uploads', 'departments.template_name', '=', 'uploads.id')
                			     ->select('departments.id', 'departments.name as deptName', 'uploads.name as templateName')
                			     ->whereNull('departments.deleted_at')
                			     ->get();
			    $errorMsg = "";
			    foreach ($departments as $dept) {
			        $templateName =  $dept->templateName;
			        if(!file_exists(storage_path('uploads/'.$templateName)) || $templateName == '') {
			            $errorMsg .= "<li>".$dept->deptName. " department template file '".$templateName. "' is missing in the storage folder. </li>";
			            
			        }
			    }
			    
			    if($errorMsg != '')
			        return redirect()->back()->withErrors($errorMsg);
			        
			$insert_id = Module::insert("Evaluation_Periods", $request);
			
			return redirect()->route('evaluation_periods.index')->withSuccess('The evalution period has been added successfully.');
			
		} else {
		    return redirect('/dashboard');
		}
	}

	/**
	 * Display the specified evaluation_period.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Evaluation_Periods", "view")) {
			
			$evaluation_period = Evaluation_Period::find($id);
			if(isset($evaluation_period->id)) {
				$module = Module::get('Evaluation_Periods');
				$module->row = $evaluation_period;
				
				return view('la.evaluation_periods.show', [
					'module' => $module,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding"
				])->with('evaluation_period', $evaluation_period);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("evaluation_period"),
				]);
			}
		} else {
		    return redirect('/dashboard');
		}
	}

	/**
	 * Show the form for editing the specified evaluation_period.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("Evaluation_Periods", "edit")) {			
			$evaluation_period = Evaluation_Period::find($id);
			if(isset($evaluation_period->id)) {	
				$module = Module::get('Evaluation_Periods');
				
				$module->row = $evaluation_period;
				$startDate =  implode("/", array_reverse(explode("-", $evaluation_period->start_date)));
				$endDate   =  implode("/", array_reverse(explode("-", $evaluation_period->end_date)));
				return view('la.evaluation_periods.edit', [
					'module' => $module,
				    'startDate' => $startDate,
				    'endDate' => $endDate,
					'view_col' => $this->view_col,
				])->with('evaluation_period', $evaluation_period);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("evaluation_period"),
				]);
			}
		} else {
		    return redirect('/dashboard');
		}
	}

	/**
	 * Update the specified evaluation_period in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Evaluation_Periods", "edit")) {
			
			$rules = Module::validateRules("Evaluation_Periods", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			//Validating start and End date
			
			$startDate =  implode("-", array_reverse(explode("/", $request->start_date)));
			$endDate   =  implode("-", array_reverse(explode("/", $request->end_date)));
			$resultCount = 0;
			
			if ($startDate > $endDate ||  $endDate <   $startDate) 
			    return redirect()->back()->withErrors('Error : Please enter valid start and end date. ');
			      $evaluationCount   =   DB::select("select count(id) as rowcount from `evaluation_periods`   where id != $id and deleted_at is null and ( ( '$startDate' between `start_date` and `end_date` ) OR ('$endDate' between `start_date` and `end_date`))");
			
			foreach ($evaluationCount as $eva) {
			    $resultCount = $eva->rowcount;
			}
			
			if($resultCount > 0)
			    return redirect()->back()->withErrors('Error : Start date or end date is already exists.');
			
			// Getting Template name from the department table and verifying the file in the storage folder
			
			$departmentsCount = DB::table('departments')
                        			->select('id', 'name', 'template_name')
                        			->whereNull('deleted_at')
                        			->count();
			
            if ($departmentsCount == 0) 
                return redirect()->back()->withErrors('Error : Department is missing.');
            
                $departments = DB::table('departments')
                                ->leftJoin('uploads', 'departments.template_name', '=', 'uploads.id')
                                ->select('departments.id', 'departments.name as deptName', 'uploads.name as templateName')
                                ->whereNull('departments.deleted_at')
                                ->get();
                $errorMsg = "";
                foreach ($departments as $dept) {
                    $templateName =  $dept->templateName;
                    if(!file_exists(storage_path('uploads/'.$templateName)) || $templateName == '') {
                        $errorMsg .= "<li>".$dept->deptName. " department template file '".$templateName. "' is missing in the storage folder.</li>";
                        
                    }
                }
            
            if($errorMsg != '')    			
                return redirect()->back()->withErrors($errorMsg);
			
			$insert_id = Module::updateRow("Evaluation_Periods", $request, $id);
			
			return redirect()->route('evaluation_periods.index')->withSuccess('The evalution period has been updated successfully.');
			
		} else {
		    return redirect('/dashboard');
		}
	}

	/**
	 * Remove the specified evaluation_period from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Evaluation_Periods", "delete")) {
		    //Validating if there is any document is create and the status is submitted (0) and not the record is not deleted
		    
		    $recordCount = DB::table('performance_appraisals_details')->select('id')
		                  ->whereNull('deleted_at')
		                  ->where('status','>',0)
		                  ->where('evaluation_period','=',$id)
		                  ->count();   
		    
		   if ($recordCount ==0 )  {
		       //Updating deleted_at column of the performance_appraisal table for pending documenent 
		       DB::table('performance_appraisals_details')
		          ->where('evaluation_period','=',$id)
		          ->where('status','=',0)
		          ->update(['deleted_at' => date("Y-m-d H:i:s")]);
		       
		         Evaluation_Period::find($id)->delete();
			    // Redirecting to index() method
			     return redirect()->route('evaluation_periods.index')->withSuccess('The evalution period has been deleted successfully.');
		   }
		   else {
		       // Redirecting to index() method
		       return redirect()->route( 'evaluation_periods.index')->withErrors('Could not delete this record. Because there is some documents are associated with this Evaluation Period.');
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
	public function dtajax()
	{
		$values = DB::table('evaluation_periods')->select($this->listing_cols)->whereNull('deleted_at');
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Evaluation_Periods');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url('/evaluation_periods/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
				if($col == 'start_date' || $col == 'end_date')
				    $data->data[$i][$j] = date_format(date_create($data->data[$i][$j]), "d-m-Y");
			}
			
			if($this->show_action) {
				$output = '';
				if(Module::hasAccess("Evaluation_Periods", "edit")) {
					$output .= '<a title="Edit" href="'.url('/evaluation_periods/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Evaluation_Periods", "delete")) {
					$output .= Form::open(['route' => ['evaluation_periods.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
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
