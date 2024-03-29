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

use App\Role;
use App\Permission;

class RolesController extends Controller
{
	public $show_action = true;
	public $view_col = 'name';
	public $listing_cols = ['id', 'name', 'display_name', 'parent', 'dept'];
	
	public function __construct() {
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Roles', $this->listing_cols);
				return $next($request);
			});
		} else {
			$this->listing_cols = ModuleFields::listingColumnAccessScan('Roles', $this->listing_cols);
		}
	}
	
	/**
	 * Display a listing of the Roles.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Roles');
		
		if(Module::hasAccess($module->id)) {
			return View('la.roles.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'module' => $module
			]);
		} else {
		    return redirect('/dashboard');
        }
	}

	/**
	 * Show the form for creating a new role.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created role in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Roles", "create")) {
		
			$rules = Module::validateRules("Roles", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			$request->name = str_replace(" ", "_", strtoupper(trim($request->name)));
			$ParentRoleId  = $request->parent; 
			
			
			
			$insert_id = Module::insert("Roles", $request);
			
			//Coping role access from the parent role 
			Module::setDefaultRoleAccessFromParentRole($ParentRoleId, $insert_id);
			 
			/*$modules = Module::all();
			foreach ($modules as $module) {
				Module::setDefaultRoleAccess($module->id, $insert_id, "readonly");
			}
			*/
			$role = Role::find($insert_id);
			$perm = Permission::where("name", "ADMIN_PANEL")->first();
			$role->attachPermission($perm);
			
			return redirect()->route('roles.index')->withSuccess('The user role has been added successfully.');
			
		} else {
		    return redirect('/dashboard');
		}
	}

	/**
	 * Display the specified role.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Roles", "view")) {
			
			$role = Role::find($id);
			if(isset($role->id)) {
				$module = Module::get('Roles');
				$module->row = $role;
				
				$modules_arr = DB::table('modules')->get();
				$modules_access = array();
				foreach ($modules_arr as $module_obj) {
					$module_obj->accesses = Module::getRoleAccess($module_obj->id, $id)[0];
					$modules_access[] = $module_obj;
				}
				return view('la.roles.show', [
					'module' => $module,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding",
					'modules_access' => $modules_access
				])->with('role', $role);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("role"),
				]);
			}
		} else {
		    return redirect('/dashboard');
		}
	}

	/**
	 * Show the form for editing the specified role.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("Roles", "edit")) {
			
			$role = Role::find($id);
			if(isset($role->id)) {
				$module = Module::get('Roles');
				
				$module->row = $role;
				
				return view('la.roles.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
				])->with('role', $role);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("role"),
				]);
			}
		} else {
		    return redirect('/dashboard');
		}
	}

	/**
	 * Update the specified role in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Roles", "edit")) {
			
			$rules = Module::validateRules("Roles", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}
			
			$request->name = str_replace(" ", "_", strtoupper(trim($request->name)));
			
			if($request->name == "SUPER_ADMIN") {
				$request->parent = 0;
			}
			
			$roleParent = DB::table('roles')->where([
			    ['id', '=', $id]
			])->value('parent');
			
			$ParentRoleId  = $request->parent; 
			
			$insert_id = Module::updateRow("Roles", $request, $id);
			
			
			
			//Checking user Role whether is modified or not
			
			
			
			if ($roleParent != $request->parent) {
			    //Updating role access from the parent role
			 //   Module::updateRoleAccessFromParentRole($ParentRoleId, $insert_id);
			    $modules = Module::all();
			    foreach ($modules as $module) {
			        Module::updateRoleAccessFromParentRole($module->id, $request->parent, $insert_id);
			    }
			}
			
			return redirect()->route('roles.index')->withSuccess('The user role has been updated successfully.');
			
		} else {
		    return redirect('/dashboard');
		}
	}

	/**
	 * Remove the specified role from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Roles", "delete")) {
		    //Validating whether the employee is a manager or not
		    $recordCount = DB::table('role_user')->select('id')
		    ->where('role_id', '=', $id)
		    ->count(); 
		    if ($recordCount ==0 )  {
			     Role::find($id)->delete();
			     // Redirecting to index() method
			     return redirect()->route('roles.index')->withSuccess('The user role has been deleted successfully.');
		    } else {
		        return redirect()->route('roles.index')->withErrors('Error: Could not delete this record. This role is assigend to some employees.');
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
		$values = DB::table('roles')->select($this->listing_cols)->whereNull('deleted_at');
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Roles');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
			    if($j== 0) {
			        $id =  $data->data[$i][0];
			    }
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url('/roles/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
				// else if($col == "author") {
				//    $data->data[$i][$j];
				// }
			}
			
			if($this->show_action) {
				$output = '';
				if($id != 1 && Module::hasAccess("Roles", "edit")) {
					$output .= '<a  title="Edit" href="'.url('/roles/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if($id != 1 && Module::hasAccess("Roles", "delete")) {
					$output .= Form::open(['route' => ['roles.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
					$output .= ' <button title="Delete" class="btn btn-danger btn-xs btn-delete" type="button"><i class="fa fa-times"></i></button>';
					$output .= Form::close();
				}
				$data->data[$i][] = (string)$output;
			}
		}
		$out->setData($data);
		return $out;
	}
	
	public function save_module_role_permissions(Request $request, $id)
	{
		if(Entrust::hasRole('SUPER_ADMIN')) {
			$role = Role::find($id);
			$module = Module::get('Roles');
			$module->row = $role;
			
			$modules_arr = DB::table('modules')->get();
			$modules_access = array();
			foreach ($modules_arr as $module_obj) {
				$module_obj->accesses = Module::getRoleAccess($module_obj->id, $id)[0];
				$modules_access[] = $module_obj;
			}
		
			$now = date("Y-m-d H:i:s");
			
			foreach($modules_access as $module) {
				
				/* =============== role_module_fields =============== */
	
				foreach ($module->accesses->fields as $field) {
					$field_name = $field['colname'].'_'.$module->id.'_'.$role->id;
					$field_value = $request->$field_name;
					if($field_value == 0) {
						$access = 'invisible';
					} else if($field_value == 1) {
						$access = 'readonly';
					} else if($field_value == 2) {
						$access = 'write';
					} 
	
					$query = DB::table('role_module_fields')->where('role_id', $role->id)->where('field_id', $field['id']);
					if($query->count() == 0) {
						DB::insert('insert into role_module_fields (role_id, field_id, access, created_at, updated_at) values (?, ?, ?, ?, ?)', [$role->id, $field['id'], $access, $now, $now]);    
					} else {
						DB:: table('role_module_fields')->where('role_id', $role->id)->where('field_id', $field['id'])->update(['access' => $access]);
					}
				}
				
				/* =============== role_module =============== */
	
				$module_name = 'module_'.$module->id;
				if(isset($request->$module_name)) {
					$view = 'module_view_'.$module->id;
					$create = 'module_create_'.$module->id;
					$edit = 'module_edit_'.$module->id;
					$delete = 'module_delete_'.$module->id;
					if(isset($request->$view)) {
						$view = 1;
					} else {
						$view = 0;
					}
					if(isset($request->$create)) {
						$create = 1;
					} else {
						$create = 0;
					}
					if(isset($request->$edit)) {
						$edit = 1;
					} else {
						$edit = 0;
					}
					if(isset($request->$delete)) {
						$delete = 1;
					} else {
						$delete = 0;
					}
					
					$query = DB::table('role_module')->where('role_id', $id)->where('module_id', $module->id);
					if($query->count() == 0) {
						DB::insert('insert into role_module (role_id, module_id, acc_view, acc_create, acc_edit, acc_delete, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?)', [$id, $module->id, $view, $create, $edit, $delete, $now, $now]);
					} else {
						DB:: table('role_module')->where('role_id', $id)->where('module_id', $module->id)->update(['acc_view' => $view, 'acc_create' => $create, 'acc_edit' => $edit, 'acc_delete' => $delete]);
					}
				} else {
					DB:: table('role_module')->where('role_id', $id)->where('module_id', $module->id)->update(['acc_view' => 0, 'acc_create' => 0, 'acc_edit' => 0, 'acc_delete' => 0]);
				}
			}
			return redirect('/roles/'.$id.'#tab-access');
		} else {
		    return redirect('/dashboard');
		}
	}
}
