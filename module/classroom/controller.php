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

	$CLASSROOM_NAME = trim($_POST['CLASSROOM_NAME']);
	$DESCRIPTION     = isset($_POST['DESCRIPTION']) ? $_POST['DESCRIPTION'] : '';

	$query = "SELECT * FROM tblclassroom WHERE classroom_name = '".$CLASSROOM_NAME."'";
	$mydb->setQuery($query);
	$res = $mydb->num_rows();

	if ($res >= 1) {
		message("Classroom Name already exist!", "error");
		redirect('index.php');
	} else {
		$query = "INSERT INTO tblclassroom (classroom_name, description)
				  VALUES ('".$CLASSROOM_NAME."', '".$DESCRIPTION."')";
		$mydb->setQuery($query);
		$istrue = ($mydb->num_rows() > 0);

		if ($istrue) {
			message("New Classroom [". $CLASSROOM_NAME ."] has been created successfully!", "success");
			redirect('index.php');
		} else {
			message("No classroom has been created successfully!", "error");
			redirect('index.php');
		}
	}
}

function doEdit(){
	global $mydb;

	if (isset($_POST['edit'])) {
		$CLASSROOM_ROW_ID = $_POST['CLASSROOM_ROW_ID'];
		$CLASSROOM_NAME    = trim($_POST['CLASSROOM_NAME1']);
		$DESCRIPTION       = isset($_POST['DESCRIPTION1']) ? $_POST['DESCRIPTION1'] : '';

		$query = "UPDATE tblclassroom SET
					classroom_name = '".$CLASSROOM_NAME."',
					description = '".$DESCRIPTION."'
				  WHERE id = '".$CLASSROOM_ROW_ID."'";
		$mydb->setQuery($query);
		$istrue = ($mydb->num_rows() > 0);

		if ($istrue) {
			message("Classroom [". $CLASSROOM_NAME ."] has been Updated successfully!", "success");
			redirect('index.php');
		} else {
			message("No classroom has been updated successfully!", "error");
			redirect('index.php');
		}
	}
}

function doDelete(){
	global $mydb;

	$id = $_GET['id'];

	$query = "DELETE FROM tblclassroom WHERE id = '".$id."'";
	$mydb->setQuery($query);

	message("Classroom already Deleted!", "success");
	redirect('index.php');
}
?>
