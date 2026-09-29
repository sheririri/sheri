<?php

require_once("../../include/initialize.php");

global $mydb;

if (!isset($_SESSION['USERID'])) {

    redirect(WEB_ROOT . "login.php");

}


$action = isset($_GET['action']) ? $_GET['action'] : '';


switch ($action) {


    /* =========================
       ADD INSTRUCTOR
       ========================= */

    case 'add':

        $instructor_id = isset($_POST['INSTRUCTOR_ID'])
            ? trim($_POST['INSTRUCTOR_ID'])
            : '';

        $name = isset($_POST['NAME'])
            ? trim($_POST['NAME'])
            : '';

        $description = isset($_POST['DESCRIPTION'])
            ? trim($_POST['DESCRIPTION'])
            : '';


        if ($instructor_id == '') {

            message("Instructor ID is required.", "error");

            redirect(WEB_ROOT . "module/instructor/index.php?view=list");

        }


        if ($name == '') {

            message("Instructor Name is required.", "error");

            redirect(WEB_ROOT . "module/instructor/index.php?view=list");

        }


        /* CHECK DUPLICATE INSTRUCTOR ID */

        $mydb->setQuery("
            SELECT id
            FROM tblinstructor
            WHERE instructor_id = '" . $mydb->escape_value($instructor_id) . "'
            LIMIT 1
        ");

        $existing = $mydb->loadSingleResult();


        if ($existing) {

            message("Instructor ID already exists.", "error");

            redirect(WEB_ROOT . "module/instructor/index.php?view=list");

        }


        /* INSERT */

        $query = "
            INSERT INTO tblinstructor
            (
                instructor_id,
                name,
                description
            )
            VALUES
            (
                '" . $mydb->escape_value($instructor_id) . "',
                '" . $mydb->escape_value($name) . "',
                '" . $mydb->escape_value($description) . "'
            )
        ";


        if ($mydb->InsertThis($query)) {

            message("Instructor added successfully.", "success");

        } else {

            message("Failed to add instructor.", "error");

        }


        redirect(WEB_ROOT . "module/instructor/index.php?view=list");

        break;



    /* =========================
       EDIT INSTRUCTOR
       ========================= */

    case 'edit':

        $id = isset($_POST['ID'])
            ? intval($_POST['ID'])
            : 0;

        $instructor_id = isset($_POST['INSTRUCTOR_ID1'])
            ? trim($_POST['INSTRUCTOR_ID1'])
            : '';

        $name = isset($_POST['NAME1'])
            ? trim($_POST['NAME1'])
            : '';

        $description = isset($_POST['DESCRIPTION1'])
            ? trim($_POST['DESCRIPTION1'])
            : '';


        if ($id <= 0) {

            message("Invalid instructor.", "error");

            redirect(WEB_ROOT . "module/instructor/index.php?view=list");

        }


        if ($instructor_id == '') {

            message("Instructor ID is required.", "error");

            redirect(WEB_ROOT . "module/instructor/index.php?view=list");

        }


        if ($name == '') {

            message("Instructor Name is required.", "error");

            redirect(WEB_ROOT . "module/instructor/index.php?view=list");

        }


        /* CHECK DUPLICATE ID */

        $mydb->setQuery("
            SELECT id
            FROM tblinstructor
            WHERE instructor_id = '" . $mydb->escape_value($instructor_id) . "'
            AND id != " . $id . "
            LIMIT 1
        ");

        $existing = $mydb->loadSingleResult();


        if ($existing) {

            message("Instructor ID already exists.", "error");

            redirect(WEB_ROOT . "module/instructor/index.php?view=list");

        }


        /* UPDATE */

        $query = "
            UPDATE tblinstructor
            SET
                instructor_id = '" . $mydb->escape_value($instructor_id) . "',
                name = '" . $mydb->escape_value($name) . "',
                description = '" . $mydb->escape_value($description) . "'
            WHERE id = " . $id . "
        ";


        if ($mydb->InsertThis($query)) {

            message("Instructor updated successfully.", "success");

        } else {

            message("Failed to update instructor.", "error");

        }


        redirect(WEB_ROOT . "module/instructor/index.php?view=list");

        break;



    /* =========================
       DELETE INSTRUCTOR
       ========================= */

    case 'delete':

        $id = isset($_GET['id'])
            ? intval($_GET['id'])
            : 0;


        if ($id <= 0) {

            message("Invalid instructor.", "error");

            redirect(WEB_ROOT . "module/instructor/index.php?view=list");

        }


        /* CHECK IF USED IN SET SCHEDULE */

        $mydb->setQuery("
            SELECT id
            FROM tblsetschedule
            WHERE instructor_id = " . $id . "
            LIMIT 1
        ");

        $used = $mydb->loadSingleResult();


        if ($used) {

            message(
                "This instructor cannot be deleted because it is already used in a schedule.",
                "error"
            );

            redirect(WEB_ROOT . "module/instructor/index.php?view=list");

        }


        /* DELETE */

        $query = "
            DELETE FROM tblinstructor
            WHERE id = " . $id . "
        ";


        if ($mydb->InsertThis($query)) {

            message("Instructor deleted successfully.", "success");

        } else {

            message("Failed to delete instructor.", "error");

        }


        redirect(WEB_ROOT . "module/instructor/index.php?view=list");

        break;



    default:

        redirect(WEB_ROOT . "module/instructor/index.php?view=list");

        break;

}

?>