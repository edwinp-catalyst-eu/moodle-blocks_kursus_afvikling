<?php //$Id: block_kursus_afvikling.php,v 1.54.2.3 2008/03/03 11:41:03 moodler Exp $


class block_kursus_afvikling extends block_base {

    function init() {
        $this->title = get_string('blockname','block_kursus_afvikling');
        $this->version = 2008041700;
        $this->localDebug = false;
    }

    function applicable_formats() {
        return array('all' => true);
    }
    

    function has_config() {
    	return false;
	  }

    function specialization() {
        $this->title = isset($this->config->title) ? $this->config->title : get_string('blockname','block_kursus_afvikling');
    }

    function instance_allow_multiple() {
        return true;
    }

    function get_content() {
        global $USER, $CFG, $COURSE;
        
        if (!empty($COURSE)) {
            $this->courseid = $COURSE->id;
        }

        $showtext = '';
        $hidetext = '';
        $selected_course = (($this->courseid) > 1) ? ($this->courseid) : -1;
        $this->content = new stdClass;
        $this->content->text  = '<br />';

        if (empty($this->instance)) {
            return $this->content;
        }
        
        if($selected_course && $selected_course != -1 && $USER->id) {
          $getcoursesetting = get_record('block_kursus_afvikling', 'userid', $USER->id, 'courseid', $selected_course);
        }
        
        if($this->localDebug) {
          t3lib_div::debug($getcoursesetting, "Debug existing course");
        }

        if (isset($getcoursesetting) && isset($getcoursesetting->id)) {
          if($getcoursesetting->showtext == 1) {
            $showtext = "checked=\"checked\"";
            $hidetext = '';
          } else {
            $showtext = '';
            $hidetext = "checked=\"checked\"";
          }
        } else {
          // Insert default | with text
          $dataobject->userid = $USER->id;
          $dataobject->courseid = $selected_course;
          $dataobject->showtext = 1; 
          if(!$newid = insert_record('block_kursus_afvikling', $dataobject)){
            error('There was an error trying to add your settings');
          }
          
          $showtext = "checked=\"checked\"";
          $hidetext = '';

          if (!$newid) {
            error('There was an error trying to add your settings');
          }

          if($this->localDebug) {
            t3lib_div::debug($dataobject, "Debug Default insert");
          }
        }
        $this->content->text .="
        <div id=\"yuidebugger\"></div>
        
        <script type=\"text/javascript\">
          var div = document.getElementById(\"yuidebugger\");
          var handleSuccess = function(o){
          }
          var handleFailure = function(o){
          }
          var callback = function(o) {
            //  success:handleSuccess,
            //  failure: handleFailure,
          }
          function makeRequest(value){
            var postData = 'userid='+$USER->id+'&courseid='+$this->courseid+'&showtext='+value; 
            var sUrl = '$CFG->wwwroot'+'/blocks/kursus_afvikling/get.php';
            var request = YAHOO.util.Connect.asyncRequest('POST', sUrl, callback, postData);
          }
        </script>";
        $this->content->text .="
        <form method=\"post\" id=\"coursesettings\" action=\"\">
        <table>
            <tr>
                <td colspan=\"2\">
                  <label id=\"showtext\">
                    <input onclick=\"makeRequest(1);\" type=\"radio\" name=\"RadioGroup1\" value=\"radio\" id=\"RadioGroup1_0\" $showtext /> ".get_string('showtext','block_kursus_afvikling')."
                  </label>
                    <br />
                  <label id=\"notext\">
                    <input onclick=\"makeRequest(0);\" type=\"radio\" name=\"RadioGroup1\" value=\"radio\" id=\"RadioGroup1_1\" $hidetext /> ".get_string('hidetext','block_kursus_afvikling')."
                  </label>
                </td>
            </tr>
        </table>
      </form><br />";
      $this->content->footer = "";  
      return $this->content;
    }
}
?>
