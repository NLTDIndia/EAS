@extends("la.layouts.app")

@section("contentheader_title", "Employees")
@section("contentheader_description", "")
@section("section", "Employees")
@section("sub_section", "Listing")
@section("htmlheader_title", "Employees Listing")

@section("headerElems")
@la_access("Employees", "create")
	<button class="btn btn-success btn-sm pull-right"  id="addEmployee">Add Employee</button>
@endla_access
@endsection

@section("main-content")

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul class="list-unstyled">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
	<div class="alert alert-success">
        <ul class="list-unstyled">
    		<li>{!!session('success')!!}</li>
    	</ul>
    </div>	
@endif
 
<div class="box box-success">
	<!--<div class="box-header"></div>-->
	<div class="box-body">
		<form id="frm-example" action="#" method="POST">
			<input type="hidden" id='hdnIds' name="hdnIds">
        	@if((Entrust::hasRole('SUPER_ADMIN') || Entrust::hasRole('HR_MANAGER')))
        	    <div class="pull-left">
        	    <input type="checkbox" id="allEmployees" name="allEmployees" value='1' checked="checked"> Current Employess
        	    </div>
        		<div class="pull-right">
        			<button type="button" id="import-employee" class="btn btn-success">Import Employees</button>
        			<button type="submit" id="approve" class="btn btn-success">Allow to Create Appraisal</button>
				</div>
				<br><br>
            @endif
			<table id="example1" class="table table-bordered">
        		<thead>
            		<tr class="success">
            			@foreach( $listing_cols as $col )
                		 	@if( ucfirst($col) == "Id")
                		 		<th><input name="select_all" value="1" id="example-select-all" type="checkbox" /></th>
                		 	@else
                				<th>{{ $module->fields[$col]['label'] or ucfirst($col) }}</th>
                			@endif 	
            			@endforeach
            			@if($show_actions)
            				 @if(  (Entrust::hasRole('SUPER_ADMIN') ||  Entrust::hasRole('HR_MANAGER'))) 
            				 <th>User Role</th>
            				@endif 
            				<th>Actions</th>
            			@endif
            		</tr>
        		</thead>
    			<tbody></tbody>
    		</table>
    	</form>	
	</div>
</div>
<div class="modal fade" id="confirm" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			 <div class="modal-body">
				<div class="box-body">
                    Are you sure to delete this record?
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-primary" id="delete">Delete</button>
    			<button type="button" data-dismiss="modal" class="btn">Cancel</button>
			</div>
		 </div>
	</div>
</div>
 
 <div class="modal fade" id="date-range" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			 <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Allow to Create Appraisal</h4>
			 </div>
			 <div class="modal-body">
				<div class="box-body">
                   <form name="frmDate" id="frmDate" method="post">
                   		 <div class="form-group">
                   			<label for="start_date">Start date : <span style="color:red">*</span></label>
                   			<div class="input-group date" id="startDate">
                   				<input class="form-control" placeholder="Enter Start date" required="1" name="start_date" autocomplete="off" id="start_date" type="text" value="">
                   				<span class="input-group-addon"><span class="fa fa-calendar"></span></span> 
                   			 </div>
                   			 <label id="start_date-error" class="error" for="start_date"></label>
                   		</div>
                   		<div class="form-group">
                   			<label for="start_date">End date : <span style="color:red">*</span></label>
                   			<div class="input-group date" id="endDate">
                   				<input class="form-control" placeholder="Enter End date" required="1" name="end_date" autocomplete="off" id="end_date" type="text" value="">
                   				<span class="input-group-addon"><span class="fa fa-calendar"></span></span> 
                   			</div>
                   			<label id="end_date-error" class="error" for="end_date"></label>
                   		</div>
                   </form>
				</div>
			</div>
			<div class="modal-footer">
			   <button type="submit" class="btn btn-primary" id="date-range-submit">Save</button>
    		</div>
		 </div>
	</div>
</div>
@la_access("Employees", "create")
 <div class="modal fade" id="import-employees" role="dialog" aria-labelledby="myModalLabel1">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			 <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel1">Import Employees</h4>
			 </div>
			 <form name="frmDate" id="frmImportEmployees" method="post" action="{{ url('/employee_upload_files') }}" enctype="multipart/form-data">
			 <input type="hidden" name="_token" value="{{ csrf_token() }}">
    			 <div class="modal-body">
    				<div class="box-body">
                       
                             <div class="form-group">
                       			<label for="start_date">File : <span style="color:red">*</span></label>
                       			<div class="input-group">
                       				<input type="file" name="employeeFile" id="employeeFile">
                       			</div>
                       			<div><a target ='new' href='<?php echo url("templates\\EAS_Employee_Template.xlsx")?>'>Click here</a> to download the sample template.</div>
                       		</div>
                    	</div>
    				</div>
        			<div class="modal-footer">
        			   <button type="submit" class="btn btn-primary" id="date-range-submit">Import</button>
            		</div>
            	</form>	
		 </div>
	</div>
</div>
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel2">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel2">Add Employee</h4>
			</div>
			{!! Form::open(['action' => 'LA\EmployeesController@store', 'id' => 'employee-add-form']) !!}
			<div class="modal-body">
				<div class="box-body">
                    <div class="mandatory-label">
							<div class="form-group">
								<label for="emp_id">Employee Id :</label>
								<input class="form-control error" placeholder="Enter Employee Id" data-rule-maxlength="5" maxlength="5" required="1" name="emp_id" type="text" value="" aria-required="true" aria-invalid="true">
								<label id="emp_id-error" class="error" for="emp_id" style="display: inline-block;"></label>
							</div>
					</div>
					<div class="form-group">
						<label for="name">Name : <span class="error">*</span></label>
						<input class="form-control" placeholder="Enter Name" data-rule-minlength="5" data-rule-maxlength="250" maxlength="250" required="1" name="name" type="text" value="" aria-required="true">
					</div>
					<div class="form-group">
						<label for="designation">Designation :<span class="error">*</span></label>
						<input class="form-control" placeholder="Enter Designation" data-rule-maxlength="50" maxlength="50" required="1" name="designation" type="text" value="" aria-required="true">
					</div>
					<div class="form-group">
						<label for="gender">Gender : <span class="error">*</span> </label><br>
						<div class="radio">
							<label><input checked="checked" name="gender" type="radio" value="Male"> Male </label>
							<label><input name="gender" type="radio" value="Female"> Female </label>
						</div>
					</div>
					<div class="form-group">
						<label for="mobile">Mobile : <span class="error">*</span></label>
						<input class="form-control" placeholder="Enter Mobile" data-rule-minlength="10" data-rule-maxlength="10" required="1" maxlength="10" id="mobile" name="mobile" type="text" value="" aria-required="true" onkeypress="return isNumberKey(event)">
					</div>
					 
					<div class="mandatory-label">
							@la_input($module, 'email')
					</div>
					<div class="form-group">
						<label for="dept">Department : <span class="error">*</span></label>
						<select class="form-control select2-hidden-accessible" required="1" data-placeholder="Select Department" rel="select2" name="dept"  id="dept" tabindex="-1" aria-hidden="true" aria-required="true">
    						<option value=''>Select Department</option> 
							@foreach($departments as $key => $value)
								<option value='{{$key}}'>{{$value}}</option> 
							@endforeach
						</select>
						<label id="dept-error" class="error" for="dept" style="display: inline-block;"></label>
					 </div>
					 <div class="form-group">
						<label for="manager">Reporting To : <span class="error">*</span></label>
						<select class="form-control select2-hidden-accessible" required="1" data-placeholder="Select Reporting To" rel="select2" id="manager" name="manager" tabindex="-1" aria-hidden="true">
							<option value=''>Reporting To</option> 
							@foreach($members as $key => $value)
								<option value='{{$key}}'>{{$value}}</option> 
							@endforeach
						</select>
						<label id="manager-error" class="error" for="manager"></label>
					</div>
			 
					<div class="form-group">
						<label for="date_hire">Joining Date : <span class="error">*</span></label>
						<div class="input-group date" id="dateHire">
							<input class="form-control" placeholder="Enter Joining Date" required="1" autocomplete = "off" name="date_hire" type="text" value="" aria-required="true">
							<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
						</div>
						<label id="date_hire-error" class="error" for="date_hire"></label>
					</div>
					<div class="form-group" style="display: none">
						<label for="date_left">Resignation Date :</label>
						<div class="input-group date" id="dateLeft">
							<input class="form-control" placeholder="Enter Resignation Date" readonly="true" autocomplete = "off"  name="date_left" type="text" value="">
							<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
						</div>
					</div>
					 <div class="mandatory-label">
							@la_input($module, 'corp_id')
					</div>
					 
					<div class="form-group">
						<label for="role">Role : <span class="error">*</span></label>
						<select class="form-control" required="1" data-placeholder="Select Role" rel="select2" name="role" id="role">
						 <option value=''>Select Role</option> 
								<?php $roles = App\Role::all(); ?>
							@foreach($roles as $role)
								@if($role->id != 1)
									<option value="{{ $role->id }}">{{ $role->name }}</option>
								@endif
							@endforeach
						</select>
						<label id="role-error" class="error" for="role"></label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit"   class="btn btn-success">Save</button> 
			</div>
			{!! Form::close() !!}
		</div>
	</div>
</div>

@endla_access

@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
$(document).ready(function () {   
	
	   var table = 	$("#example1").DataTable({
	   "createdRow": function( row, data, dataIndex){
          if(data[9] != null){
              $(row).addClass('redClass');
          }
        },
		processing: true,
        serverSide: false,
        stateSave: true,
        ajax: "{{ url('/employee_dt_ajax/'.$employeeStatus) }}",
        'type': 'GET',
        "order": [[ 1, "desc" ]],
		language: {
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},
		@if($show_actions)
			columnDefs: [   { orderable: false, targets: [0] }],
		@endif
		 @if(  (!Entrust::hasRole('SUPER_ADMIN') &&  !Entrust::hasRole('HR_MANAGER'))) 
			 columnDefs: [ { visible: false, targets: [0 ,9 ] }],
			 @else 
				  columnDefs: [ { orderable: false, targets: [0] } ], 
		 @endif
		  
	});
	
	$("#employee-add-form").validate({
		
	});
 
 

// Handle click on "Select all" control
$('#example-select-all').on('click', function() {
   // Check/uncheck all checkboxes in the table
   var rows = table.rows({ 'search': 'applied' }).nodes();
   $('input[type="checkbox"]', rows).prop('checked', this.checked);
});

// Handle click on checkbox to set state of "Select all" control
$('#example1 tbody').on('change', 'input[type="checkbox"]', function() {
   // If checkbox is not checked
   if(!this.checked){
      var el = $('#example-select-all').get(0);
      // If "Select all" control is checked and has 'indeterminate' property
      if(el && el.checked && ('indeterminate' in el)){
         // Set visual state of "Select all" control 
         // as 'indeterminate'
         el.indeterminate = true;
      }
   }
});

 $('#approve').on('click', function(e) {
	if({{$evaluationId}} > 0) {
	e.preventDefault();
	var form = this;
    var len = $('input.record:checked').length;
    if(len > 0) {
        var ids = "";
     // Iterate over all checkboxes in the table
    table.$('input[type="checkbox"]').each(function(){  
       // If checkbox doesn't exist in DOM
       if($.contains(document, this)){ 
          // If checkbox is checked
          if(this.checked){ 
            ids += this.value + ",";
              
          }
       } 
    });
    ids = ids.replace(/,\s*$/, "");
    $('#hdnIds').val(ids);
    var validator = $( "#frmDate" ).validate();
   	validator.resetForm(); 
   	$( "#start_date, #end_date" ).removeClass( "error" );
   	$( "#start_date, #end_date" ).val( "" );
    $('#date-range').modal('show'); 
     
   } else {
    	bootbox.alert("Please select at least one record.");
     
	e.preventDefault();
     }
	}
	 else {
	    	bootbox.alert("Please check the Evaluation period. It may be completed or not available.");
	     
		e.preventDefault();
	     }
 });
 
 $('#addEmployee').on('click', function(e) {
	$('#AddModal').modal('show');
	$('#AddModal').on('show.bs.modal', function (e) {
		$("#date_hire, #date_left").val('');
	 	})
});	

 $('#import-employee').on('click', function(e) {
		$('#import-employees').modal('show');
		$('#import-employees').on('show.bs.modal', function (e) {
			
		 	})
	});	

 
 $('#date-range-submit').on('click', function(e) {
		e.preventDefault();
 if($("#frmDate").valid() ) {
	 ids =  $('#hdnIds').val();
	 startDate = $('#start_date').val();
	 endDate   = $('#end_date').val();
	 $.ajaxSetup(
     		{
     		    headers:
     		    {
     		        'X-CSRF-Token': $('input[name="_token"]').val()
     		    }
     		});
     $.ajax({
         type: "POST",
         url: "{{ url('/add_employee_performance_ajax') }}",
         data: {ids: ids, evaluationPeriod: {{$evaluationId}}, startDate: startDate, endDate: endDate},
         success: function( msg ) {
         	$('#example-select-all').prop("checked", false);
         	bootbox.alert('The record has been successfully updated.');
         	table.ajax.reload();
         	$("#start_date").val('');
         	$("#end_date").val('');
         	$('#date-range').modal('hide');
            console.log(msg);
         }
     });
 }
 });

 
//Handle check box value change
$('#example1 tbody').on('change', 'input[type="checkbox"]', function(){
   // If checkbox is not checked
   if(!this.checked){
      var el = $('#example-select-all').get(0);
      // If "Select all" control is checked and has 'indeterminate' property
      if(el && el.checked && ('indeterminate' in el)){
         // Set visual state of "Select all" control 
         // as 'indeterminate'
         el.indeterminate = true;
      }
   }
});

$( "input[name='allEmployees']" ).change(function() {
  // Check input( $( this ).val() ) for validity here
  if(!this.checked)	 
	  valueSelected = '0';
  else 
	  valueSelected = "1";
	$('#example-select-all').prop("checked", false);
  var link = "{{ url('/employee_dt_ajax/')}}";
  table.ajax.url(link+"/"+valueSelected).load();
});

	 $('#example1').on('click', '.btn-delete', function(e) { 
	  var $form = $(this).closest('form');
	 //   e.preventDefault();
	   
	  $('#confirm').modal({
	      backdrop: 'static',
	      keyboard: false
	  })
	  .on('click', '#delete', function(e) {
	      $form.trigger('submit');
	    }); 
	});
 
});
$(function () {  
	$('#startDate, #endDate').datetimepicker({
	    ignoreReadonly: true ,
	    format: 'DD/MM/YYYY'
	});
 	 $('#dateHire, #dateLeft').datetimepicker({
		format: 'DD/MM/YYYY',
		ignoreReadonly: true ,
	    
	}); 
	$("#dateHire").data("DateTimePicker").maxDate(moment());
	 
    $("#startDate").on("dp.change", function (e) {
        $("#endDate").data("DateTimePicker").minDate(e.date);
        $("input[name=end_date]").val('');
    });
	$("#endDate").on("dp.change", function (e) {
        $("#startDate").data("DateTimePicker").maxDate(e.date);
   });
	 
});

$("#addEmployee").click(function(e){
	 var validator = $( "#employee-add-form" ).validate();
	 validator.resetForm(); 
	 $( 'input').removeClass( "error" );
	 $( 'input[name=emp_id], input[name=name], input[name=designation], input[name=mobile], input[name=email], input[name=date_hire], input[name=corp_id]').val( "" );
	 $('select').prop('selectedIndex',0).change();
});
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
$('#employee-add-form').validate({
    // ...
    messages: {
        mobile: "Please enter valid mobile number.",
   }
    // ...
});
</script>
<style>
.mandatory-label label:after {
content:" *";
color:red;
}
.mandatory-label .error:after {
content:"";
}
.redClass {background-color: #e8dfdf;}
</style>
@endpush

 