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

use App\Models\Department;
use App\Models\Upload;
class DepartmentsController extends Controller
{
	public $show_action = true;
	public $view_col = 'name';
	public $listing_cols = ['id', 'name', 'tags', 'template_name', 'color'];
	
	public function __construct() {
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Departments', $this->listing_cols);
				return $next($request);
			});
		} else {
			$this->listing_cols = ModuleFields::listingColumnAccessScan('Departments', $this->listing_cols);
		}
	}
	
	/**
	 * Display a listing of the Departments.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Departments');
		$templateItems = Upload::where('extension','xlsx')->where('extension','xlsx')->pluck('id', 'name')->toArray();
		if(Module::hasAccess($module->id)) {
			return View('la.departments.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
			    'templateItems' => $templateItems,
				'module' => $module
			]);
		} else {
		    return redirect('/dashboard');
        }
	}

	/**
	 * Show the form for creating a new department.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
	    return redirect('/dashboard');
	}

	/**
	 * Store a newly created department in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Departments", "create")) {
		
			$rules = Module::validateRules("Departments", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			$templateId      =  $request->template_name;
            $templateName    =   DB::table('uploads')->where('id', '=', $templateId)->value('name');
			$path_parts = pathinfo(storage_path('uploads/'.$templateName));
			
			if($path_parts['extension'] != 'xlsx') {
			    return redirect()->back()->withErrors('Error: Invalid file type. Please choose valid xlsx file.');
			}
			if(!file_exists(storage_path('uploads/'.$templateName))) {
			    return redirect()->back()->withErrors('Error: '.$templateName. ' is missing in the storage folder.');
			}  
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			$insert_id = Module::insert("Departments", $request);
			
			return redirect()->route('departments.index')->withSuccess('The department has been added successfully.');
			
		} else {
		    return redirect('/dashboard');
		}
	}

	/**
	 * Display the specified department.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Departments", "view")) {
			
			$department = Department::find($id);
			if(isset($department->id)) {
				$module = Module::get('Departments');
				$module->row = $department;
				
				return view('la.departments.show', [
					'module' => $module,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding"
				])->with('department', $department);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("department"),
				]);
			}
		} else {
		    return redirect('/dashboard');
		}
	}

	/**
	 * Show the form for editing the specified department.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("Departments", "edit")) {			
			$department = Department::find($id);
			if(isset($department->id)) {	
				$module = Module::get('Departments');
				$templateItems = Upload::whereNull('deleted_at')->where('extension','xlsx')->pluck('id', 'name')->toArray();
				$module->row = $department;
				return view('la.departments.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
				    'templateItems' => $templateItems,
				])->with('department', $department);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("department"),
				]);
			}
		} else {
		    return redirect('/dashboard');
		}
	}

	/**
	 * Update the specified department in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Departments", "edit")) {
			
			$rules = Module::validateRules("Departments", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			$templateId      =  $request->template_name;
			$templateName    =  DB::table('uploads')->where('id', '=', $templateId)->value('name');
			$path_parts      =  pathinfo(storage_path('uploads/'.$templateName));
			
			
			if($path_parts['extension'] != 'xlsx') {
			    return redirect()->back()->withErrors('Error : Invalid file type. Please choose valid xlsx file.');
			}
			if ($validator->fails()  ) {
			    return redirect()->back()->withErrors($validator)->withInput();
			}
			
			if(!file_exists(storage_path('uploads/'.$templateName))) {
			      return redirect()->back()->withErrors('Error: '.$templateName. ' is missing in the storage folder.');
			}  
			
			
			$insert_id = Module::updateRow("Departments", $request, $id);
			
			return redirect()->route('departments.index')->withSuccess('The department has been updated successfully.');
			
		} else {
		    return redirect('/dashboard');
		}
	}

	/**
	 * Remove the specified department from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Departments", "delete")) {
		    
		    //Validating if there is any user is associated with this department or not.
		    
		    $recordCount = DB::table('employees')->select('id')
                		    ->whereNull('deleted_at')
                		    ->where('dept', '=', $id)
                		    ->count();   
		    if ($recordCount ==0 )  {
		       Department::find($id)->delete();
		      // Redirecting to index() method
		      return redirect()->route('departments.index')->withSuccess('The department has been deleted successfully.');
		    }
		    else {
		        // Redirecting to index() method
		        return redirect()->route('departments.index')->withErrors('Could not delete this record. Because there is some employees records are related to this department record.');
		        
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
		$values = DB::table('departments')->select($this->listing_cols)->whereNull('deleted_at');
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Departments');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url('/departments/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
				// else if($col == "author") {
				//    $data->data[$i][$j];
				// }
			}
			
			if($this->show_action) {
				$output = '';
				if(Module::hasAccess("Departments", "edit")) {
					$output .= '<a title="Edit" href="'.url('/departments/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Departments", "delete")) {
					$output .= Form::open(['route' => ['departments.destroy', $data->data[$i][0]], 'method' => 'delete', 'class' => 'delete-form', 'style'=>'display:inline']);
					$output .= ' <button title="Delete" class="btn btn-danger btn-xs btn-delete" type="button" id="'.$data->data[$i][0].'"><i class="fa fa-times"></i></button>';
					 $output .= Form::close();
				}
				$data->data[$i][] = (string)$output;
			}
		}
		$out->setData($data);
		return $out;
	}
	
	/**
	 * Verifying the file in the storage path.
	 *
	 * @return
	 */
	public function fileValidationAjax(Request $request)  {
	  /*  $templateName = $request->input( 'fileName' );
	    $path_parts = pathinfo(storage_path('uploads/'.$templateName));
	    if($path_parts['extension'] != 'xlsx') {
	        echo "Error : Invalid file type. Please choose valid xlsx file.";
	    }
	    if(!file_exists(storage_path('uploads/'.$templateName))) {
	        echo "Error : File not found.";  
	    }  
	    else */
	        echo 'success';
	}
}
