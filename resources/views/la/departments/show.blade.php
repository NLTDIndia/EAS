@extends('la.layouts.app')

@section('htmlheader_title')
	Department View
@endsection


@section('main-content')
@if(session('success'))
	<div class="alert alert-success">
        <ul class="list-unstyled">
    		<li>{{session('success')}}</li>
    	</ul>
    </div>	
@endif
<div id="page-content" class="profile2">
	<div class="bg-primary clearfix">
		<div class="col-md-4">
			<div class="row">
				<div class="col-md-3">
					<!--<img class="profile-image" src="{{ asset('la-assets/img/avatar5.png') }}" alt="">-->
					<div class="profile-icon text-primary"><i class="fa {{ $module->fa_icon }}"></i></div>
				</div>
				<div class="col-md-9">
					<h4 class="name">{{ $department->$view_col }}</h4>
					<div class="row stats"></div>
					 
				</div>
			</div>
		</div>
		<div class="col-md-3"></div>
		<div class="col-md-4"></div>
		<div class="col-md-1 actions">
			@la_access("Departments", "edit")
				<a href="{{ url('/departments/'.$department->id.'/edit') }}" class="btn btn-xs btn-edit btn-default"><i class="fa fa-pencil"></i></a><br>
			@endla_access
			
			@la_access("Departments", "delete")
				{{ Form::open(['route' => ['departments.destroy', $department->id], 'method' => 'delete', 'style'=>'display:inline']) }}
					<button class="btn btn-default btn-delete btn-xs" type="button"><i class="fa fa-times"></i></button>
				{{ Form::close() }}
			@endla_access
		</div>
	</div>

	<ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
		<li class=""><a href="{{ url('/departments') }}" data-toggle="tooltip" data-placement="right" title="Back to Departments"><i class="fa fa-chevron-left"></i></a></li>
		<li class="active"><a role="tab" data-toggle="tab" class="active" href="#tab-general-info" data-target="#tab-info"><i class="fa fa-bars"></i> General Info</a></li>
	 </ul>

	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active fade in" id="tab-info">
			<div class="tab-content">
				<div class="panel infolist">
					<div class="panel-default panel-heading">
						<h4>General Info</h4>
					</div>
					<div class="panel-body">
						@la_display($module, 'name')
						@la_display($module, 'tags')
						@la_display($module, 'template_name')
						@la_display($module, 'color')
					</div>
				</div>
			</div>
		</div>
		 
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
@endsection
@push('scripts')
<script>
$(document).ready(function() {
	 $('.btn-delete').on('click', function(e) { 
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