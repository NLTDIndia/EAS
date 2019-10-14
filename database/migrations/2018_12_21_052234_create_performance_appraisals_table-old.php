<?php
/**
 * Migration genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Dwij\Laraadmin\Models\Module;

class CreatePerformanceAppraisalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Performance_appraisals", 'performance_appraisals', 'section_1_title', 'fa-file-word-o', [
            ["goal_6", "Goal", "Textarea", false, "", 0, 0, true],
            ["objective_6", "Objective", "Textarea", false, "", 0, 0, true],
            ["measurement_6", "Measurement", "Textarea", false, "", 0, 0, true],
            ["comments_by_appraisee_6", "Comments by Appraisee", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraisee_6", "Rating By Appraisee", "Radio", false, "", 0, 0, true, "@ratings"],
            ["comments_by_appraiser_6", "Comments by Appraiser", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraiser_6", "Rating By Appraiser", "Radio", false, "", 0, 0, false, "@ratings"],
            ["weightage_6", "Weightage", "TextField", false, "", 0, 100, true],
            ["manager_only_6", "Manager only", "Radio", false, "No", 0, 0, true, ["No","Yes"]],
            ["goal_7", "Goal", "Textarea", false, "", 0, 0, true],
            ["objective_7", "Objective", "Textarea", false, "", 0, 0, true],
            ["measurement_7", "Measurement", "Textarea", false, "", 0, 0, true],
            ["comments_by_appraisee_7", "Comments by Appraisee", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraisee_7", "Rating By Appraisee", "Radio", false, "", 0, 0, true, "@ratings"],
            ["comments_by_appraiser_7", "Comments by Appraiser", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraiser_7", "Rating By Appraiser", "Radio", false, "", 0, 0, false, "@ratings"],
            ["weightage_7", "Weightage", "TextField", false, "", 0, 100, true],
            ["manager_only_7", "Manager only", "Radio", false, "No", 0, 0, true, ["No","Yes"]],
            ["goal_8", "Goal", "Textarea", false, "", 0, 0, true],
            ["objective_8", "Objective", "Textarea", false, "", 0, 0, true],
            ["measurement_8", "Measurement", "Textarea", false, "", 0, 0, true],
            ["comments_by_appraisee_8", "Comments by Appraisee", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraisee_8", "Rating By Appraisee", "Radio", false, "", 0, 0, true, "@ratings"],
            ["comments_by_appraiser_8", "Comments by Appraiser", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraiser_8", "Rating By Appraiser", "Radio", false, "", 0, 0, false, "@ratings"],
            ["weightage_8", "Weightage", "TextField", false, "", 0, 100, true],
            ["manager_only_8", "Manager only", "Radio", false, "No", 0, 0, true, ["No","Yes"]],
            ["goal_9", "Goal", "Textarea", false, "", 0, 0, true],
            ["objective_9", "Objective", "Textarea", false, "", 0, 0, true],
            ["measurement_9", "Measurement", "Textarea", false, "", 0, 0, true],
            ["comments_by_appraisee_9", "Comments by Appraisee", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraisee_9", "Rating By Appraisee", "Radio", false, "", 0, 0, true, "@ratings"],
            ["comments_by_appraiser_9", "Comments by Appraiser", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraiser_9", "Rating By Appraiser", "Radio", false, "", 0, 0, false, "@ratings"],
            ["weightage_9", "Weightage", "TextField", false, "", 0, 100, true],
            ["manager_only_9", "Manager only", "Radio", false, "No", 0, 0, true, ["No","Yes"]],
            ["goal_10", "Goal", "Textarea", false, "", 0, 0, true],
            ["objective_10", "Objective", "Textarea", false, "", 0, 0, true],
            ["measurement_10", "Measurement", "Textarea", false, "", 0, 0, true],
            ["comments_by_appraisee_10", "Comments by Appraisee", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraisee_10", "Rating By Appraisee", "Radio", false, "", 0, 0, true, "@ratings"],
            ["comments_by_appraiser_10", "Comments by Appraiser", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraiser_10", "Rating By Appraiser", "Radio", false, "", 0, 0, false, "@ratings"],
            ["weightage_10", "Weightage", "TextField", false, "", 0, 100, true],
            ["manager_only_10", "Manager only", "Radio", false, "No", 0, 0, true, ["No","Yes"]],
            ["goal_11", "Goal", "Textarea", false, "", 0, 0, true],
            ["objective_11", "Objective", "Textarea", false, "", 0, 0, true],
            ["measurement_11", "Measurement", "Textarea", false, "", 0, 0, true],
            ["comments_by_appraisee_11", "Comments by Appraisee", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraisee_11", "Rating By Appraisee", "Radio", false, "", 0, 0, true, "@ratings"],
            ["comments_by_appraiser_11", "Comments by Appraiser", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraiser_11", "Rating By Appraiser", "Radio", false, "", 0, 0, false, "@ratings"],
            ["weightage_11", "Weightage", "TextField", false, "", 0, 100, true],
            ["manager_only_11", "Manager only", "Radio", false, "No", 0, 0, true, ["No","Yes"]],
            ["goal_12", "Goal", "Textarea", false, "", 0, 0, true],
            ["objective_12", "Objective", "Textarea", false, "", 0, 0, true],
            ["measurement_12", "Measurement", "Textarea", false, "", 0, 0, true],
            ["comments_by_appraisee_12", "Comments by Appraisee", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraisee_12", "Rating By Appraisee", "Radio", false, "", 0, 0, true, "@ratings"],
            ["comments_by_appraiser_12", "Comments by Appraiser", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraiser_12", "Rating By Appraiser", "Radio", false, "", 0, 0, false, "@ratings"],
            ["weightage_12", "Weightage", "TextField", false, "", 0, 100, true],
            ["manager_only_12", "Manager only", "Radio", false, "No", 0, 0, true, ["No","Yes"]],
            ["goal_13", "Goal", "Textarea", false, "", 0, 0, true],
            ["objective_13", "Objective", "Textarea", false, "", 0, 0, true],
            ["measurement_13", "Measurement", "Textarea", false, "", 0, 0, true],
            ["comments_by_appraisee_13", "Comments by Appraisee", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraisee_13", "Rating By Appraisee", "Radio", false, "", 0, 0, true, "@ratings"],
            ["comments_by_appraiser_13", "Comments by Appraiser", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraiser_13", "Rating By Appraiser", "Radio", false, "", 0, 0, false, "@ratings"],
            ["weightage_13", "Weightage", "TextField", false, "", 0, 100, true],
            ["manager_only_13", "Manager only", "Radio", false, "No", 0, 0, true, ["No","Yes"]],
            ["goal_14", "Goal", "Textarea", false, "", 0, 0, true],
            ["objective_14", "Objective", "Textarea", false, "", 0, 0, true],
            ["measurement_14", "Measurement", "Textarea", false, "", 0, 0, true],
            ["comments_by_appraisee_14", "Comments by Appraisee", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraisee_14", "Rating By Appraisee", "Radio", false, "", 0, 0, true, "@ratings"],
            ["comments_by_appraiser_14", "Comments by Appraiser", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraiser_14", "Rating By Appraiser", "Radio", false, "", 0, 0, false, "@ratings"],
            ["weightage_14", "Weightage", "TextField", false, "", 0, 100, true],
            ["manager_only_14", "Manager only", "Radio", false, "No", 0, 0, true, ["No","Yes"]],
            ["goal_15", "Goal", "Textarea", false, "", 0, 0, true],
            ["objective_15", "Objective", "Textarea", false, "", 0, 0, true],
            ["measurement_15", "Measurement", "Textarea", false, "", 0, 0, true],
            ["comments_by_appraisee_15", "Comments by Appraisee", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraisee_15", "Rating By Appraisee", "Radio", false, "", 0, 0, true, "@ratings"],
            ["comments_by_appraiser_15", "Comments by Appraiser", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraiser_15", "Rating By Appraiser", "Radio", false, "", 0, 0, false, "@ratings"],
            ["weightage_15", "Weightage", "TextField", false, "", 0, 100, true],
            ["manager_only_15", "Manager only", "Radio", false, "No", 0, 0, true, ["No","Yes"]],
            ["goal_16", "Goal", "Textarea", false, "", 0, 0, true],
            ["objective_16", "Objective", "Textarea", false, "", 0, 0, true],
            ["measurement_16", "Measurement", "Textarea", false, "", 0, 0, true],
            ["comments_by_appraisee_16", "Comments by Appraisee", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraisee_16", "Rating By Appraisee", "Radio", false, "", 0, 0, true, "@ratings"],
            ["comments_by_appraiser_16", "Comments by Appraiser", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraiser_16", "Rating By Appraiser", "Radio", false, "", 0, 0, false, "@ratings"],
            ["weightage_16", "Weightage", "TextField", false, "", 0, 100, true],
            ["manager_only_16", "Manager only", "Radio", false, "No", 0, 0, true, ["No","Yes"]],
            ["goal_17", "Goal", "Textarea", false, "", 0, 0, true],
            ["objective_17", "Objective", "Textarea", false, "", 0, 0, true],
            ["measurement_17", "Measurement", "Textarea", false, "", 0, 0, true],
            ["comments_by_appraisee_17", "Comments by Appraisee", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraisee_17", "Rating By Appraisee", "Radio", false, "", 0, 0, true, "@ratings"],
            ["comments_by_appraiser_17", "Comments by Appraiser", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraiser_17", "Rating By Appraiser", "Radio", false, "", 0, 0, false, "@ratings"],
            ["weightage_17", "Weightage", "TextField", false, "", 0, 100, true],
            ["manager_only_17", "Manager only", "Radio", false, "No", 0, 0, true, ["No","Yes"]],
            ["goal_18", "Goal", "Textarea", false, "", 0, 0, true],
            ["objective_18", "Objective", "Textarea", false, "", 0, 0, true],
            ["measurement_18", "Measurement", "Textarea", false, "", 0, 0, true],
            ["comments_by_appraisee_18", "Comments by Appraisee", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraisee_18", "Rating By Appraisee", "Radio", false, "", 0, 0, true, "@ratings"],
            ["comments_by_appraiser_18", "Comments by Appraiser", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraiser_18", "Rating By Appraiser", "Radio", false, "", 0, 0, false, "@ratings"],
            ["weightage_18", "Weightage", "TextField", false, "", 0, 100, true],
            ["manager_only_18", "Manager only", "Radio", false, "No", 0, 0, true, ["No","Yes"]],
            ["goal_19", "Goal", "Textarea", false, "", 0, 0, true],
            ["objective_19", "Objective", "Textarea", false, "", 0, 0, true],
            ["measurement_19", "Measurement", "Textarea", false, "", 0, 0, true],
            ["comments_by_appraisee_19", "Comments by Appraisee", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraisee_19", "Rating By Appraisee", "Radio", false, "", 0, 0, true, "@ratings"],
            ["comments_by_appraiser_19", "Comments by Appraiser", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraiser_19", "Rating By Appraiser", "Radio", false, "", 0, 0, false, "@ratings"],
            ["weightage_19", "Weightage", "TextField", false, "", 0, 100, true],
            ["manager_only_19", "Manager only", "Radio", false, "No", 0, 0, true, ["No","Yes"]],
            ["goal_20", "Goal", "Textarea", false, "", 0, 0, true],
            ["objective_20", "Objective", "Textarea", false, "", 0, 0, true],
            ["measurement_20", "Measurement", "Textarea", false, "", 0, 0, true],
            ["comments_by_appraisee_20", "Comments by Appraisee", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraisee_20", "Rating By Appraisee", "Radio", false, "", 0, 0, true, "@ratings"],
            ["comments_by_appraiser_20", "Comments by Appraiser", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraiser_20", "Rating By Appraiser", "Radio", false, "", 0, 0, false, "@ratings"],
            ["weightage_20", "Weightage", "TextField", false, "", 0, 100, true],
            ["manager_only_20", "Manager only", "Radio", false, "No", 0, 0, true, ["No","Yes"]],
            ["section_2_title", "Section title", "Textarea", false, "", 0, 0, false],
            ["section_2_description", "Section  description", "Textarea", false, "", 0, 0, false],
            ["section_3_title", "Section title", "Textarea", false, "", 0, 0, false],
            ["section_3_description", "Section  description", "Textarea", false, "", 0, 0, false],
            ["section_4_title", "Section title", "Textarea", false, "", 0, 0, false],
            ["section_4_description", "Section  description", "Textarea", false, "", 0, 0, false],
            ["overall_comments_by_appraisee", "Overall Comments by Appraisee", "Textarea", false, "", 0, 0, true],
            ["overall_rating_by_appraisee", "Overall Rating By Appraisee", "Radio", false, "", 0, 11, true, "@ratings"],
            ["overall_comments_by_appraiser", "Overall Comments by Appraiser", "Textarea", false, "", 0, 0, true],
            ["overall_rating_by_appraiser", "Overall Rating By Appraiser", "Float", false, "", 0, 256, true],
            ["measurement_5", "Measurement", "Textarea", false, "", 0, 0, true],
            ["comments_by_appraisee_5", "Comments by Appraisee", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraisee_5", "Rating By Appraisee", "Radio", false, "", 0, 0, true, "@ratings"],
            ["comments_by_appraiser_5", "Comments by Appraiser", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraiser_5", "Rating By Appraiser", "Radio", false, "", 0, 0, false, "@ratings"],
            ["weightage_5", "Weightage", "TextField", false, "", 0, 100, true],
            ["manager_only_5", "Manager only", "Radio", false, "No", 0, 0, true, ["No","Yes"]],
            ["section_1_title", "Section title", "Textarea", false, "", 0, 0, false],
            ["section_1_description", "Section  description", "Textarea", false, "", 0, 0, false],
            ["goal_1", "Goal", "Textarea", false, "", 0, 0, true],
            ["objective_1", "Objective", "Textarea", false, "", 0, 0, true],
            ["measurement_1", "Measurement", "Textarea", false, "", 0, 0, true],
            ["comments_by_appraisee_1", "Comments by Appraisee", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraisee_1", "Rating By Appraisee", "Radio", false, "", 0, 0, true, "@ratings"],
            ["comments_by_appraiser_1", "Comments by Appraiser", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraiser_1", "Rating By Appraiser", "Radio", false, "", 0, 0, false, "@ratings"],
            ["weightage_1", "Weightage", "TextField", false, "", 0, 100, true],
            ["manager_only_1", "Manager only", "Radio", false, "No", 0, 0, true, ["No","Yes"]],
            ["goal_2", "Goal", "Textarea", false, "", 0, 0, true],
            ["objective_2", "Objective", "Textarea", false, "", 0, 0, true],
            ["measurement_2", "Measurement", "Textarea", false, "", 0, 0, true],
            ["comments_by_appraisee_2", "Comments by Appraisee", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraisee_2", "Rating By Appraisee", "Radio", false, "", 0, 0, true, "@ratings"],
            ["comments_by_appraiser_2", "Comments by Appraiser", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraiser_2", "Rating By Appraiser", "Radio", false, "", 0, 0, false, "@ratings"],
            ["weightage_2", "Weightage", "TextField", false, "", 0, 100, true],
            ["manager_only_2", "Manager only", "Radio", false, "No", 0, 0, true, ["No","Yes"]],
            ["goal_3", "Goal", "Textarea", false, "", 0, 0, true],
            ["objective_3", "Objective", "Textarea", false, "", 0, 0, true],
            ["measurement_3", "Measurement", "Textarea", false, "", 0, 0, true],
            ["comments_by_appraisee_3", "Comments by Appraisee", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraisee_3", "Rating By Appraisee", "Radio", false, "", 0, 0, true, "@ratings"],
            ["comments_by_appraiser_3", "Comments by Appraiser", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraiser_3", "Rating By Appraiser", "Radio", false, "", 0, 0, false, "@ratings"],
            ["weightage_3", "Weightage", "TextField", false, "", 0, 100, true],
            ["manager_only_3", "Manager only", "Radio", false, "No", 0, 0, true, ["No","Yes"]],
            ["goal_4", "Goal", "Textarea", false, "", 0, 0, true],
            ["objective_4", "Objective", "Textarea", false, "", 0, 0, true],
            ["measurement_4", "Measurement", "Textarea", false, "", 0, 0, true],
            ["comments_by_appraisee_4", "Comments by Appraisee", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraisee_4", "Rating By Appraisee", "Radio", false, "", 0, 0, true, "@ratings"],
            ["comments_by_appraiser_4", "Comments by Appraiser", "Textarea", false, "", 0, 0, true],
            ["rating_by_appraiser_4", "Rating By Appraiser", "Radio", false, "", 0, 0, false, "@ratings"],
            ["weightage_4", "Weightage", "TextField", false, "", 0, 100, true],
            ["manager_only_4", "Manager only", "Radio", false, "No", 0, 0, true, ["No","Yes"]],
            ["goal_5", "Goal", "Textarea", false, "", 0, 0, true],
            ["objective_5", "Objective", "Textarea", false, "", 0, 0, true],
        ]);
		
		/*
		Row Format:
		["field_name_db", "Label", "UI Type", "Unique", "Default_Value", "min_length", "max_length", "Required", "Pop_values"]
        Module::generate("Module_Name", "Table_Name", "view_column_name" "Fields_Array");
        
		Module::generate("Books", 'books', 'name', [
            ["address",     "Address",      "Address",  false, "",          0,  1000,   true],
            ["restricted",  "Restricted",   "Checkbox", false, false,       0,  0,      false],
            ["price",       "Price",        "Currency", false, 0.0,         0,  0,      true],
            ["date_release", "Date of Release", "Date", false, "date('Y-m-d')", 0, 0,   false],
            ["time_started", "Start Time",  "Datetime", false, "date('Y-m-d H:i:s')", 0, 0, false],
            ["weight",      "Weight",       "Decimal",  false, 0.0,         0,  20,     true],
            ["publisher",   "Publisher",    "Dropdown", false, "Marvel",    0,  0,      false, ["Bloomsbury","Marvel","Universal"]],
            ["publisher",   "Publisher",    "Dropdown", false, 3,           0,  0,      false, "@publishers"],
            ["email",       "Email",        "Email",    false, "",          0,  0,      false],
            ["file",        "File",         "File",     false, "",          0,  1,      false],
            ["files",       "Files",        "Files",    false, "",          0,  10,     false],
            ["weight",      "Weight",       "Float",    false, 0.0,         0,  20.00,  true],
            ["biography",   "Biography",    "HTML",     false, "<p>This is description</p>", 0, 0, true],
            ["profile_image", "Profile Image", "Image", false, "img_path.jpg", 0, 250,  false],
            ["pages",       "Pages",        "Integer",  false, 0,           0,  5000,   false],
            ["mobile",      "Mobile",       "Mobile",   false, "+91  8888888888", 0, 20,false],
            ["media_type",  "Media Type",   "Multiselect", false, ["Audiobook"], 0, 0,  false, ["Print","Audiobook","E-book"]],
            ["media_type",  "Media Type",   "Multiselect", false, [2,3],    0,  0,      false, "@media_types"],
            ["name",        "Name",         "Name",     false, "John Doe",  5,  250,    true],
            ["password",    "Password",     "Password", false, "",          6,  250,    true],
            ["status",      "Status",       "Radio",    false, "Published", 0,  0,      false, ["Draft","Published","Unpublished"]],
            ["author",      "Author",       "String",   false, "JRR Tolkien", 0, 250,   true],
            ["genre",       "Genre",        "Taginput", false, ["Fantacy","Adventure"], 0, 0, false],
            ["description", "Description",  "Textarea", false, "",          0,  1000,   false],
            ["short_intro", "Introduction", "TextField",false, "",          5,  250,    true],
            ["website",     "Website",      "URL",      false, "http://dwij.in", 0, 0,  false],
        ]);
		*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('performance_appraisals')) {
            Schema::drop('performance_appraisals');
        }
    }
}
