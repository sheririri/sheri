<?php

require_once("../../include/initialize.php");

global $mydb;


/* =========================================================
   GET SINGLE SCHEDULE DAY FOR EDIT
========================================================= */

if (isset($_POST['ID'])) {

    $output = array();

    $ID = (int)$_POST['ID'];


    $query = "SELECT *
              FROM tblscheduleday
              WHERE id = '".$ID."'
              LIMIT 1";


    $mydb->setQuery($query);

    $result = $mydb->loadResultList();


    foreach ($result as $row) {

        $output["ID"] = $row->id;

        $output["NAME"] = $row->name;

        $output["DESCRIPTION"] = $row->description;

    }


    echo json_encode($output);

}


/* =========================================================
   DATATABLE SCHEDULE DAY LIST
========================================================= */

else {

    $output = array();


    $query = "SELECT *
              FROM tblscheduleday";


    /* =====================================================
       SEARCH
    ===================================================== */

    if (
        isset($_POST["search"]["value"]) &&
        $_POST["search"]["value"] != ''
    ) {

        $search = $_POST["search"]["value"];


        $query .= " WHERE name LIKE '%".$search."%'
                    OR description LIKE '%".$search."%'";

    }


    /* =====================================================
       ORDERING
    ===================================================== */

    if (isset($_POST["order"])) {

        $column = (int)$_POST['order']['0']['column'];

        $dir = $_POST['order']['0']['dir'];


        /*
         * DataTable columns:
         *
         * 0 = #
         * 1 = Day Name
         * 2 = Description
         * 3 = Action
         */


        $allowed_columns = array(

            0 => 'id',

            1 => 'name',

            2 => 'description'

        );


        if (isset($allowed_columns[$column])) {

            $order_column = $allowed_columns[$column];


            if ($dir != 'asc' && $dir != 'desc') {

                $dir = 'asc';

            }


            $query .= " ORDER BY ".$order_column." ".$dir;

        } else {

            $query .= " ORDER BY id ASC";

        }

    } else {

        $query .= " ORDER BY id ASC";

    }


    /* =====================================================
       PAGINATION
    ===================================================== */

    if (
        isset($_POST["length"]) &&
        $_POST["length"] != -1
    ) {

        $start = isset($_POST["start"])
            ? (int)$_POST["start"]
            : 0;


        $length = (int)$_POST["length"];


        $query .= " LIMIT ".$start.", ".$length;

    }


    /* =====================================================
       GET SCHEDULE DAYS
    ===================================================== */

    $mydb->setQuery($query);

    $cur = $mydb->loadResultList();


    $data = array();


    $filtered_rows = $mydb->num_rows();

    $i = 1;


    /* =====================================================
       CREATE DATATABLE ROWS
    ===================================================== */

    foreach ($cur as $result) {

        $sub_array = array();


        /* Number */

        $sub_array[] = $i;


        /* Day Name */

        $sub_array[] = htmlspecialchars(
            $result->name
        );


        /* Description */

        $sub_array[] = htmlspecialchars(
            $result->description
        );


        /* =================================================
           ACTION BUTTONS
        ================================================= */

        $sub_array[] = '

            <!-- EDIT -->

            <button
                type="button"
                name="update"
                ID="'.$result->id.'"
                class="btn btn-warning btn-xs editEntry"
                title="Edit">

                <span class="fa fa-edit"></span>

            </button>


            <!-- VIEW -->

            <a
                href="index.php?view=view&id='.$result->id.'"
                class="btn btn-primary btn-xs"
                title="View">

                <span class="fa fa-eye"></span>

            </a>


            <!-- PRINT -->

            <a
                href="print.php?id='.$result->id.'"
                target="_blank"
                class="btn btn-outline-success btn-xs"
                title="Print">

                <span class="fa fa-print"></span> Print

            </a>


            <!-- DELETE -->

            <button
                type="button"
                name="delete"
                ID="'.$result->id.'"
                class="btn btn-danger btn-xs deleteEntry"
                title="Delete">

                <span class="fa fa-trash"></span>

            </button>

        ';


        $data[] = $sub_array;

        $i++;

    }


    /* =====================================================
       GET TOTAL RECORDS
    ===================================================== */

    function get_total_all_records()
    {

        global $mydb;


        $statement = "SELECT *
                      FROM tblscheduleday";


        $mydb->setQuery($statement);


        return $mydb->num_rows();

    }


    /* =====================================================
       DATATABLE RESPONSE
    ===================================================== */

    $output = array(

        "data" => $data,

        "recordsTotal" => $filtered_rows,

        "recordsFiltered" => get_total_all_records()

    );


    echo json_encode($output);

}

?>