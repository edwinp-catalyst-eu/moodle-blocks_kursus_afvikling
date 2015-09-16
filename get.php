<?php
  require_once('../../config.php');  
  global $USER, $CFG, $COURSE;
  if($_POST) {
    $is_record = get_record('block_kursus_afvikling', 'userid', $_POST['userid'], 'courseid', $_POST['courseid']);
    if(empty($is_record->id)) {
      // Insert new
      $newid = insert_record('block_kursus_afvikling', $_POST);
      if (!$newid) {
        error('There was an error trying to add your course settings');
      }    
    } else {
      // Update
      $dataobject->id = $is_record->id;
      $dataobject->userid = $_POST['userid'];
      $dataobject->courseid = $_POST['courseid'];
      $dataobject->showtext = $_POST['showtext'];
      if (!update_record('block_kursus_afvikling', $dataobject)) {
        error('There was an error trying to update the database');
      } 
    }
  } else {
    //  print('failed');
    //  error('There was an error trying to add POST settings the database');
  }
?>