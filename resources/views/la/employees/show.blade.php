@extends('la.layouts.app')

@section('htmlheader_title')
	Employee View
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
	<div class="bg-success clearfix">
		<div class="col-md-4"><h4 class="name">{{$employee->name}}</h4></div>
		<div class="col-md-3"></div>
		<div class="col-md-4"></div>
		<div class="col-md-1 actions">
		@la_access("Employees", "edit")
				<a href="{{ url('/employees/'.$employee->id.'/edit') }}" class="btn btn-xs btn-edit btn-default"><i class="fa fa-pencil"></i></a>
		@endla_access
		     
			 @la_access("Employees", "delete")
				{{ Form::open(['route' => [ 'employees.destroy', $employee->id], 'method' => 'delete', 'style'=>'display:inline']) }}
					<button class="btn btn-default btn-delete btn-xs" type="button"><i class="fa fa-times"></i></button>
				{{ Form::close() }}
			@endla_access
		</div>
	</div>

	<ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
		<li class=""><a href="{{ url('/employees') }}" data-toggle="tooltip" data-placement="right" title=""><i class="fa fa-chevron-left"></i></a></li>
		<li class="active"><a role="tab" data-toggle="tab" class="active" href="#tab-info" data-target="#tab-info"><i class="fa fa-bars"></i> General Info</a></li>
		@if($employee->id == Auth::user()->id || Entrust::hasRole("SUPER_ADMIN"))
			<!-- <li class=""><a role="tab" data-toggle="tab" href="#tab-account-settings" data-target="#tab-account-settings"><i class="fa fa-key"></i> Account settings</a></li>-->
		@endif
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
						@la_display($module, 'designation')
						@la_display($module, 'gender')
						@la_display($module, 'mobile')
						@la_display($module, 'email')
						@la_display($module, 'dept', 'FALSE')
						@la_display($module, 'manager', 'FALSE')
						@la_display($module, 'corp_id')
						 
					  	
					  	@if($employee->date_hire != '')
					  		@la_display($module, 'date_hire') 
					  	@else 
					  	 	<div class="form-group"><label for="date_hire" class="col-md-2">Joining Date :</label><div class="col-md-10 fvalue">-</div></div> 
					  	@endif	
					  	
					@if($employee->id != Auth::user()->id)
					    @if($employee->date_left != '')
					  		@la_display($module, 'date_left') 
					  	@else 
					  	 	<div class="form-group"><label for="date_left" class="col-md-2">Resignation Date :</label><div class="col-md-10 fvalue">-</div></div> 
					  	@endif	
					   
					@endif       
					</div>
				</div>
			</div>
		</div>
		 		
		@if($employee->id == Auth::user()->id || Entrust::hasRole("SUPER_ADMIN"))
		<div role="tabpanel" class="tab-pane fade" id="tab-account-settings">
			<div class="tab-content">
				<form action="{{ url('/change_password/'.$employee->id) }}" id="password-reset-form" class="general-form dashed-row white" method="post" accept-charset="utf-8">
					{{ csrf_field() }}
					<div class="panel">
						<div class="panel-default panel-heading">
							<h4>Account settings</h4>
						</div>
						<div class="panel-body">
							@if (count($errors) > 0)
								<div class="alert alert-danger">
									<ul>
										@foreach ($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
								</div>
							@endif
							@if(Session::has('success_message'))
								<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
							@endif
							<div class="form-group">
								<label for="password" class=" col-md-2">Password</label>
								<div class=" col-md-10">
									<input type="password" name="password" value="" id="password" class="form-control" placeholder="Password" autocomplete="off" required="required" data-rule-minlength="6" data-msg-minlength="Please enter at least 6 characters.">
								</div>
							</div>
							<div class="form-group">
								<label for="password_confirmation" class=" col-md-2">Retype password</label>
								<div class=" col-md-10">
									<input type="password" name="password_confirmation" value="" id="password_confirmation" class="form-control" placeholder="Retype password" autocomplete="off" required="required" data-rule-equalto="#password" data-msg-equalto="Please enter the same value again.">
								</div>
							</div>
						</div>
						<div class="panel-footer">
							<button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> Change Password</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		@endif
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
$(function () {
	@if($employee->id == Auth::user()->id || Entrust::hasRole("SUPER_ADMIN"))
	$('#password-reset-form').validate({
		
	});
	@endif
});
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
