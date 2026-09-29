<?php

// ARSUENTO SOLUTIONS

require_once("../../include/initialize.php");
global $mydb;

if (isset($_POST['CLASSROOM_ROW_ID'])) {
	$output = array();
	$query =	"SELECT * FROM tblclassroom
		WHERE id = '".$_POST["CLASSROOM_ROW_ID"]."'
		LIMIT 1";
	$mydb->setQuery($query);
	$result = $mydb->loadResultList();

	foreach($result as $row)
	{
		$output["CLASSROOM_ROW_ID"] = $row->id;
		$output["CLASSROOM_NAME"] = $row->classroom_name;
		$output["DESCRIPTION"] = $row->description;
	}
	echo json_encode($output);
}else{
	$output = array();
	$query = "SELECT * FROM `tblclassroom` ";

	if(isset($_POST["search"]["value"]) && $_POST["search"]["value"] != '')
	{
	$query .= " where `classroom_name` LIKE '%".$_POST["search"]["value"]."%' ";
	}
	if(isset($_POST["order"]))
	{
		$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
	}
	else
	{
		$query .= 'ORDER BY `classroom_name` ASC ';
	}
	if($_POST["length"] != -1)
	{
		$query .= " LIMIT " . $_POST['start'] . ", " . $_POST['length'] . "";
	}
	$mydb->setQuery($query);
	$cur = $mydb->loadResultList();
	$data = array();
	$filtered_rows = $mydb->num_rows();
	$i = 1;
	foreach ($cur as $result) {

	$sub_array = array();

		$sub_array[] = $i;
		$sub_array[] = $result->classroom_name;
		$sub_array[] = $result->description;
		$sub_array[] = '
		<a href="index.php?view=view&id='.$result->id.'"><button type="button" class="btn btn-success btn-xs" title="View"><span class="fa fa-eye"></span></button></a>
		<button type="button" name="update" CLASSROOM_ROW_ID="'.$result->id .'" class="btn btn-warning btn-xs editEntry"><span class="fa fa-edit fw-fa"></span></button>
		<button type="button" name="delete" CLASSROOM_ROW_ID="'.$result->id .'" class="btn btn-danger btn-xs deleteEntry"><span class="fa fa-trash"></span></button>';
		$data[] = $sub_array;
	$i = $i + 1;
	}
	function get_total_all_records()
	{
		global $mydb;
		$statement = "SELECT * FROM `tblclassroom`";
		$mydb->setQuery($statement);
		return $mydb->num_rows();
	}

	$output = array('data' 			   => $data,
					"recordsTotal"	   => $filtered_rows,
					"recordsFiltered"	=>	get_total_all_records() );
	echo json_encode($output);
}
?>
