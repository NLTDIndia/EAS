@extends('la.layouts.app')

@section('htmlheader_title') Dashboard @endsection
@section('contentheader_title') Dashboard @endsection
@section('contentheader_description') @endsection

@section('main-content')
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
<!-- Main content -->
<div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                  @if($userId != 1)
                    <h3 class="box-title">My Performance Appraisal</h3>
                   @endif 
                   <div class="pull-right"> 
                  	 <?php echo $output ?>
                   </div>
                </div>

                <div class="box-body">
                     
                   <div class="row">
                   		<div class="col-md-12">
                   		
                   		<ul class="timeline">
                                <!-- timeline time label -->
                                 @foreach ($records as $record)  
                                <li class="time-label">
                                      <span class="bg-red"> &nbsp; &nbsp;  {{  $record->evaluation_period }}  &nbsp; &nbsp;
                                      </span>
                                </li>
                                <!-- /.timeline-label -->
                                <!-- timeline item -->
                                <li>
                                  <i class="fa fa-envelope bg-blue"></i>
                              
                                  <div class="timeline-item">
                                     <h3 class="timeline-header"> <?php //echo $record->status; echo "<br>".$evaluationStatus;exit;?>
                                      @if( ($record->status == 0 ) &&  ( $evaluationStatus != 'Closed' && $evaluationStatus != 'Completed' ))
                                       <a class="btn btn-primary btn-xs" href="{{url('/performance_appraisals/'.$record->id.'/edit') }}">Edit Document</a>
                                     @elseif( ($record->status == 0 ||   $record->status == 4) && ( $evaluationStatus != 'Closed'  && $evaluationStatus != ''  &&  $evaluationStatus != 'Completed'  && $evaluationStatus != 'Mid-Year-Revision' )) 
                                     	 <a class="btn btn-primary btn-xs" href="{{url('/performance_appraisals/'.$record->id.'/edit') }}">Edit Document</a>
                                     @elseif( ( $record->status == 2) && $evaluationStatus != '' && $evaluationStatus != 'Completed'  && $evaluationStatus == 'Final-Review' )  
                                     <a class="btn btn-primary btn-xs" href="{{url('/performance_appraisals/'.$record->id.'/edit') }}">Edit Document</a>
                                     @elseif( $record->status ==0 && $accessCount1 == 1)
                                      	 <a class="btn btn-primary btn-xs" href="{{url('/performance_appraisals/'.$record->id.'/edit') }}">Edit Document</a>
                                     @else
                                        <a class="btn btn-primary btn-xs" href="{{url('/performance_appraisals/'.$record->id) }}" >View Document</a>
                                    @endif    
                                   
									@php
										switch($record->status) {
											case 0:
                                                 $status = 'Goal setting is in progress';
                                                 break ;
                                             case 1:
                                                 $status = 'Goal settings is completed by Appraisee';
                                                 break ;
                                             case 2:
                                                 $status = 'Goal settings is completed by Appraiser';
                                                 break ;
                                             case 3:
                                                 $status = '';
                                                 break ;
                                             case 4:
                                                 $status = 'Mid Year Revision is completed by Appraiser';
                                                 break ;
                                             case 5:
                                                 $status = 'Self rating is completed by Appraisee';
                                                 break ;
                                             case 6:
                                                 $status = ' Final Review is in progress';
                                                 break ;
                                             case 7:
                                                 $status = 'Final Review is completed by Appraiser';
                                                 break ;       	      	 		  	
									    }  	
									@endphp
									<?php $start_date  =   date_format(date_create($record->start_date), "d-m-Y");
									      $end_date    =   date_format(date_create($record->end_date), "d-m-Y");?>
									<br><br>         
                                    <span class="label label-default">Period - <?php echo $start_date; ?> - <?php echo $end_date; ?></span>
                                    <span class="label label-default">Status - {{ $status }}</span> 
                                    <span class="label label-default">Department - {{ ucwords($record->name)}} </span>
                                    <span class="label label-default">Reporting To - {{ ucwords($record->managerName)}}</span>
                                     </h3>
                    				 <!-- <div class="timeline-body">
                    				
                                     </div>-->
                                    <div class="timeline-footer">
                                      <!-- <a class="btn btn-primary btn-xs">Read more</a>
                                      <a class="btn btn-danger btn-xs">Delete</a> -->
                                    </div>
                                  </div>
                                </li>
                               
                                 @endforeach   
                    
                  </ul>
                   			 
                   		</div>
           </div>
          <!-- Main row -->
                         
                    </div>
                </div>
            </div>
        </div>
   
  
@endsection

@push('styles')
<!-- Morris chart -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/morris/morris.css') }}">
<!-- jvectormap -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css') }}">
<!-- Date Picker -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/datepicker/datepicker3.css') }}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/daterangepicker/daterangepicker-bs3.css') }}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{ asset('la-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">
@endpush


@push('scripts')
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="{{ asset('la-assets/plugins/morris/morris.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('la-assets/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
<!-- jvectormap -->
<script src="{{ asset('la-assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('la-assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('la-assets/plugins/knob/jquery.knob.js') }}"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="{{ asset('la-assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- datepicker -->
<script src="{{ asset('la-assets/plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('la-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('la-assets/plugins/fastclick/fastclick.js') }}"></script>
<!-- dashboard -->
<script src="{{ asset('la-assets/js/pages/dashboard.js') }}"></script>
@endpush

@push('scripts')
<script>
/*
(function($) {
	$('body').pgNotification({
		style: 'circle',
		title: 'LaraAdmin',
		message: "Welcome to LaraAdmin...",
		position: "top-right",
		timeout: 0,
		type: "success",
		thumbnail: '<img width="40" height="40" style="display: inline-block;" src="{{ Gravatar::fallback(asset('la-assets/img/user2-160x160.jpg'))->get(Auth::user()->email, 'default') }}" data-src="assets/img/profiles/avatar.jpg" data-src-retina="assets/img/profiles/avatar2x.jpg" alt="">'
	}).show();
})(window.jQuery);*/
</script>
@endpush