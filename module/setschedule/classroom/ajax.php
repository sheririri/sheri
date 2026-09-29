<?php

require_once("../../include/initialize.php");

global $mydb;

header('Content-Type: application/json; charset=utf-8');

try {

    /*
    |--------------------------------------------------------------------------
    | SINGLE RECORD REQUEST
    |--------------------------------------------------------------------------
    */
    if (isset($_POST['ID']) && $_POST['ID'] != '') {

        $id = intval($_POST['ID']);

        $sql = "SELECT 
                    id,
                    name,
                    description
                FROM tblclassroom
                WHERE id = {$id}
                LIMIT 1";

        $mydb->setQuery($sql);
        $result = $mydb->loadSingleResultAssoc();

        if ($result) {

            echo json_encode([
                "status" => "success",
                "data" => $result
            ]);

        } else {

            echo json_encode([
                "status" => "error",
                "message" => "Classroom not found."
            ]);
        }

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | DATATABLE PARAMETERS
    |--------------------------------------------------------------------------
    */

    $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 0;

    $start = isset($_POST['start']) ? intval($_POST['start']) : 0;

    $length = isset($_POST['length']) ? intval($_POST['length']) : 10;

    if ($length < 1) {
        $length = 10;
    }

    $search = "";

    if (isset($_POST['search']['value'])) {
        $search = trim($_POST['search']['value']);
    }


    /*
    |--------------------------------------------------------------------------
    | ORDERING
    |--------------------------------------------------------------------------
    */

    $columns = [
        0 => "id",
        1 => "name",
        2 => "description"
    ];

    $orderColumnIndex = isset($_POST['order'][0]['column'])
        ? intval($_POST['order'][0]['column'])
        : 1;

    $orderDirection = isset($_POST['order'][0]['dir'])
        ? strtolower($_POST['order'][0]['dir'])
        : "asc";

    if (!isset($columns[$orderColumnIndex])) {
        $orderColumnIndex = 1;
    }

    if ($orderDirection !== "desc") {
        $orderDirection = "asc";
    }

    $orderColumn = $columns[$orderColumnIndex];


    /*
    |--------------------------------------------------------------------------
    | TOTAL RECORDS
    |--------------------------------------------------------------------------
    */

    $mydb->setQuery("
        SELECT COUNT(*) AS total
        FROM tblclassroom
    ");

    $totalResult = $mydb->loadSingleResultAssoc();

    $recordsTotal = 0;

    if ($totalResult) {
        $recordsTotal = intval($totalResult['total']);
    }


    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    $where = "";

    if ($search != '') {

        $searchSafe = addslashes($search);

        $where = "
            WHERE 
                name LIKE '%{$searchSafe}%'
                OR description LIKE '%{$searchSafe}%'
        ";
    }


    /*
    |--------------------------------------------------------------------------
    | FILTERED RECORDS
    |--------------------------------------------------------------------------
    */

    $mydb->setQuery("
        SELECT COUNT(*) AS total
        FROM tblclassroom
        {$where}
    ");

    $filteredResult = $mydb->loadSingleResultAssoc();

    $recordsFiltered = 0;

    if ($filteredResult) {
        $recordsFiltered = intval($filteredResult['total']);
    }


    /*
    |--------------------------------------------------------------------------
    | DATA
    |--------------------------------------------------------------------------
    */

    $sql = "
        SELECT
            id,
            name,
            description
        FROM tblclassroom
        {$where}
        ORDER BY {$orderColumn} {$orderDirection}
        LIMIT {$start}, {$length}
    ";

    $mydb->setQuery($sql);

    $results = $mydb->loadResultList();


    /*
    |--------------------------------------------------------------------------
    | BUILD DATATABLE DATA
    |--------------------------------------------------------------------------
    */

    $data = [];

    $counter = $start + 1;

    foreach ($results as $row) {

        $id = intval($row->id);

        $name = htmlspecialchars(
            $row->name ?? '',
            ENT_QUOTES,
            'UTF-8'
        );

        $description = htmlspecialchars(
            $row->description ?? '',
            ENT_QUOTES,
            'UTF-8'
        );


        /*
        |----------------------------------------------------------------------
        | ACTION BUTTONS
        |----------------------------------------------------------------------
        */

        $actions = '
            <div class="btn-group">

                <button
                    type="button"
                    class="btn btn-sm btn-info"
                    onclick="editClassroom(' . $id . ')">
                    <i class="fa fa-edit"></i>
                </button>

                <a
                    href="' . WEB_ROOT . 'module/classroom/index.php?view=view&id=' . $id . '"
                    class="btn btn-sm btn-success">
                    <i class="fa fa-eye"></i>
                </a>

                <a
                    href="' . WEB_ROOT . 'module/classroom/print.php?id=' . $id . '"
                    target="_blank"
                    class="btn btn-sm btn-primary">
                    <i class="fa fa-print"></i>
                </a>

                <button
                    type="button"
                    class="btn btn-sm btn-danger"
                    onclick="deleteClassroom(' . $id . ')">
                    <i class="fa fa-trash"></i>
                </button>

            </div>
        ';


        $data[] = [
            $counter,
            $name,
            $description,
            $actions
        ];

        $counter++;
    }


    /*
    |--------------------------------------------------------------------------
    | FINAL JSON RESPONSE
    |--------------------------------------------------------------------------
    */

    echo json_encode([
        "draw" => $draw,
        "recordsTotal" => $recordsTotal,
        "recordsFiltered" => $recordsFiltered,
        "data" => $data
    ], JSON_UNESCAPED_UNICODE);

    exit;


} catch (Throwable $e) {

    /*
    |--------------------------------------------------------------------------
    | ERROR RESPONSE
    |--------------------------------------------------------------------------
    |
    | This guarantees DataTables receives valid JSON even when PHP/SQL
    | encounters an error.
    |
    */

    http_response_code(200);

    echo json_encode([
        "draw" => isset($_POST['draw']) ? intval($_POST['draw']) : 0,
        "recordsTotal" => 0,
        "recordsFiltered" => 0,
        "data" => [],
        "error" => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);

    exit;
}
?>