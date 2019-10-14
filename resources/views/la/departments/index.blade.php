@extends("la.layouts.app")

@section("contentheader_title", "Departments")
@section("contentheader_description", "")
@section("section", "Departments")
@section("sub_section", "Listing")
@section("htmlheader_title", "Departments Listing")

@section("headerElems")
@la_access("Departments", "create")
	<button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal" id='addDepartment'>Add Department</button>
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
    		<li>{{session('success')}}</li>
    	</ul>
    </div>	
@endif

<div class="box box-success">
	<!--<div class="box-header"></div>-->
	<div class="box-body">
		<table id="example1" class="table table-bordered">
		<thead>
		<tr class="success">
			@foreach( $listing_cols as $col )
			<th>{{ $module->fields[$col]['label'] or ucfirst($col) }}</th>
			@endforeach
			@if($show_actions)
			<th>Actions</th>
			@endif
		</tr>
		</thead>
		<tbody>
			
		</tbody>
		</table>
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
@la_access("Departments", "create")
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Department</h4>
			</div>
			<span class="col-md-offset-8" style="padding-top:10px;color:red;"> **All fields are mandatory</span>
			{!! Form::open(['action' => 'LA\DepartmentsController@store', 'id' => 'department-add-form']) !!}
			<div class="modal-body">
				<div class="box-body">
                   	@la_input($module, 'name')
                   	<label for="name">Template :</label>
					<select class="form-control" required="1" data-placeholder="Enter Template Name" rel="select2" id="template_name" name="template_name" tabindex="-1" aria-hidden="true" aria-required="true">
						<option value="">Select</option>
						@foreach($templateItems as $key => $val)
						 <option value="{{$val}}">{{$key}}</option>
						@endforeach
					</select>
					<label id="template_name-error" class="error" for="template_name" style="display: inline-block;">This field is required.</label>
					{{-- @la_form($module)
					@la_input($module, 'name')
					@la_input($module, 'tags')
					@la_input($module, 'template_name')
					@la_input($module, 'color')
					--}}
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				{!! Form::submit( 'Submit', ['class'=>'btn btn-success', 'id' => 'btnSubmit']) !!}
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
$(function () {
	$("#example1").DataTable({
		processing: true,
        serverSide: true,
        ajax: "{{ url('/department_dt_ajax') }}",
		language: {
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},
		@if($show_actions)
		columnDefs: [ { orderable: false, targets: [-1] }],
		@endif
	});    
});
$("#addDepartment").click(function(e){
	 var validator = $( "#department-add-form" ).validate();
	 validator.resetForm(); 
	 $( 'input[name=name], input[name=template_name]').removeClass( "error" );
	 $( 'input[name=name]').val("");
	 $("select#template_name").prop('selectedIndex', 0);
	 $( '.select2-selection__placeholder').text("Enter Template Name");
	 $( '#select2-template_name-container').text("");
	
	 
	
});
$("#department-add-form").validate();
$("#department-add-form").on('submit', function(e) {
	var isvalid = $("#department-add-form").valid();
	if(isvalid) {
		e.preventDefault();
		var fileName = document.getElementsByName('template_name')[0].text;
		$.ajaxSetup(
        		{
        			headers:
        		    {
        		        'X-CSRF-Token': $('input[name="_token"]').val()
        		    }
        		});
		$.ajax({
            type: "POST",
            url: "{{ url('/department_file_validation_dt_ajax') }}",
            data: {fileName: fileName},
            success: function( msg ) {  
            	 $("#department-add-form")[0].submit();
			
            }
            
   		});
	}
});
  
 
 

$(document).ready(function() {
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
</script>
@endpush
