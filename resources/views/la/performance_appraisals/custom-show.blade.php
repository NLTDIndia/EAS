@extends("la.layouts.app")

@section("contentheader_title")
	<a href="{{ url('/performance_appraisals') }}">Performance Appraisal</a> 
@endsection
 
@section("section", "Performance_Appraisals")
@section("section_url", url('/performance_appraisals'))
@section("sub_section", "View")

@section("htmlheader_title", "Performance_Appraisals View ")

@section("main-content")

<div id="page-content" class="profile2">
	<div class="bg-primary clearfix">
        	 	<div class="col-md-11">
        			<div class="row">
        				<div class="col-md-8">
            					 <label class="name" style="text-transform:capitalize"> <b>Employee Name : </b>  {{ $employeeName }}  </label>
            					 <label class="name" style="text-transform:capitalize"> Appraisal Period :  {{ $appraisalPeriod }} ( {{$startDate }} - {{ $endDate }} )</label>
            					 <div class="row stats"></div>
            					 <p class="desc"> </p>
            			</div>
        				<div class="col-md-4">
        					 <label class="name" style="text-transform:capitalize"> Department :   {{ $deptName }} </label> 
        					 <label class="name" style="text-transform:capitalize"> Manager : </h5> {{ $managerName }} </label>
        					<!--<img class="profile-image" src="{{ asset('la-assets/img/avatar5.png') }}" alt="">-->
        					<!-- <div class="profile-icon text-primary"><i class="fa {{ $module->fa_icon }}"></i></div>-->
        				</div>
        			</div>
        		</div>
		<div class="col-md-1"></div> 
		<div class="col-md-1 actions">
			@la_access("Performance_Appraisals", "edit")
			@if ($employeeId == Auth::user()->context_id && $currentStatus < 4 )
			  <!-- <a href="{{ url('/performance_appraisals/'.$performance_appraisal->id.'/edit') }}" class="btn btn-xs btn-edit btn-default"><i class="fa fa-pencil"></i></a><br> -->
			@endif
			@if ($employeeId != Auth::user()->context_id && $currentStatus > 7 )
			  <!-- <a href="{{ url('/performance_appraisals/'.$performance_appraisal->id.'/edit') }}" class="btn btn-xs btn-edit btn-default"><i class="fa fa-pencil"></i></a><br> -->
			@endif
			
			@endla_access
			
			@la_access("Performance_Appraisals", "delete") 
			 @if( $currentStatus != 7 )
				{{ Form::open(['route' => ['performance_appraisals.destroy', $performance_appraisal->id], 'method' => 'delete', 'style'=>'display:inline']) }}
					<!-- <button class="btn btn-default btn-delete btn-xs" type="submit"><i class="fa fa-times"></i></button> -->
				{{ Form::close() }}
			@endif	
			@endla_access
		</div>
	</div>
    <?php $i = 0; $j=0; $k=0;?>
    <ul data-toggle="ajax-tab" class="nav nav-tabs profile" role="tablist">
    		<!-- <li class=""><a href="{{ url('/performance_appraisals') }}" data-toggle="tooltip" data-placement="right" title="Back to Performance Appraisals"><i class="fa fa-chevron-left"></i></a></li> -->
    		 @if(trim($performance_appraisal->section_1_title) != '')
    		 	<li class="active"><a role="tab" data-toggle="tab" class="active" href="#tab-goals-jobs" data-target="#tab-goals-jobs"><i class="fa fa-bars"></i>{{$performance_appraisal->section_1_title}}</a></li>
    		 @endif
    		 @if(trim($performance_appraisal->section_2_title) != '')
    			<li class=""><a role="tab" data-toggle="tab" href="#tab-core-competencies" data-target="#tab-core-competencies"><i class="fa fa-clock-o"></i>{{$performance_appraisal->section_2_title}}</a></li>
    		 @endif
    	  	 @if(trim($performance_appraisal->section_3_title) != '')
    			<li class=""><a role="tab" data-toggle="tab" href="#tab-job-competencies" data-target="#tab-job-competencies"><i class="fa fa-clock-o"></i>{{$performance_appraisal->section_3_title}}</a></li>
    		 @endif
    		 @if(trim($performance_appraisal->section_4_title) != ''  && ($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed'))
    			<li class=""><a role="tab" data-toggle="tab" href="#tab-overall-rating" data-target="#tab-overall-rating"><i class="fa fa-clock-o"></i>{{$performance_appraisal->section_4_title}}</a></li>
    		 @endif	
    </ul>
	 <div class="tab-content">
		<div role="tabpanel" class="tab-pane active fade in" id="tab-goals-jobs">
		  	<div class="box box-solid">
                    	<div class="box-body">
                        	<h4 style="background-color:#48b0f7; font-size: 18px; text-align: center; padding: 7px 10px; margin-top: 0;border-radius: 4px;color:#fff">{{$performance_appraisal->section_1_title}}</h4>
                            <div class="media">
                           		<div class="media-body">
                            		<div class="clearfix">
                                    	<p style="padding-left:13px;">
                                    	 {{$performance_appraisal->section_1_description}}</p>                    
                                    </div>
                                    
                                </div>
                            </div>
                           <?php $j=0 ; 
                                    for ($i= 1; $i <= 10; $i++) {  
                                        if(( ($performance_appraisal->{'goal_'.$i}  != 'N/A' && $performance_appraisal->{'goal_'.$i}  != '') &&  $performance_appraisal->{'manager_only_'.$i} == "No" ) || ( trim($performance_appraisal->{'manager_only_'.$i} ) == "Yes" && $memberCount > 0 )) {
                           ?>
                           <div class="col-md-10">
                               <div class="box box-primary">
                               		<div class="box-header with-border">
                                    	<h3 class="box-title">Goal {{ ++$j }}</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="bg-light" style="background-color:#f7f7f7;padding: 10px; margin-bottom: 10px;border-radius: 5px;">
                        					<div class="row">
                        					 	<div class="form-group">
                            						<div class="col-md-3"><label for="goal_1"><b>Goal : </b></label></div>
                            						<div class="col-md-9"><?php echo $performance_appraisal->{'goal_'.$i} ; ?></div>
                        						</div>
                        					</div> 
                        					<div class="row">
                            					 <div class="form-group">
                            						<div class="col-md-3"><label for="goal_1"><b>Objective : </b></label></div>
                            						<div class="col-md-9"><?php echo $performance_appraisal->{'objective_'.$i} ; ?></div>
                    							</div>
                            				</div>
                        				 
                        					<div class="row">
                            					 <div class="form-group">
                            						<div class="col-md-3"><label for="goal_1"><b>Weightage : </b></label></div>
                            						<div class="col-md-9"><?php echo $performance_appraisal->{'weightage_'.$i} ; ?> %</div>
                            					</div>
                        					</div>      
                        					<div class="row">
                            					 <div class="form-group">
                            						<div class="col-md-3"><label for="goal_1"><b>Measurement : </b></label></div>
                            						<div class="col-md-9"><?php echo nl2br($performance_appraisal->{'measurement_'.$i}) ; ?></div>
                            					</div>
                        					</div> 
                        					
                        					 
											@if($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed')
                            					<div class="row">
                                					 <div class="form-group">
                                						<div class="col-md-3"><label for="goal_1"><b>Comments by Appraisee : </b></label></div>
                                						<div class="col-md-9"><?php echo nl2br($performance_appraisal->{'comments_by_appraisee_'.$i}) ; ?></div>
                                					</div>
                            					</div> 
                            					<div class="row">
                                					 <div class="form-group">
                                						<div class="col-md-3"><label for="goal_1"><b>Rating By Appraisee  : </b></label></div>
                                						<div class="col-md-9"><?php echo $performance_appraisal->{'rating_by_appraisee_'.$i} ; ?></div>
                                					</div>
                            					</div> 
                            					
                            					 @if( ($employeeId != Auth::user()->context_id && ($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed'))  || ( $evaluationStatus == 'Completed'  && $currentStatus == 7 )) 
                            					 	<div class="row">
                                    					 <div class="form-group">
                                    						<div class="col-md-3"><label for="goal_1"><b>Comments by Appraiser : </b></label></div>
                                    						<div class="col-md-9"><?php echo nl2br($performance_appraisal->{'comments_by_appraiser_'.$i}) ; ?></div>
                                    					</div>
                                					</div> 
                                					<div class="row">
                                    					 <div class="form-group">
                                    						<div class="col-md-3"><label for="goal_1"><b>Rating By Appraiser  : </b></label></div>
                                    						<div class="col-md-9"><?php echo $performance_appraisal->{'rating_by_appraiser_'.$i} ; ?></div>
                                    					</div>
                                					</div> 
                                				@endif	
                                			  @endif	  
                        				 
                        				</div>
                        			</div><!-- /.box-body -->
                        	   </div>
                          </div>	
                          <?php 
                                        }
                                    }
                           ?>
                         
                       
      				</div>
			 </div>
		</div> 	<!--  End  of tabpanel -->
	
		<div role="tabpanel" class="tab-pane fade in" id="tab-core-competencies">
		  	<div class="box box-solid">
                    	<div class="box-body">
                        	<h4 style="background-color:#48b0f7; font-size: 18px; text-align: center; padding: 7px 10px; margin-top: 0;border-radius: 4px;color:#fff">{{$performance_appraisal->section_2_title}}</h4>
                            <div class="media">
                           		<div class="media-body">
                            		<div class="clearfix">
                                    	<p style="padding-left:13px;">
                                    	 {{$performance_appraisal->section_2_description}}</p>                    
                                    </div>
                                    
                                </div>
                            </div>
                           <?php $j=0 ; 
                                    for ($i= 11; $i <= 17; $i++) {  
                                        if(( ($performance_appraisal->{'goal_'.$i}  != 'N/A' && $performance_appraisal->{'goal_'.$i}  != '') &&  $performance_appraisal->{'manager_only_'.$i} == "No" ) || ( trim($performance_appraisal->{'manager_only_'.$i} ) == "Yes" && $memberCount > 0 )) {
                           ?>
                           <div class="col-md-10">
                               <div class="box box-primary">
                               		<div class="box-header with-border">
                                    	<h3 class="box-title">Goal {{ ++$j }}</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="bg-light" style="background-color:#f7f7f7;padding: 10px; margin-bottom: 10px;border-radius: 5px;">
                        					<div class="row">
                        					 	<div class="form-group">
                            						<div class="col-md-3"><label for="goal_1"><b>Goal : </b></label></div>
                            						<div class="col-md-9"><?php echo $performance_appraisal->{'goal_'.$i} ; ?></div>
                        						</div>
                        					</div> 
                        					<div class="row">
                            					 <div class="form-group">
                            						<div class="col-md-3"><label for="goal_1"><b>Objective : </b></label></div>
                            						<div class="col-md-9"><?php echo $performance_appraisal->{'objective_'.$i} ; ?></div>
                    							</div>
                            				</div>
                        				 
                        					<div class="row">
                            					 <div class="form-group">
                            						<div class="col-md-3"><label for="goal_1"><b>Weightage : </b></label></div>
                            						<div class="col-md-9"><?php echo $performance_appraisal->{'weightage_'.$i} ; ?></div>
                            					</div>
                        					</div>      
                        					
                        					
                        					 
											@if($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed')
                            					<div class="row">
                                					 <div class="form-group">
                                						<div class="col-md-3"><label for="goal_1"><b>Comments by Appraisee : </b></label></div>
                                						<div class="col-md-9"><?php echo nl2br($performance_appraisal->{'comments_by_appraisee_'.$i}) ; ?></div>
                                					</div>
                            					</div> 
                            					<div class="row">
                                					 <div class="form-group">
                                						<div class="col-md-3"><label for="goal_1"><b>Rating By Appraisee  : </b></label></div>
                                						<div class="col-md-9"><?php echo $performance_appraisal->{'rating_by_appraisee_'.$i} ; ?></div>
                                					</div>
                            					</div> 
                            					
                            					 @if( ($employeeId != Auth::user()->context_id && ($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed'))  || ( $evaluationStatus == 'Completed'  && $currentStatus == 7 )) 
                            					 	<div class="row">
                                    					 <div class="form-group">
                                    						<div class="col-md-3"><label for="goal_1"><b>Comments by Appraiser : </b></label></div>
                                    						<div class="col-md-9"><?php echo nl2br($performance_appraisal->{'comments_by_appraiser_'.$i}) ; ?></div>
                                    					</div>
                                					</div> 
                                					<div class="row">
                                    					 <div class="form-group">
                                    						<div class="col-md-3"><label for="goal_1"><b>Rating By Appraiser  : </b></label></div>
                                    						<div class="col-md-9"><?php echo $performance_appraisal->{'rating_by_appraiser_'.$i} ; ?></div>
                                    					</div>
                                					</div> 
                                				@endif	
                                			  @endif	  
                        				 
                        				</div>
                        			</div><!-- /.box-body -->
                        	   </div>
                          </div>	
                          <?php 
                                        }
                                    }
                           ?>
                             
                       
      				</div>
			 </div>
		</div> 	<!--  End  of tabpanel -->
	
		<div role="tabpanel" class="tab-pane fade in" id="tab-job-competencies">
		  	<div class="box box-solid">
                    	<div class="box-body">
                        	<h4 style="background-color:#48b0f7; font-size: 18px; text-align: center; padding: 7px 10px; margin-top: 0;border-radius: 4px;color:#fff">{{$performance_appraisal->section_3_title}}</h4>
                            <div class="media">
                           		<div class="media-body">
                            		<div class="clearfix">
                                    	<p style="padding-left:13px;">
                                    	 {{$performance_appraisal->section_3_description}}</p>                    
                                    </div>
                                    
                                </div>
                            </div>
                           <?php $j=0 ; 
                                    for ($i= 18; $i <= 20; $i++) {  
                                        if(( ($performance_appraisal->{'goal_'.$i}  != 'N/A' && $performance_appraisal->{'goal_'.$i}  != '') &&  $performance_appraisal->{'manager_only_'.$i} == "No" ) || ( trim($performance_appraisal->{'manager_only_'.$i} ) == "Yes" && $memberCount > 0 )) {
                           ?>
                           <div class="col-md-10">
                               <div class="box box-primary">
                               		<div class="box-header with-border">
                                    	<h3 class="box-title">Goal {{ ++$j }}</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="bg-light" style="background-color:#f7f7f7;padding: 10px; margin-bottom: 10px;border-radius: 5px;">
                        					<div class="row">
                        					 	<div class="form-group">
                            						<div class="col-md-3"><label for="goal_1"><b>Goal : </b></label></div>
                            						<div class="col-md-9"><?php echo $performance_appraisal->{'goal_'.$i} ; ?></div>
                        						</div>
                        					</div> 
                        					<div class="row">
                            					 <div class="form-group">
                            						<div class="col-md-3"><label for="goal_1"><b>Objective : </b></label></div>
                            						<div class="col-md-9"><?php echo $performance_appraisal->{'objective_'.$i} ; ?></div>
                    							</div>
                            				</div>
                        				 
                        					<div class="row">
                            					 <div class="form-group">
                            						<div class="col-md-3"><label for="goal_1"><b>Weightage : </b></label></div>
                            						<div class="col-md-9"><?php echo $performance_appraisal->{'weightage_'.$i} ; ?></div>
                            					</div>
                        					</div>      
                        					  
											@if($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed')
                            					<div class="row">
                                					 <div class="form-group">
                                						<div class="col-md-3"><label for="goal_1"><b>Comments by Appraisee : </b></label></div>
                                						<div class="col-md-9"><?php echo nl2br($performance_appraisal->{'comments_by_appraisee_'.$i}) ; ?></div>
                                					</div>
                            					</div> 
                            					<div class="row">
                                					 <div class="form-group">
                                						<div class="col-md-3"><label for="goal_1"><b>Rating By Appraisee  : </b></label></div>
                                						<div class="col-md-9"><?php echo $performance_appraisal->{'rating_by_appraisee_'.$i} ; ?></div>
                                					</div>
                            					</div> 
                            					
                            					 @if( ($employeeId != Auth::user()->context_id && ($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed'))  || ( $evaluationStatus == 'Completed'  && $currentStatus == 7 )) 
                            					 	<div class="row">
                                    					 <div class="form-group">
                                    						<div class="col-md-3"><label for="goal_1"><b>Comments by Appraiser : </b></label></div>
                                    						<div class="col-md-9"><?php echo nl2br($performance_appraisal->{'comments_by_appraiser_'.$i}) ; ?></div>
                                    					</div>
                                					</div> 
                                					<div class="row">
                                    					 <div class="form-group">
                                    						<div class="col-md-3"><label for="goal_1"><b>Rating By Appraiser  : </b></label></div>
                                    						<div class="col-md-9"><?php echo $performance_appraisal->{'rating_by_appraiser_'.$i} ; ?></div>
                                    					</div>
                                					</div> 
                                				@endif	
                                			  @endif	  
                        				 
                        				</div>
                        			</div><!-- /.box-body -->
                        	   </div>
                          </div>	
                          <?php 
                                        }
                                    }
                           ?>
                             
                       
      				</div>
			 </div>
		</div> 	<!--  End  of tabpanel -->
	
		
	<!--  Start of Overall Rating -->	
    <div role="tabpanel" class="tab-pane fade in p20 bg-white" id="tab-overall-rating">
    	<div class="box box-solid">
    		<div class="box-body">
				<h4 style="background-color:#48b0f7; font-size: 18px; text-align: center; padding: 7px 10px; margin-top: 0;border-radius: 4px;color:#fff">{{$performance_appraisal->section_4_title}}</h4>
                <div class="media">
                	<div class="media-body">
                		<div class="clearfix">
                        	<p style="padding-left:13px;">
                        	{{--$performance_appraisal->section_4_description--}}
                         </div>
                    </div>
                </div>
                <div class="col-md-10">
                   <div class="box box-primary">
                   		<div class="box-header with-border"></div><!-- /.box-header -->
                     	@if($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed')
                            <div class="box-body">
                                <div class="bg-light" style="background-color:#f7f7f7;padding: 10px; margin-bottom: 10px;border-radius: 5px;">
                            		<div class="row">
                             			<div class="form-group">
                            				<div class="col-md-3"><label for="goal_1"><b>{{$performance_appraisal->section_1_title}}</b></label></div>
                            		  	</div>
                            		  	
                            		</div> 
                            	</div>		
                            	
                            	<table class="table table-bordered">
                                    <tbody>
                                    	<tr>
                                      		<th style="width: 10px">#</th>
                                            <th style="width: 10px">Goal</th>
                                            <th style="width: 30px">Weightage %</th>
                                            <th style="width: 30px">Rating by Appraisee</th>
                                            @if( $employeeId != Auth::user()->context_id ||  ($evaluationStatus == 'Completed' && $currentStatus == 7  ))
                                            	<th style="width: 30px">Rating by Appraiser</th>
                                            @endif	
                                       </tr>
                                        <?php $y=0 ; 
                                        for ($i= 1; $i <= 10; $i++) {  
                                            if(( ($performance_appraisal->{'goal_'.$i}  != 'N/A' && $performance_appraisal->{'goal_'.$i}  != '') &&  $performance_appraisal->{'manager_only_'.$i} == "No" ) || ( trim($performance_appraisal->{'manager_only_'.$i} ) == "Yes" && $memberCount > 0 )) {
                                        ?>
                                        <tr>
                                            <td>{{++$y}}</td>
                                            <td><?php echo $performance_appraisal->{'goal_'.$i};?></td>
                                            <td><?php echo $performance_appraisal->{'weightage_'.$i};?></td>
                                            <td><?php echo $performance_appraisal->{'rating_by_appraisee_'.$i};?></td>
                                            @if( $employeeId != Auth::user()->context_id ||  ($evaluationStatus == 'Completed' && $currentStatus == 7  ))
                                            	<td><?php echo $performance_appraisal->{'rating_by_appraiser_'.$i};?></td>
                                            @endif
                                        </tr>
                                        <?php } ?>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                            	</table>
                           </div><!-- /.box-body -->
                       @endif
	   				</div>
  				</div>	
  				<div class="col-md-10">
                   <div class="box box-primary">
                   		<div class="box-header with-border"></div><!-- /.box-header -->
                     	@if($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed')
                            <div class="box-body">
                                <div class="bg-light" style="background-color:#f7f7f7;padding: 10px; margin-bottom: 10px;border-radius: 5px;">
                            		<div class="row">
                             			<div class="form-group">
                            				<div class="col-md-3"><label for="goal_1"><b>{{$performance_appraisal->section_2_title}}</b></label></div>
                            		  	</div>
                            		  	
                            		</div> 
                            	</div>		
                            	
                            	<table class="table table-bordered">
                                    <tbody>
                                    	<tr>
                                      		<th style="width: 10px">#</th>
                                            <th style="width: 10px">Goal</th>
                                            <th style="width: 30px">Weightage %</th>
                                            <th style="width: 30px">Rating by Appraisee</th>
                                            @if( $employeeId != Auth::user()->context_id ||  ($evaluationStatus == 'Completed' && $currentStatus == 7  ))
                                            	<th style="width: 30px">Rating by Appraiser</th>
                                            @endif	
                                       </tr>
                                        <?php $y=0 ; 
                                        for ($i= 11; $i <= 17; $i++) {  
                                            if(( ($performance_appraisal->{'goal_'.$i}  != 'N/A' && $performance_appraisal->{'goal_'.$i}  != '') &&  $performance_appraisal->{'manager_only_'.$i} == "No" ) || ( trim($performance_appraisal->{'manager_only_'.$i} ) == "Yes" && $memberCount > 0 )) {
                                        ?>
                                        <tr>
                                            <td>{{++$y}}</td>
                                            <td><?php echo $performance_appraisal->{'goal_'.$i};?></td>
                                            <td><?php echo $performance_appraisal->{'weightage_'.$i};?></td>
                                            <td><?php echo $performance_appraisal->{'rating_by_appraisee_'.$i};?></td>
                                            @if( $employeeId != Auth::user()->context_id ||  ($evaluationStatus == 'Completed' && $currentStatus == 7  ))
                                            	<td><?php echo $performance_appraisal->{'rating_by_appraiser_'.$i};?></td>
                                            @endif
                                        </tr>
                                        <?php } ?>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                            	</table>
                           </div><!-- /.box-body -->
                       @endif
	   				</div>
  				</div>	
  				<div class="col-md-10">
                   <div class="box box-primary">
                   		<div class="box-header with-border"></div><!-- /.box-header -->
                     	@if($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed')
                            <div class="box-body">
                                <div class="bg-light" style="background-color:#f7f7f7;padding: 10px; margin-bottom: 10px;border-radius: 5px;">
                            		<div class="row">
                             			<div class="form-group">
                            				<div class="col-md-3"><label for="goal_1"><b>{{$performance_appraisal->section_3_title}}</b></label></div>
                            		  	</div>
                            		  	
                            		</div> 
                            	</div>		
                            	<table class="table table-bordered">
                                    <tbody>
                                    	<tr>
                                      		<th style="width: 10px">#</th>
                                            <th style="width: 10px">Goal</th>
                                            <th style="width: 30px">Weightage %</th>
                                            <th style="width: 30px">Rating by Appraisee</th>
                                            @if( $employeeId != Auth::user()->context_id ||  ($evaluationStatus == 'Completed' && $currentStatus == 7  ))
                                            	<th style="width: 30px">Rating by Appraiser</th>
                                            @endif	
                                       </tr>
                                        <?php $y=0 ; 
                                        for ($i= 17; $i <= 20; $i++) {  
                                            if(( ($performance_appraisal->{'goal_'.$i}  != 'N/A' && $performance_appraisal->{'goal_'.$i}  != '') &&  $performance_appraisal->{'manager_only_'.$i} == "No" ) || ( trim($performance_appraisal->{'manager_only_'.$i} ) == "Yes" && $memberCount > 0 )) {
                                        ?>
                                        <tr>
                                            <td>{{++$y}}</td>
                                            <td><?php echo $performance_appraisal->{'goal_'.$i};?></td>
                                            <td><?php echo $performance_appraisal->{'weightage_'.$i};?></td>
                                            <td><?php echo $performance_appraisal->{'rating_by_appraisee_'.$i};?></td>
                                            @if( $employeeId != Auth::user()->context_id ||  ($evaluationStatus == 'Completed' && $currentStatus == 7  ))
                                            	<td><?php echo $performance_appraisal->{'rating_by_appraiser_'.$i};?></td>
                                            @endif
                                        </tr>
                                        <?php } ?>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                            	</table>
                            	
                           </div><!-- /.box-body -->
                       @endif
	   				</div>
  				</div>	
  				<div class="col-md-10">
                   <div class="box box-primary">
                   		<div class="box-header with-border"></div><!-- /.box-header -->
                     	@if($evaluationStatus == 'Final-Review' || $evaluationStatus == 'Completed')
                            <div class="box-body">
                               <!-- <div class="bg-light" style="background-color:#f7f7f7;padding: 10px; margin-bottom: 10px;border-radius: 5px;">
                            		<div class="row">
                             			<div class="form-group">
                            				<div class="col-md-3"><label for="goal_1"><b>Overall Comments & Rating</b></label></div>
                            		  	</div>
                            		</div> 
                            	</div>		-->
                            	<div class="row">
                                    <div class="form-group">
                                    	 <label for="overall_comments_by_appraiser">Overall Comments by Appraisee :</label>
                                    	 <textarea class="form-control valid" readonly ="true"  placeholder="Enter Overall Comments by Appraisee" required="1" cols="30" rows="3" name="overall_comments_by_appraisee" aria-required="true" aria-invalid="false"><?php echo nl2br($performance_appraisal->overall_comments_by_appraisee);?></textarea>
                                    </div>
                                </div>
                                <div class="row">     
                                    <div class="form-group">
                                    	<label for="overall_rating_by_appraisee">Overall Rating By Appraisee :</label>
                                    	<input id="overall_rating_by_appraisee" class="form-control valid" readonly ="true" placeholder="Enter Overall Rating By Appraisee" data-rule-minlength="1" required="1" name="overall_rating_by_appraisee" type="text" value="<?php  echo $performance_appraisal->overall_rating_by_appraisee;?>" aria-required="true" aria-invalid="false">
                                     </div>
                                </div>     
                                @if($employeeId != Auth::user()->context_id &&  ($evaluationStatus == 'Completed' ||  $evaluationStatus == 'Final-Review' ) || $currentStatus == 7 )
                                <div class="row">
                                    <div class="form-group">
                                    	 <label for="overall_comments_by_appraiser">Overall Comments by Appraiser :</label>
                                    	 <textarea class="form-control valid" readonly ="true"  placeholder="Enter Overall Comments by Appraiser" required="1" cols="30" rows="3" name="overall_comments_by_appraiser" aria-required="true" aria-invalid="false"><?php echo nl2br($performance_appraisal->overall_comments_by_appraiser);?></textarea>
                                    </div>
                                </div>
                                <div class="row">     
                                    <div class="form-group">
                                    	<label for="overall_rating_by_appraiser">Overall Rating By Appraiser :</label>
                                    	<input id="overall_rating_by_appraiser" class="form-control valid" readonly ="true" placeholder="Enter Overall Rating By Appraiser" data-rule-minlength="1" required="1" name="overall_rating_by_appraiser" type="text" value="<?php  echo $performance_appraisal->overall_rating_by_appraiser;?>" aria-required="true" aria-invalid="false">
                                     </div>
                                </div>     
                                
                                @endif
                            	
                           </div><!-- /.box-body -->
                       @endif
	   				</div>
  				</div>	
  			</div>
		</div> 	
	</div><!--  End of Overall Rating -->
	
	 
	</div>
	</div>
 
@endsection
<style>
.fixed-side-content {
    position: fixed;
    top: 50%;
    right: 0px;
    background: #007bffc7;
    height: auto;
    padding: 1em 1em;
    z-index: 999;
}
.fixed-side-content ul{
	    padding:0px;
	  }
.fixed-side-content ul li{
	    list-style:none;
		color:#fff;
		font-size: 14px;
}
.fixed-side-content ul h5 {
    color: #fff;
}  
</style>