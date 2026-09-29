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

	$DAY_NAME    = trim($_POST['DAY_NAME']);
	$DESCRIPTION = isset($_POST['DESCRIPTION']) ? $_POST['DESCRIPTION'] : '';

	$query = "SELECT * FROM tblscheduleday WHERE day_name = '".$DAY_NAME."'";
	$mydb->setQuery($query);
	$res = $mydb->num_rows();

	if ($res >= 1) {
		message("Schedule Day already exist!", "error");
		redirect('index.php');
	} else {
		$query = "INSERT INTO tblscheduleday (day_name, description)
				  VALUES ('".$DAY_NAME."', '".$DESCRIPTION."')";
		$mydb->setQuery($query);
		$istrue = ($mydb->num_rows() > 0);

		if ($istrue) {
			message("New Schedule Day [". $DAY_NAME ."] has been created successfully!", "success");
			redirect('index.php');
		} else {
			message("No schedule day has been created successfully!", "error");
			redirect('index.php');
		}
	}
}

function doEdit(){
	global $mydb;

	if (isset($_POST['edit'])) {
		$SCHEDULEDAY_ROW_ID = $_POST['SCHEDULEDAY_ROW_ID'];
		$DAY_NAME            = trim($_POST['DAY_NAME1']);
		$DESCRIPTION         = isset($_POST['DESCRIPTION1']) ? $_POST['DESCRIPTION1'] : '';

		$query = "UPDATE tblscheduleday SET
					day_name = '".$DAY_NAME."',
					description = '".$DESCRIPTION."'
				  WHERE id = '".$SCHEDULEDAY_ROW_ID."'";
		$mydb->setQuery($query);
		$istrue = ($mydb->num_rows() > 0);

		if ($istrue) {
			message("Schedule Day [". $DAY_NAME ."] has been Updated successfully!", "success");
			redirect('index.php');
		} else {
			message("No schedule day has been updated successfully!", "error");
			redirect('index.php');
		}
	}
}

function doDelete(){
	global $mydb;

	$id = $_GET['id'];

	$query = "DELETE FROM tblscheduleday WHERE id = '".$id."'";
	$mydb->setQuery($query);

	message("Schedule Day already Deleted!", "success");
	redirect('index.php');
}
?>
