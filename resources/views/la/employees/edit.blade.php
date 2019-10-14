@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url('/employees') }}">Employees</a> :
@endsection
@section("contentheader_description", $employee->$view_col)
@section("section", "Employees")
@section("section_url", url('/employees'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Employee Edit : ".$employee->$view_col)

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

<div class="box">
	<div class="box-header">
		
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">  
				{!! Form::model($employee, ['route' => ['employees.update', $employee->id ], 'method'=>'PUT', 'id' => 'employee-edit-form']) !!}
				 
					<div class="form-group">
						<label for="name">Name : <span class="error">*</span></label>
						<input class="form-control" placeholder="Enter Name" data-rule-minlength="5" data-rule-maxlength="250" maxlength="250" required="1" name="name" type="text" value="{{$employee->name}}" aria-required="true">
					</div>
					<div class="form-group">
						<label for="designation">Designation : <span class="error">*</span></label>
						<input class="form-control" placeholder="Enter Designation" data-rule-maxlength="50" maxlength="50" required="1" name="designation" type="text" value="{{$employee->designation}}" aria-required="true">
					</div>
					<div class="mandatory-label">
							@la_input($module, 'gender')
					</div>
					
					<div class="form-group">
						<label for="mobile">Mobile : <span class="error">*</span></label>
						<input class="form-control" placeholder="Enter Mobile" data-rule-minlength="10" data-rule-maxlength="10" required="1" maxlength="10" name="mobile" type="text" value="{{$employee->mobile}}" aria-required="true" onkeypress="return isNumberKey(event)">
					</div>
					<div class="mandatory-label">
							@la_input($module, 'email')
					</div>
				
					<div class="form-group">
						<label for="dept">Department : <span class="error">*</span></label>
						<select class="form-control select2-hidden-accessible" required="1" data-placeholder="Enter Department" rel="select2" name="dept" tabindex="-1" aria-hidden="true" aria-required="true">
    						<option value=''>Select Department</option> 
							@foreach( $departments as $key => $value )
								@if($employee->dept == $key))
										<option value='{{ $key }}' selected>{{ $value }}</option>
									@else
										<option value='{{ $key }}'>{{ $value }}</option>
									@endif
							@endforeach
						 </select>
						 <label id="dept-error" class="error" for="dept" style="display: inline-block;"></label>
					</div>
					<div class="form-group">
						<label for="manager">Reporting To : <span class="error">*</span></label>
						<select class="form-control select2-hidden-accessible" required="1" data-placeholder="Enter Reporting To" rel="select2" name="manager" tabindex="-1" aria-hidden="true">
							<option value=''>Reporting To</option>
							@foreach($members as $key => $value)
									@if($employee->manager == $key))
										<option value="{{$key}}" selected>{{ $value }}</option>
									@else
										<option value="{{$key}}">{{ $value }}</option>
									@endif
							@endforeach
						</select>
						<label id="manager-error" class="error" for="manager"></label>
					</div>
					
					<div class="form-group">
						<label for="date_hire">Joining Date : <span class="error">*</span></label>
						<div class="input-group date"  id="dateHire">
							<input class="form-control" placeholder="Enter Joining Date" required="1"  autocomplete = "off"  name="date_hire" type="text" value="{{$doh}}" aria-required="true">
							<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
						</div>
						<label id="date_hire-error" class="error" for="date_hire"></label>
					</div>
					<div class="form-group">
						<label for="date_left">Resignation Date : </label>
						<div class="input-group date" id="dateLeft">
							<input class="form-control" placeholder="Enter Resignation Date" autocomplete = "off"  name="date_left" type="text" value="{{$dol}}">
							<span class="input-group-addon"><span class="fa fa-calendar"></span></span>
						</div>
					</div> 
					<div class="mandatory-label">
					 @la_input($module, 'corp_id')
					 </div> 
					  @if(Entrust::hasRole("SUPER_ADMIN") || Entrust::hasRole("HR_MANAGER"))
                    <div class="form-group">
						<label for="role">Role : <span class="error">*</span></label>
						<select class="form-control" required="1" data-placeholder="Select Role" rel="select2" name="role">
						 <option value=''>Select Role</option> 
							<?php $roles = App\Role::all(); ?>
							@foreach($roles as $role)
								@if($role->id != 1 || Entrust::hasRole("SUPER_ADMIN"))
									@if($user->hasRole($role->name))
										<option value="{{ $role->id }}" selected>{{ $role->name }}</option>
									@else
										<option value="{{ $role->id }}">{{ $role->name }}</option>
									@endif
								@endif
							@endforeach
						</select>
						<label id="role-error" class="error" for="role"></label>
					</div>
					@else 
					<?php $roles = App\Role::all(); ?>
							@foreach($roles as $role)
								 
									@if($user->hasRole($role->name))
									 <input type="hidden" name="role" value="{{ $role->id }}">
									 @endif
								 
							@endforeach
				  
					 @endif
					<br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} 
						@if($employee->id != Auth::user()->id)
						<button class="btn btn-default pull-right"><a href="{{ url('/employees') }}">Cancel</a></button>
						@else
						<button class="btn btn-default pull-right"><a href="{{ url('/employees/'.$employee->id) }}">Cancel</a></button>
						@endif
					</div>
				{!! Form::close() !!}
				
			</div>
		</div>
	</div>
</div>

@endsection

@push('scripts')
<script>
$(function () {
	$("#employee-edit-form").validate({
		
	});
});
 $(function () {
	 var minDate = $("input[name='date_hire']").val();
	 $('#dateHire, #dateLeft').datetimepicker({
			ignoreReadonly: true ,
			format: 'DD/MM/YYYY'
	 }); 
	 $("#dateHire").data("DateTimePicker").maxDate(moment());
	 $("#dateLeft").data("DateTimePicker").maxDate(moment());
	 $("#dateLeft").data("DateTimePicker").minDate(minDate);
	 
	 $("#dateHire").on("dp.change", function (e) {
	     $("#dateLeft").data("DateTimePicker").minDate(e.date);
	     $("input[name=date_left]").val('');
	 });
}); 
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
$('#employee-edit-form').validate({
    // ...
    messages: {
        mobile: "Please enter valid mobile number.",
        mobile2: "Please enter valid mobile number."
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
.mandatory-label .radio label:after {
content:"";
}
</style>
@endpush
