<?php

require_once("../../include/initialize.php");

global $mydb;

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'add') {

    doInsert();

} elseif ($action == 'edit') {

    doEdit();

} elseif ($action == 'delete') {

    doDelete();

} else {

    redirect('index.php');

}


/*
|--------------------------------------------------------------------------
| ADD
|--------------------------------------------------------------------------
*/

function doInsert()
{
    global $mydb;

    $DEPARTMENT_ID = isset($_POST['DEPARTMENT_ID'])
        ? (int)$_POST['DEPARTMENT_ID'] : 0;

    $SUBJECT_ID = isset($_POST['SUBJECT_ID'])
        ? (int)$_POST['SUBJECT_ID'] : 0;

    $CLASSROOM_ID = isset($_POST['CLASSROOM_ID'])
        ? (int)$_POST['CLASSROOM_ID'] : 0;

    $DAY_ID = isset($_POST['DAY_ID'])
        ? (int)$_POST['DAY_ID'] : 0;

    $TIME_ID = isset($_POST['TIME_ID'])
        ? (int)$_POST['TIME_ID'] : 0;

    $INSTRUCTOR_ID = isset($_POST['INSTRUCTOR_ID'])
        ? (int)$_POST['INSTRUCTOR_ID'] : 0;

    $SEMESTER = isset($_POST['SEMESTER'])
        ? trim($_POST['SEMESTER']) : '';

    $SCHOOL_YEAR = isset($_POST['SCHOOL_YEAR'])
        ? trim($_POST['SCHOOL_YEAR']) : '';


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    if (
        $DEPARTMENT_ID <= 0 ||
        $SUBJECT_ID <= 0 ||
        $CLASSROOM_ID <= 0 ||
        $DAY_ID <= 0 ||
        $TIME_ID <= 0 ||
        $SEMESTER == '' ||
        $SCHOOL_YEAR == ''
    ) {

        message(
            "Please complete all required schedule fields.",
            "error"
        );

        redirect('index.php');

        return;
    }


    $SEMESTER = addslashes($SEMESTER);
    $SCHOOL_YEAR = addslashes($SCHOOL_YEAR);


    /*
    |--------------------------------------------------------------------------
    | CHECK CLASSROOM CONFLICT
    |--------------------------------------------------------------------------
    */

    $mydb->setQuery("
        SELECT s.id

        FROM tblsetschedule s

        INNER JOIN tblscheduletime oldtime
            ON oldtime.id = s.time_id

        INNER JOIN tblscheduletime newtime
            ON newtime.id = '".$TIME_ID."'

        WHERE s.classroom_id = '".$CLASSROOM_ID."'

        AND s.day_id = '".$DAY_ID."'

        AND s.semester = '".$SEMESTER."'

        AND s.school_year = '".$SCHOOL_YEAR."'

        AND oldtime.time_start < newtime.time_end

        AND oldtime.time_end > newtime.time_start

        LIMIT 1
    ");

    $conflict = $mydb->loadResultList();


    if (count($conflict) > 0) {

        message(
            "Schedule conflict: This classroom is already occupied on the selected day and time.",
            "error"
        );

        redirect('index.php');

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK INSTRUCTOR CONFLICT
    |--------------------------------------------------------------------------
    */

    if ($INSTRUCTOR_ID > 0) {

        $mydb->setQuery("
            SELECT s.id

            FROM tblsetschedule s

            INNER JOIN tblscheduletime oldtime
                ON oldtime.id = s.time_id

            INNER JOIN tblscheduletime newtime
                ON newtime.id = '".$TIME_ID."'

            WHERE s.instructor_id = '".$INSTRUCTOR_ID."'

            AND s.day_id = '".$DAY_ID."'

            AND s.semester = '".$SEMESTER."'

            AND s.school_year = '".$SCHOOL_YEAR."'

            AND oldtime.time_start < newtime.time_end

            AND oldtime.time_end > newtime.time_start

            LIMIT 1
        ");

        $conflict = $mydb->loadResultList();


        if (count($conflict) > 0) {

            message(
                "Schedule conflict: This instructor is already assigned on the selected day and time.",
                "error"
            );

            redirect('index.php');

            return;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK DUPLICATE SUBJECT
    |--------------------------------------------------------------------------
    */

    $mydb->setQuery("
        SELECT id

        FROM tblsetschedule

        WHERE subject_id = '".$SUBJECT_ID."'

        AND semester = '".$SEMESTER."'

        AND school_year = '".$SCHOOL_YEAR."'

        LIMIT 1
    ");

    $existing = $mydb->loadResultList();


    if (count($existing) > 0) {

        message(
            "This subject already has a schedule for the selected semester and school year.",
            "error"
        );

        redirect('index.php');

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | INSERT
    |--------------------------------------------------------------------------
    */

    if ($INSTRUCTOR_ID > 0) {

        $INSTRUCTOR_SQL = "'".$INSTRUCTOR_ID."'";

    } else {

        $INSTRUCTOR_SQL = "NULL";

    }


    $query = "
        INSERT INTO tblsetschedule
        (
            department_id,
            subject_id,
            classroom_id,
            day_id,
            time_id,
            instructor_id,
            semester,
            school_year
        )

        VALUES
        (
            '".$DEPARTMENT_ID."',
            '".$SUBJECT_ID."',
            '".$CLASSROOM_ID."',
            '".$DAY_ID."',
            '".$TIME_ID."',
            ".$INSTRUCTOR_SQL.",
            '".$SEMESTER."',
            '".$SCHOOL_YEAR."'
        )
    ";


    $result = $mydb->InsertThis($query);


    if ($result === true) {

        message(
            "New Schedule has been created successfully!",
            "success"
        );

    } else {

        message(
            "Schedule was not created.",
            "error"
        );
    }


    redirect('index.php');
}


/*
|--------------------------------------------------------------------------
| EDIT
|--------------------------------------------------------------------------
*/

function doEdit()
{
    global $mydb;


    $ID = isset($_POST['ID'])
        ? (int)$_POST['ID'] : 0;


    $DEPARTMENT_ID = isset($_POST['DEPARTMENT_ID'])
        ? (int)$_POST['DEPARTMENT_ID'] : 0;

    $SUBJECT_ID = isset($_POST['SUBJECT_ID'])
        ? (int)$_POST['SUBJECT_ID'] : 0;

    $CLASSROOM_ID = isset($_POST['CLASSROOM_ID'])
        ? (int)$_POST['CLASSROOM_ID'] : 0;

    $DAY_ID = isset($_POST['DAY_ID'])
        ? (int)$_POST['DAY_ID'] : 0;

    $TIME_ID = isset($_POST['TIME_ID'])
        ? (int)$_POST['TIME_ID'] : 0;

    $INSTRUCTOR_ID = isset($_POST['INSTRUCTOR_ID'])
        ? (int)$_POST['INSTRUCTOR_ID'] : 0;

    $SEMESTER = isset($_POST['SEMESTER'])
        ? trim($_POST['SEMESTER']) : '';

    $SCHOOL_YEAR = isset($_POST['SCHOOL_YEAR'])
        ? trim($_POST['SCHOOL_YEAR']) : '';


    /*
    |--------------------------------------------------------------------------
    | ID CHECK
    |--------------------------------------------------------------------------
    */

    if ($ID <= 0) {

        message(
            "Invalid schedule ID.",
            "error"
        );

        redirect('index.php');

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | REQUIRED FIELDS
    |--------------------------------------------------------------------------
    */

    if (
        $DEPARTMENT_ID <= 0 ||
        $SUBJECT_ID <= 0 ||
        $CLASSROOM_ID <= 0 ||
        $DAY_ID <= 0 ||
        $TIME_ID <= 0 ||
        $SEMESTER == '' ||
        $SCHOOL_YEAR == ''
    ) {

        message(
            "Please complete all required schedule fields.",
            "error"
        );

        redirect('index.php');

        return;
    }


    $SEMESTER = addslashes($SEMESTER);
    $SCHOOL_YEAR = addslashes($SCHOOL_YEAR);


    /*
    |--------------------------------------------------------------------------
    | CLASSROOM CONFLICT
    |--------------------------------------------------------------------------
    */

    $mydb->setQuery("
        SELECT s.id

        FROM tblsetschedule s

        INNER JOIN tblscheduletime oldtime
            ON oldtime.id = s.time_id

        INNER JOIN tblscheduletime newtime
            ON newtime.id = '".$TIME_ID."'

        WHERE s.id != '".$ID."'

        AND s.classroom_id = '".$CLASSROOM_ID."'

        AND s.day_id = '".$DAY_ID."'

        AND s.semester = '".$SEMESTER."'

        AND s.school_year = '".$SCHOOL_YEAR."'

        AND oldtime.time_start < newtime.time_end

        AND oldtime.time_end > newtime.time_start

        LIMIT 1
    ");

    $conflict = $mydb->loadResultList();


    if (count($conflict) > 0) {

        message(
            "Schedule conflict: This classroom is already occupied on the selected day and time.",
            "error"
        );

        redirect('index.php');

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | INSTRUCTOR CONFLICT
    |--------------------------------------------------------------------------
    */

    if ($INSTRUCTOR_ID > 0) {

        $mydb->setQuery("
            SELECT s.id

            FROM tblsetschedule s

            INNER JOIN tblscheduletime oldtime
                ON oldtime.id = s.time_id

            INNER JOIN tblscheduletime newtime
                ON newtime.id = '".$TIME_ID."'

            WHERE s.id != '".$ID."'

            AND s.instructor_id = '".$INSTRUCTOR_ID."'

            AND s.day_id = '".$DAY_ID."'

            AND s.semester = '".$SEMESTER."'

            AND s.school_year = '".$SCHOOL_YEAR."'

            AND oldtime.time_start < newtime.time_end

            AND oldtime.time_end > newtime.time_start

            LIMIT 1
        ");

        $conflict = $mydb->loadResultList();


        if (count($conflict) > 0) {

            message(
                "Schedule conflict: This instructor is already assigned on the selected day and time.",
                "error"
            );

            redirect('index.php');

            return;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DUPLICATE SUBJECT
    |--------------------------------------------------------------------------
    */

    $mydb->setQuery("
        SELECT id

        FROM tblsetschedule

        WHERE subject_id = '".$SUBJECT_ID."'

        AND semester = '".$SEMESTER."'

        AND school_year = '".$SCHOOL_YEAR."'

        AND id != '".$ID."'

        LIMIT 1
    ");

    $existing = $mydb->loadResultList();


    if (count($existing) > 0) {

        message(
            "This subject already has another schedule for the selected semester and school year.",
            "error"
        );

        redirect('index.php');

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    if ($INSTRUCTOR_ID > 0) {

        $INSTRUCTOR_SQL = "'".$INSTRUCTOR_ID."'";

    } else {

        $INSTRUCTOR_SQL = "NULL";

    }


    $query = "
        UPDATE tblsetschedule SET

            department_id = '".$DEPARTMENT_ID."',

            subject_id = '".$SUBJECT_ID."',

            classroom_id = '".$CLASSROOM_ID."',

            day_id = '".$DAY_ID."',

            time_id = '".$TIME_ID."',

            instructor_id = ".$INSTRUCTOR_SQL.",

            semester = '".$SEMESTER."',

            school_year = '".$SCHOOL_YEAR."'

        WHERE id = '".$ID."'
    ";


    $result = $mydb->InsertThis($query);


    if ($result === true) {

        message(
            "Schedule has been updated successfully!",
            "success"
        );

    } else {

        message(
            "Schedule was not updated.",
            "error"
        );
    }


    redirect('index.php');
}


/*
|--------------------------------------------------------------------------
| DELETE
|--------------------------------------------------------------------------
*/

function doDelete()
{
    global $mydb;


    $ID = isset($_GET['id'])
        ? (int)$_GET['id'] : 0;


    if ($ID <= 0) {

        message(
            "Invalid schedule ID.",
            "error"
        );

        redirect('index.php');

        return;
    }


    $query = "
        DELETE FROM tblsetschedule
        WHERE id = '".$ID."'
    ";


    $result = $mydb->InsertThis($query);


    if ($result === true) {

        message(
            "Schedule has been deleted successfully!",
            "success"
        );

    } else {

        message(
            "Schedule could not be deleted.",
            "error"
        );
    }


    redirect('index.php');
}

?>