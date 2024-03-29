@extends("la.layouts.app")

@section("contentheader_title", "Organizations")
@section("contentheader_description", "organizations listing")
@section("section", "Organizations")
@section("sub_section", "Listing")
@section("htmlheader_title", "Organizations Listing")

@section("headerElems")
@la_access("Organizations", "create")
	<button class="btn btn-success btn-sm pull-right" data-toggle="modal" data-target="#AddModal">Add Organization</button>
@endla_access
@endsection

@section("main-content")

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
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
@la_access("Organizations", "create")
<div class="modal fade" id="AddModal" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Organization</h4>
			</div>
			{!! Form::open(['action' => 'LA\OrganizationsController@store', 'id' => 'organization-add-form']) !!}
			<div class="modal-body">
				<div class="box-body">
                    @la_form($module)
					
					{{--
					@la_input($module, 'name')
					@la_input($module, 'email')
					@la_input($module, 'phone')
					@la_input($module, 'website')
					@la_input($module, 'assigned_to')
					@la_input($module, 'connect_since')
					@la_input($module, 'address')
					@la_input($module, 'city')
					@la_input($module, 'description')
					@la_input($module, 'profile_image')
					@la_input($module, 'profile')
					--}}
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				{!! Form::submit( 'Submit', ['class'=>'btn btn-success']) !!}
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
        ajax: "{{ url('/organization_dt_ajax') }}",
		language: {
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},
		@if($show_actions)
		columnDefs: [ { orderable: false, targets: [-1] }],
		@endif
	});
	$("#organization-add-form").validate({
		
	});
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