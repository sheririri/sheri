<?php

require_once("../../include/initialize.php");

global $mydb;


/* ==========================================
   LOAD SINGLE INSTRUCTOR
   ========================================== */

if (isset($_POST['ID']) && $_POST['ID'] != '') {

    $id = intval($_POST['ID']);


    $mydb->setQuery("
        SELECT
            id,
            instructor_id,
            name,
            description
        FROM tblinstructor
        WHERE id = " . $id . "
        LIMIT 1
    ");


    $row = $mydb->loadSingleResultAssoc();


    if ($row) {

        echo json_encode(array(

            "ID" => $row['id'],

            "INSTRUCTOR_ID" => $row['instructor_id'],

            "NAME" => $row['name'],

            "DESCRIPTION" => $row['description']

        ));

    } else {

        echo json_encode(array());

    }


    exit;

}



/* ==========================================
   DATATABLES
   ========================================== */

$request = $_POST;


$columns = array(

    0 => 'id',

    1 => 'instructor_id',

    2 => 'name',

    3 => 'description',

    4 => 'id'

);


$limit = isset($request['length'])
    ? intval($request['length'])
    : 10;


$start = isset($request['start'])
    ? intval($request['start'])
    : 0;


$search = isset($request['search']['value'])
    ? trim($request['search']['value'])
    : '';


$orderColumn = isset($request['order'][0]['column'])
    ? intval($request['order'][0]['column'])
    : 1;


$orderDirection = isset($request['order'][0]['dir'])
    ? strtoupper($request['order'][0]['dir'])
    : 'ASC';


if (!in_array($orderDirection, array('ASC', 'DESC'))) {

    $orderDirection = 'ASC';

}


$orderBy = isset($columns[$orderColumn])
    ? $columns[$orderColumn]
    : 'instructor_id';



/* ==========================================
   WHERE
   ========================================== */

$where = "";


if ($search != '') {

    $safeSearch = $mydb->escape_value($search);

    $where = "
        WHERE
            instructor_id LIKE '%" . $safeSearch . "%'
            OR name LIKE '%" . $safeSearch . "%'
            OR description LIKE '%" . $safeSearch . "%'
    ";

}



/* ==========================================
   TOTAL RECORDS
   ========================================== */

$mydb->setQuery("
    SELECT COUNT(*) AS total
    FROM tblinstructor
");


$totalResult = $mydb->loadSingleResultAssoc();


$totalRecords = isset($totalResult['total'])
    ? intval($totalResult['total'])
    : 0;



/* ==========================================
   FILTERED RECORDS
   ========================================== */

$mydb->setQuery("
    SELECT COUNT(*) AS total
    FROM tblinstructor
    " . $where . "
");


$filteredResult = $mydb->loadSingleResultAssoc();


$totalFiltered = isset($filteredResult['total'])
    ? intval($filteredResult['total'])
    : 0;



/* ==========================================
   DATA
   ========================================== */

$sql = "
    SELECT
        id,
        instructor_id,
        name,
        description
    FROM tblinstructor
    " . $where . "
    ORDER BY " . $orderBy . " " . $orderDirection . "
    LIMIT " . $start . ", " . $limit . "
";


$mydb->setQuery($sql);


$rows = $mydb->loadResultList();


$data = array();


foreach ($rows as $row) {


    $action = '

        <div class="btn-group">

            <a href="' . WEB_ROOT . 'module/instructor/index.php?view=view&id=' . $row->id . '"
               class="btn btn-info btn-sm"
               title="View">

                <i class="fas fa-eye"></i>

            </a>


            <button type="button"
                    class="btn btn-warning btn-sm editEntry"
                    ID="' . $row->id . '"
                    title="Edit">

                <i class="fas fa-edit"></i>

            </button>


            <a href="' . WEB_ROOT . 'module/instructor/print.php?id=' . $row->id . '"
               target="_blank"
               class="btn btn-secondary btn-sm"
               title="Print">

                <i class="fas fa-print"></i>

            </a>


            <button type="button"
                    class="btn btn-danger btn-sm deleteEntry"
                    ID="' . $row->id . '"
                    title="Delete">

                <i class="fas fa-trash"></i>

            </button>

        </div>

    ';


    $data[] = array(

        $row->id,

        htmlspecialchars($row->instructor_id),

        htmlspecialchars($row->name),

        htmlspecialchars($row->description),

        $action

    );

}



/* ==========================================
   RESPONSE
   ========================================== */

$json_data = array(

    "draw" => isset($request['draw'])
        ? intval($request['draw'])
        : 0,

    "recordsTotal" => $totalRecords,

    "recordsFiltered" => $totalFiltered,

    "data" => $data

);


echo json_encode($json_data);

?>