<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        @if (! Auth::guest())
            
        @endif

        <!-- search form (Optional) -->
        @if(LAConfigs::getByKey('sidebar_search'))
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
	                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
        </form>
        @endif
        <!-- /.search form -->
<?php
           
            $userId = Auth::user()->context_id;
            $menuItems = Dwij\Laraadmin\Models\Menu::where("parent", 0)->orderBy('hierarchy', 'asc')->get();
            $userId = Auth::user()->context_id;
            $memberCount = DB::table('employees')->select('id')
                            ->whereNull('deleted_at')
                            ->whereNull('date_left')
                            ->where('manager', '=', $userId)
                            ->count();   
            $teams = array();
            // Getting team members ids
            $values = DB::select("select id from (select * from employees order by manager, id) employees,
            (select @pv :=". Auth::user()->context_id.") initialisation where find_in_set(manager, @pv) > 0 and @pv := concat(@pv, ',', id)");
                foreach ($values as $val) {
                    array_push($teams, $val->id);
                }
            
            array_push($teams, $userId);
            $evaluationCount = 0;
            
            
            $documentCount = DB::table('Performance_Appraisals_details')->select('id')
                            ->whereNull('deleted_at')
                            ->whereIn('manager_id', $teams)
                            ->count();    
            
            ?>
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header"></li>
            <!-- Optionally, you can add icons to the links -->
            <?php  $routeName = request()->route()->getName(); ?>
              @if ( $routeName != '')
              	<li><a href="{{ url('/dashboard') }}"><i class='fa fa-home'></i> <span>Dashboard</span></a></li>
              @elseif  ( $routeName == '')
               	<li class='active'><a href="{{ url('/dashboard') }}"><i class='fa fa-home'></i> <span>Dashboard</span></a></li>
              @endif
              
            
             
            @foreach ($menuItems as $menu)
                @if($menu->type == "module")
                    <?php  $temp_module_obj = Module::get($menu->name); ?>
                    @la_access($temp_module_obj->id)
                        @if((($menu->name != 'Employees'  ||   $menu->name != 'My Team Documents') && $memberCount > 0 ) || Entrust::hasRole("SUPER_ADMIN") || Entrust::hasRole("HR_MANAGER"))
    						@if(isset($module->id) && $module->name == $menu->name)
                            	<?php echo LAHelper::print_menu($menu ,true); ?>
    						@else
    							<?php echo LAHelper::print_menu($menu); ?>
    						@endif
    					@endif	
				    @endla_access
                @else
                     @if ( ( $routeName == 'performance_appraisals.index' || $routeName == 'performance_appraisals.create' ||  $routeName == 'performance_appraisals.edit'  ||  $routeName == 'performance_appraisals.show') )
                     	<?php echo LAHelper::print_menu($menu, true); ?>
                     @elseif(   $memberCount > 0 || $documentCount > 0 || Entrust::hasRole('SUPER_ADMIN'))
                     	<?php echo  LAHelper::print_menu($menu);?>
                      @endif
                @endif
            @endforeach
            @if(  Entrust::hasRole("SUPER_ADMIN") || Entrust::hasRole("HR_MANAGER"))
                <li class="treeview <?php echo ($routeName == 'reports.index' || $routeName == 'reports.ratings.index' ) ? 'active':'' ?>">
                	<a href="#"><i class="fa fa-bar-chart-o"></i><span>Reports</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                	<ul class="treeview-menu">
                	 	<li class="<?php echo ($routeName == 'reports.index') ? 'active':'' ?>"><a href="{{ url('/reports') }}"><i class='fa fa-file-word-o'></i> <span>Overall Documents</span></a></li>
                		<!--<li class="<?php //echo  ($routeName == 'reports.ratings.index') ? 'active':'' ?>"><a href="{{ url('/reports/ratings') }}"><i class='fa fa fa-star'></i> <span>Employee Ratings</span></a></li>-->
                	</ul>
                </li>
           @endif 	
           
            <!-- LAMenus -->
            
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
