<?php

require_once("../../include/initialize.php");

global $mydb;

header('Content-Type: application/json; charset=utf-8');


// ============================================================
// VIEW / EDIT SINGLE RECORD
// ============================================================

if (isset($_POST['ID']) && $_POST['ID'] != '') {

    $ID = (int)$_POST['ID'];

    $mydb->setQuery("
        SELECT
            ss.id,
            ss.department_id AS DEPARTMENT_ID,
            ss.subject_id AS SUBJECT_ID,
            ss.classroom_id AS CLASSROOM_ID,
            ss.day_id AS DAY_ID,
            ss.time_id AS TIME_ID,
            ss.instructor_id AS INSTRUCTOR_ID,
            ss.semester AS SEMESTER,
            ss.school_year AS SCHOOL_YEAR

        FROM tblsetschedule ss

        WHERE ss.id = '".$ID."'

        LIMIT 1
    ");

    $row = $mydb->loadSingleResult();

    if ($row) {

        echo json_encode($row);

    } else {

        echo json_encode(array(
            "error" => "Schedule record not found."
        ));

    }

    exit;
}



// ============================================================
// DATATABLES
// ============================================================

$draw = isset($_POST['draw'])
    ? (int)$_POST['draw']
    : 0;

$start = isset($_POST['start'])
    ? (int)$_POST['start']
    : 0;

$length = isset($_POST['length'])
    ? (int)$_POST['length']
    : 10;

$search = '';

if (
    isset($_POST['search']) &&
    isset($_POST['search']['value'])
) {
    $search = trim($_POST['search']['value']);
}


// ============================================================
// TOTAL RECORDS
// ============================================================

$mydb->setQuery("
    SELECT COUNT(*) AS total
    FROM tblsetschedule
");

$totalResult = $mydb->loadSingleResult();

$recordsTotal = $totalResult
    ? (int)$totalResult->total
    : 0;



// ============================================================
// SEARCH
// ============================================================

$where = "";

if ($search != '') {

    $search = addslashes($search);

    $where = "
        WHERE
            sub.SUBJECT_CODE LIKE '%".$search."%'
            OR sub.SUBJECT_NAME LIKE '%".$search."%'
            OR d.name LIKE '%".$search."%'
            OR c.name LIKE '%".$search."%'
            OR sd.name LIKE '%".$search."%'
            OR i.name LIKE '%".$search."%'
            OR ss.semester LIKE '%".$search."%'
            OR ss.school_year LIKE '%".$search."%'
    ";
}



// ============================================================
// FILTERED RECORDS
// ============================================================

$mydb->setQuery("
    SELECT COUNT(*) AS total

    FROM tblsetschedule ss

    LEFT JOIN tbldepartment d
        ON d.id = ss.department_id

    LEFT JOIN tblsubjects sub
        ON sub.SUBJECT_ID = ss.subject_id

    LEFT JOIN tblclassroom c
        ON c.id = ss.classroom_id

    LEFT JOIN tblscheduleday sd
        ON sd.id = ss.day_id

    LEFT JOIN tblscheduletime st
        ON st.id = ss.time_id

    LEFT JOIN tblinstructor i
        ON i.id = ss.instructor_id

    ".$where."
");

$filteredResult = $mydb->loadSingleResult();

$recordsFiltered = $filteredResult
    ? (int)$filteredResult->total
    : 0;



// ============================================================
// ORDER
// ============================================================

$orderColumn = 1;
$orderDirection = "ASC";

if (isset($_POST['order'][0]['column'])) {

    $orderColumn = (int)$_POST['order'][0]['column'];

}

if (isset($_POST['order'][0]['dir'])) {

    $orderDirection = strtoupper(
        $_POST['order'][0]['dir']
    );

}

$allowedDirections = array("ASC", "DESC");

if (!in_array($orderDirection, $allowedDirections)) {

    $orderDirection = "ASC";

}



// DataTable column mapping
$orderColumns = array(
    0 => "ss.id",
    1 => "st.time_start",
    2 => "sd.name",
    3 => "sub.SUBJECT_CODE",
    4 => "sub.SUBJECT_NAME",
    5 => "sub.UNITS",
    6 => "c.name",
    7 => "i.name",
    8 => "ss.id"
);

if (!isset($orderColumns[$orderColumn])) {

    $orderColumn = 1;

}

$orderBy = $orderColumns[$orderColumn];



// ============================================================
// MAIN QUERY
// ============================================================

$limitSql = "";

if ($length != -1) {

    $limitSql = "
        LIMIT ".$start.", ".$length."
    ";

}


$mydb->setQuery("
    SELECT

        ss.id,

        d.name AS DEPARTMENT_NAME,

        sub.SUBJECT_CODE,
        sub.SUBJECT_NAME,
        sub.UNITS,

        c.name AS CLASSROOM_NAME,

        sd.name AS DAY_NAME,

        st.time_start,
        st.time_end,

        i.name AS INSTRUCTOR_NAME,

        ss.semester,
        ss.school_year

    FROM tblsetschedule ss

    LEFT JOIN tbldepartment d
        ON d.id = ss.department_id

    LEFT JOIN tblsubjects sub
        ON sub.SUBJECT_ID = ss.subject_id

    LEFT JOIN tblclassroom c
        ON c.id = ss.classroom_id

    LEFT JOIN tblscheduleday sd
        ON sd.id = ss.day_id

    LEFT JOIN tblscheduletime st
        ON st.id = ss.time_id

    LEFT JOIN tblinstructor i
        ON i.id = ss.instructor_id

    ".$where."

    ORDER BY ".$orderBy." ".$orderDirection."

    ".$limitSql."
");

$rows = $mydb->loadResultList();

$data = array();



// ============================================================
// BUILD DATATABLE ROWS
// ============================================================

foreach ($rows as $row) {


    // TIME
    if (
        !empty($row->time_start) &&
        !empty($row->time_end)
    ) {

        $time = date(
            "h:i A",
            strtotime($row->time_start)
        );

        $time .= " - ";

        $time .= date(
            "h:i A",
            strtotime($row->time_end)
        );

    } else {

        $time = "Not Set";

    }


    // DAY
    $day = $row->DAY_NAME
        ? htmlspecialchars($row->DAY_NAME)
        : "Not Set";


    // CODE
    $code = $row->SUBJECT_CODE
        ? htmlspecialchars($row->SUBJECT_CODE)
        : "N/A";


    // SUBJECT
    $subject = $row->SUBJECT_NAME
        ? htmlspecialchars($row->SUBJECT_NAME)
        : "N/A";


    // UNIT
    $unit = $row->UNITS !== null
        ? htmlspecialchars($row->UNITS)
        : "0";


    // ROOM
    $room = $row->CLASSROOM_NAME
        ? htmlspecialchars($row->CLASSROOM_NAME)
        : "Not Assigned";


    // INSTRUCTOR
    $instructor = $row->INSTRUCTOR_NAME
        ? htmlspecialchars($row->INSTRUCTOR_NAME)
        : "Not Assigned";


    // ACTION BUTTONS
    $action = '

        <div class="btn-group">

            <a
                href="'.WEB_ROOT.'module/setschedule/index.php?view=view&id='.$row->id.'"
                class="btn btn-info btn-sm"
                title="View">

                <i class="fa fa-eye"></i>

            </a>


            <button
                type="button"
                class="btn btn-primary btn-sm editEntry"
                ID="'.$row->id.'"
                title="Edit">

                <i class="fa fa-edit"></i>

            </button>


            <a
                href="'.WEB_ROOT.'module/setschedule/print.php?id='.$row->id.'"
                target="_blank"
                class="btn btn-secondary btn-sm"
                title="Print">

                <i class="fa fa-print"></i>

            </a>


            <button
                type="button"
                class="btn btn-danger btn-sm deleteEntry"
                ID="'.$row->id.'"
                title="Delete">

                <i class="fa fa-trash"></i>

            </button>

        </div>

    ';


    $data[] = array(

        "",

        $time,

        $day,

        $code,

        $subject,

        $unit,

        $room,

        $instructor,

        $action

    );

}



// ============================================================
// RETURN JSON
// ============================================================

echo json_encode(array(

    "draw" => $draw,

    "recordsTotal" => $recordsTotal,

    "recordsFiltered" => $recordsFiltered,

    "data" => $data

));

exit;

?>