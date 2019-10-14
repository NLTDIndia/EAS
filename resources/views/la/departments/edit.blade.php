@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url('/departments') }}">Department</a> :
@endsection
@section("contentheader_description", $department->$view_col)
@section("section", "Departments")
@section("section_url", url('/departments'))
@section("sub_section", "Edit")

@section("htmlheader_title", "Departments Edit : ".$department->$view_col)

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
<div class="box">
	<div class="box-header">
		
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
			<span class="col-md-offset-9" style="padding-top:10px;color:red;"> **All fields are mandatory</span>
				{!! Form::model($department, ['route' => ['departments.update', $department->id ], 'method'=>'PUT', 'id' => 'department-edit-form']) !!}
					@la_input($module, 'name')
					<label for="name">Template :</label>
					<select class="form-control select2-hidden-accessible" required="1" data-placeholder="Enter Template Name" rel="select2" name="template_name" tabindex="-1" aria-hidden="true" aria-required="true">
						<option value="">Select</option>
						@foreach($templateItems as $key => $val)
						  @if($department->template_name == $val)
							<option selected='selected' value="{{$val}}">{{$key}}</option>
						  @else 
						  	<option value="{{$val}}">{{$key}}</option>
						  @endif	
						@endforeach
					</select>
					<label id="template_name-error" class="error" for="template_name"></label>
					{{--@la_form($module)
					@la_input($module, 'name')
					@la_input($module, 'tags')
					@la_input($module, 'template_name', $templateItems)
					@la_input($module, 'color')
					--}}
                    <br>
					<div class="form-group">
						{!! Form::submit( 'Update', ['class'=>'btn btn-success']) !!} <button class="btn btn-default pull-right"><a href="{{ url('/departments') }}">Cancel</a></button>
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
	$("#department-edit-form").validate({
		
	});
});
</script>
@endpush
