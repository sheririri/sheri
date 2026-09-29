<?php
// ARSUENTO SOLUTIONS
require_once ("../../include/initialize.php");
if (!isset($_SESSION['ACCOUNT_ID'])){
    // redirect(web_root."admin/index.php");
}

global $mydb;

$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';

switch ($action) {
	case 'add' :
		doInsert();
		break;

	case 'edit' :
		doEdit();
		break;

	case 'delete' :
		doDelete();
		break;
}

function doInsert(){
	global $mydb;

	$TIME_START  = trim($_POST['TIME_START']);
	$TIME_END    = trim($_POST['TIME_END']);
	$DESCRIPTION = isset($_POST['DESCRIPTION']) ? $_POST['DESCRIPTION'] : '';

	$query = "INSERT INTO tblscheduletime (time_start, time_end, description)
			  VALUES ('".$TIME_START."', '".$TIME_END."', '".$DESCRIPTION."')";
	$mydb->setQuery($query);
	$istrue = ($mydb->num_rows() > 0);

	if ($istrue) {
		message("New Schedule Time has been created successfully!", "success");
		redirect('index.php');
	} else {
		message("No schedule time has been created successfully!", "error");
		redirect('index.php');
	}
}

function doEdit(){
	global $mydb;

	if (isset($_POST['edit'])) {
		$SCHEDULETIME_ROW_ID = $_POST['SCHEDULETIME_ROW_ID'];
		$TIME_START           = trim($_POST['TIME_START1']);
		$TIME_END             = trim($_POST['TIME_END1']);
		$DESCRIPTION          = isset($_POST['DESCRIPTION1']) ? $_POST['DESCRIPTION1'] : '';

		$query = "UPDATE tblscheduletime SET
					time_start = '".$TIME_START."',
					time_end = '".$TIME_END."',
					description = '".$DESCRIPTION."'
				  WHERE id = '".$SCHEDULETIME_ROW_ID."'";
		$mydb->setQuery($query);
		$istrue = ($mydb->num_rows() > 0);

		if ($istrue) {
			message("Schedule Time has been Updated successfully!", "success");
			redirect('index.php');
		} else {
			message("No schedule time has been updated successfully!", "error");
			redirect('index.php');
		}
	}
}

function doDelete(){
	global $mydb;

	$id = $_GET['id'];

	$query = "DELETE FROM tblscheduletime WHERE id = '".$id."'";
	$mydb->setQuery($query);

	message("Schedule Time already Deleted!", "success");
	redirect('index.php');
}
?>
