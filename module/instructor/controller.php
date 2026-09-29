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

	$INSTRUCTOR_ID = trim($_POST['INSTRUCTOR_ID']);
	$NAME          = trim($_POST['NAME']);
	$DESCRIPTION   = isset($_POST['DESCRIPTION']) ? $_POST['DESCRIPTION'] : '';

	$query = "SELECT * FROM tblinstructor WHERE instructor_id = '".$INSTRUCTOR_ID."'";
	$mydb->setQuery($query);
	$res = $mydb->num_rows();

	if ($res >= 1) {
		message("Instructor ID already exist!", "error");
		redirect('index.php');
	} else {
		$query = "INSERT INTO tblinstructor (instructor_id, name, description)
				  VALUES ('".$INSTRUCTOR_ID."', '".$NAME."', '".$DESCRIPTION."')";
		$mydb->setQuery($query);
		$istrue = ($mydb->num_rows() > 0);

		if ($istrue) {
			message("New Instructor [". $INSTRUCTOR_ID ."] has been created successfully!", "success");
			redirect('index.php');
		} else {
			message("No instructor has been created successfully!", "error");
			redirect('index.php');
		}
	}
}

function doEdit(){
	global $mydb;

	if (isset($_POST['edit'])) {
		$INSTRUCTOR_ROW_ID = $_POST['INSTRUCTOR_ROW_ID'];
		$INSTRUCTOR_ID      = trim($_POST['INSTRUCTOR_ID1']);
		$NAME               = trim($_POST['NAME1']);
		$DESCRIPTION        = isset($_POST['DESCRIPTION1']) ? $_POST['DESCRIPTION1'] : '';

		$query = "UPDATE tblinstructor SET
					instructor_id = '".$INSTRUCTOR_ID."',
					name = '".$NAME."',
					description = '".$DESCRIPTION."'
				  WHERE id = '".$INSTRUCTOR_ROW_ID."'";
		$mydb->setQuery($query);
		$istrue = ($mydb->num_rows() > 0);

		if ($istrue) {
			message("Instructor [". $INSTRUCTOR_ID ."] has been Updated successfully!", "success");
			redirect('index.php');
		} else {
			message("No instructor has been updated successfully!", "error");
			redirect('index.php');
		}
	}
}

function doDelete(){
	global $mydb;

	$id = $_GET['id'];

	$query = "DELETE FROM tblinstructor WHERE id = '".$id."'";
	$mydb->setQuery($query);

	message("Instructor already Deleted!", "success");
	redirect('index.php');
}
?>
