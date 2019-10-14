@extends("la.layouts.app")

@section("contentheader_title", "Reports")
@section("contentheader_description", "Performance Appraisals Reports")
@section("section", "Reports")
@section("sub_section", "Reports")
@section("htmlheader_title", "")

@section("headerElems")

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

<!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
<div class="box box-success">
	<!--<div class="box-header"></div>-->
	<div class="box-body"> 
	 <label>Appraisal Period rat </label>  {!! Form::select('evaluationItems', $evaluationItems, null ) !!} 
	  
    	<form id="frm-example" action="#" method="POST">
        	 
            	<table id="example1" class="table table-bordered">
            		<thead>
            		<tr class="success">
            		   
            		 	@foreach( $listing_cols_data_table as $col )
                		 	<th>{{ $module->fields[$col]['label'] or ucfirst($col) }}</th>
                			 
            			@endforeach
            			
            		</tr>
            		</thead>
            		<tbody></tbody>
    			</table>
    	</form>
	</div>
</div>

 
 
@endsection
@push('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('la-assets/plugins/datatables/datatables.min.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
var _token = $('input[name="_token"]').val();
$(document).ready(function (){   
	 
	 var table = 	$("#example1").DataTable({
		processing: true,
        serverSide: true,
        ajax: "{{ url('/reports_performance_appraisal_dt_ratings/'.$evaluationId)}}",
        'type': 'GET',
        "order": [[ 1, "asc" ]],
		language: {
			lengthMenu: "_MENU_",
			search: "_INPUT_",
			searchPlaceholder: "Search"
		},
		  columnDefs: [  {visible:false, targets:[0]}],
 });
  
//Handle drop down evalution period value change
$( "select[name='evaluationItems']" ).change(function() {
  // Check input( $( this ).val() ) for validity here
   var optionSelected = $("option:selected", this).value;
   var valueSelected = this.value;
   // table.ajax.reload();
   var link = "{{ url('/reports_performance_appraisal_dt_ratings/')}}";
  
   table.ajax.url(link+"/"+valueSelected).load();
});

 
 
}); 
</script>
@endpush
