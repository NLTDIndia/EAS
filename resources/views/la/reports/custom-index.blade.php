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
@if(  (Entrust::hasRole('SUPER_ADMIN') || Entrust::hasRole('HR_MANAGER'))) 
<div class="row">
	<!-- <div class="col-md-6"></div> -->
	<div class="col-md-12">
		<div class="box box-success">
            <div class="box-header with-border">
                	<span class="">No of Employees : </span>&nbsp;&nbsp;
                    <span> {{$noOfEmployees}}</span>
            </div>
            <div class="box-header with-border">
                	<span class="">No of Eligible Employees : </span>&nbsp;&nbsp;
                    <span> {{ $noOfEligibleEmployees }}</span>
            </div>
        </div>
	</div>
</div>
@endif
<!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
<div class="box box-success">
	<!--<div class="box-header"></div>-->
	<div class="box-body"> 
	 <label>Appraisal Period </label>  {!! Form::select('evaluationItems', $evaluationItems, null ) !!} 
	  
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
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.0/css/buttons.bootstrap.min.css">
@endpush

@push('scripts')
<script src="{{ asset('la-assets/plugins/datatables/datatables.min.js') }}"></script>
<script>
    var _token = $('input[name="_token"]').val();
    $(document).ready(function (){   
    	var table = 	$("#example1").DataTable({
    		processing: true,
            serverSide: false,
            lengthChange: false,
            ajax: "{{ url('/reports_performance_appraisal_dt_ajax/'.$evaluationId)}}",
            'type': 'GET',
            "order": [[ 1, "asc" ]],
    		language: {
    			lengthMenu: "_MENU_",
    			search: "_INPUT_",
    			searchPlaceholder: "Search"
    		},
    		  columnDefs: [  {visible:false, targets:[0]}],
    		  dom: 'Bfrtip',
    		  buttons: [
					{ extend: 'excelHtml5',filename: '<?php echo "EAS Documents -".date("d-m-Y"); ?>',exportOptions:{columns: [1, 2, 3, 4, 5, 6, 7]}},
					{ extend: 'pdfHtml5',orientation: 'landscape',
 					  pageSize: 'A4',filename: '<?php echo "EAS Documents -".date("d-m-Y"); ?>',
					  exportOptions:{columns: [1, 2, 3, 4, 5, 6, 7]}
					},
			  ],
    	});
    	 table.buttons().container()
         .appendTo( '#example_wrapper .col-sm-6:eq(0)' )
      
        //Handle drop down evalution period value change
        $( "select[name='evaluationItems']" ).change(function() {
          // Check input( $( this ).val() ) for validity here
           var optionSelected = $("option:selected", this).value;
           var valueSelected = this.value;
           var link = "{{ url('/reports_performance_appraisal_dt_ajax/')}}";
           table.ajax.url(link+"/"+valueSelected).load();
        }); 
   }); 
</script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.0/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.bootstrap.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.html5.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.print.min.js"></script>
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.colVis.min.js"></script>

@endpush
