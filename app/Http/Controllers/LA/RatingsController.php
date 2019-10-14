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

use App\Models\Rating;

class RatingsController extends Controller
{
	public $show_action = true;
	public $view_col = 'rating';
	public $listing_cols = ['id', 'rating'];
	
	public function __construct() {
		// Field Access of Listing Columns
		if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
			$this->middleware(function ($request, $next) {
				$this->listing_cols = ModuleFields::listingColumnAccessScan('Ratings', $this->listing_cols);
				return $next($request);
			});
		} else {
			$this->listing_cols = ModuleFields::listingColumnAccessScan('Ratings', $this->listing_cols);
		}
	}
	
	/**
	 * Display a listing of the Ratings.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$module = Module::get('Ratings');
		
		if(Module::hasAccess($module->id)) {
			return View('la.ratings.index', [
				'show_actions' => $this->show_action,
				'listing_cols' => $this->listing_cols,
				'module' => $module
			]);
		} else {
		    return redirect('/dashboard');
        }
	}

	/**
	 * Show the form for creating a new rating.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created rating in database.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		if(Module::hasAccess("Ratings", "create")) {
		
			$rules = Module::validateRules("Ratings", $request);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();
			}
			
			$insert_id = Module::insert("Ratings", $request);
			
			return redirect()->route('ratings.index')->withSuccess('The rating details has been added successfully.');
			
		} else {
		    return redirect('/dashboard');
		}
	}

	/**
	 * Display the specified rating.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if(Module::hasAccess("Ratings", "view")) {
			
			$rating = Rating::find($id);
			if(isset($rating->id)) {
				$module = Module::get('Ratings');
				$module->row = $rating;
				
				return view('la.ratings.show', [
					'module' => $module,
					'view_col' => $this->view_col,
					'no_header' => true,
					'no_padding' => "no-padding"
				])->with('rating', $rating);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("rating"),
				]);
			}
		} else {
		    return redirect('/dashboard');
		}
	}

	/**
	 * Show the form for editing the specified rating.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		if(Module::hasAccess("Ratings", "edit")) {			
			$rating = Rating::find($id);
			if(isset($rating->id)) {	
				$module = Module::get('Ratings');
				
				$module->row = $rating;
				
				return view('la.ratings.edit', [
					'module' => $module,
					'view_col' => $this->view_col,
				])->with('rating', $rating);
			} else {
				return view('errors.404', [
					'record_id' => $id,
					'record_name' => ucfirst("rating"),
				]);
			}
		} else {
		    return redirect('/dashboard');
		}
	}

	/**
	 * Update the specified rating in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		if(Module::hasAccess("Ratings", "edit")) {
			
			$rules = Module::validateRules("Ratings", $request, true);
			
			$validator = Validator::make($request->all(), $rules);
			
			if ($validator->fails()) {
				return redirect()->back()->withErrors($validator)->withInput();;
			}
			
			$insert_id = Module::updateRow("Ratings", $request, $id);
			 
			return redirect()->route('ratings.index')->withSuccess('The rating details has been updated successfully.');
			
		} else {
		    return redirect('/dashboard');
		}
	}

	/**
	 * Remove the specified rating from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		if(Module::hasAccess("Ratings", "delete")) {
			Rating::find($id)->delete();
			
			// Redirecting to index() method
			return redirect()->route('ratings.index')->withSuccess('The rating details has been deleted successfully.');
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
		$values = DB::table('ratings')->select($this->listing_cols)->whereNull('deleted_at');
		$out = Datatables::of($values)->make();
		$data = $out->getData();

		$fields_popup = ModuleFields::getModuleFields('Ratings');
		
		for($i=0; $i < count($data->data); $i++) {
			for ($j=0; $j < count($this->listing_cols); $j++) { 
				$col = $this->listing_cols[$j];
				if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
					$data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
				}
				if($col == $this->view_col) {
					$data->data[$i][$j] = '<a href="'.url('/ratings/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
				}
				// else if($col == "author") {
				//    $data->data[$i][$j];
				// }
			}
			
			if($this->show_action) {
				$output = '';
				if(Module::hasAccess("Ratings", "edit")) {
					$output .= '<a title="Edit" href="'.url( '/ratings/'.$data->data[$i][0].'/edit').'" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
				}
				
				if(Module::hasAccess("Ratings", "delete")) {
					$output .= Form::open(['route' => [ 'ratings.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
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
