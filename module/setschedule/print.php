<?php

require_once("../../include/initialize.php");

global $mydb;


/*
|--------------------------------------------------------------------------
| GET PARAMETERS
|--------------------------------------------------------------------------
*/

$ID = isset($_GET['id'])
    ? (int)$_GET['id']
    : 0;

$semester = isset($_GET['semester'])
    ? trim($_GET['semester'])
    : '1st Semester';

$school_year = isset($_GET['school_year'])
    ? trim($_GET['school_year'])
    : '2026-2027';

$section = isset($_GET['section'])
    ? trim($_GET['section'])
    : '1ST YEAR - SET C';


/*
|--------------------------------------------------------------------------
| IF ID IS PROVIDED
| Print only that schedule
|--------------------------------------------------------------------------
*/

$idCondition = '';

if ($ID > 0) {

    $idCondition = "
        AND ss.id = '".$ID."'
    ";

}


/*
|--------------------------------------------------------------------------
| GET SCHEDULE DATA
|--------------------------------------------------------------------------
*/

$mydb->setQuery("
    SELECT

        ss.id AS ID,

        ss.department_id AS DEPARTMENT_ID,

        ss.subject_id AS SUBJECT_ID,

        ss.classroom_id AS CLASSROOM_ID,

        ss.day_id AS DAY_ID,

        ss.time_id AS TIME_ID,

        ss.instructor_id AS INSTRUCTOR_ID,

        ss.semester AS SEMESTER,

        ss.school_year AS SCHOOL_YEAR,

        st.time_start AS TIME_START,

        st.time_end AS TIME_END,

        sd.name AS DAY_NAME,

        sub.SUBJECT_CODE AS SUBJECT_CODE,

        sub.SUBJECT_NAME AS SUBJECT_NAME,

        sub.UNITS AS UNITS,

        c.name AS CLASSROOM_NAME,

        i.name AS INSTRUCTOR_NAME,

        d.name AS DEPARTMENT_NAME

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

    WHERE ss.semester = '".addslashes($semester)."'

    AND ss.school_year = '".addslashes($school_year)."'

    ".$idCondition."

    ORDER BY

        st.time_start ASC,

        ss.day_id ASC,

        sub.SUBJECT_CODE ASC
");


$schedules = $mydb->loadResultList();


/*
|--------------------------------------------------------------------------
| CHECK IF RECORD EXISTS
|--------------------------------------------------------------------------
*/

if (!$schedules || count($schedules) == 0) {

    ?>

    <!DOCTYPE html>

    <html>

    <head>

        <title>
            Schedule Not Found
        </title>

        <style>

            body {
                font-family: Arial, sans-serif;
                text-align: center;
                padding-top: 100px;
            }

        </style>

    </head>

    <body>

        <h2>
            No schedule found.
        </h2>

        <p>
            Please check the semester and school year.
        </p>

    </body>

    </html>

    <?php

    exit;
}


/*
|--------------------------------------------------------------------------
| TOTAL UNITS
|--------------------------------------------------------------------------
*/

$totalUnits = 0;

foreach ($schedules as $schedule) {

    $totalUnits += (float)$schedule->UNITS;

}


/*
|--------------------------------------------------------------------------
| SCHOOL INFORMATION
|--------------------------------------------------------------------------
*/

$schoolName =
    "COLEGIO DE SANTA RITA DE SAN CARLOS, INC ";

$schoolAddress =
    "San Carlos City, Negros Occidental";

$schoolTel =
    "Tel. No.: (034) 312-6212";

$schoolEmail =
    "Email: csr_main@csr.edu.ph";


/*
|--------------------------------------------------------------------------
| DEPARTMENT
|--------------------------------------------------------------------------
*/

$departmentName =
    "BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY (BSIT)";


/*
|--------------------------------------------------------------------------
| LOGO
|--------------------------------------------------------------------------
*/

$logo =
    WEB_ROOT . "csr-scc.png";


?>

<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Schedule of Classes
    </title>


    <style>

        /*
        |--------------------------------------------------------------------------
        | GENERAL
        |--------------------------------------------------------------------------
        */

        * {

            box-sizing: border-box;

        }


        html,
        body {

            margin: 0;

            padding: 0;

            background: #ffffff;

            color: #111111;

            font-family:
                "Times New Roman",
                Times,
                serif;

        }


        /*
        |--------------------------------------------------------------------------
        | PRINT CONTAINER
        |--------------------------------------------------------------------------
        */

        .print-container {

            width: 100%;

            max-width: 780px;

            margin: 0 auto;

            padding: 15px 20px;

        }


        /*
        |--------------------------------------------------------------------------
        | SCHOOL HEADER
        |--------------------------------------------------------------------------
        */

        .school-header {

            position: relative;

            width: 100%;

            text-align: center;

            min-height: 92px;

            padding-top: 2px;

        }


        .school-logo {

            position: absolute;

            left: 5px;

            top: 0;

            width: 82px;

            height: 82px;

            object-fit: contain;

        }


        .school-name {

            font-size: 18px;

            font-weight: bold;

            margin-top: 2px;

            line-height: 1.2;

        }


        .school-address {

            font-size: 11px;

            margin-top: 2px;

            line-height: 1.2;

        }


        .school-tel {

            font-size: 10px;

            margin-top: 2px;

            line-height: 1.2;

        }


        .school-email {

            font-size: 10px;

            margin-top: 1px;

            line-height: 1.2;

        }


        /*
        |--------------------------------------------------------------------------
        | MAIN TITLE
        |--------------------------------------------------------------------------
        */

        .schedule-title {

            text-align: center;

            font-size: 21px;

            font-weight: bold;

            text-decoration: underline;

            margin-top: 7px;

            line-height: 1.1;

        }


        .semester-title {

            text-align: center;

            font-size: 16px;

            font-weight: bold;

            margin-top: 2px;

            line-height: 1.2;

        }


        .section-title {

            text-align: center;

            font-size: 22px;

            font-weight: bold;

            color: #3d78a8;

            margin-top: 2px;

            line-height: 1.2;

        }


        /*
        |--------------------------------------------------------------------------
        | RED PROGRAM BAR
        |--------------------------------------------------------------------------
        */

        .department-title {

            width: 100%;

            text-align: center;

            background: #d9534f;

            color: #111111;

            border: 1px solid #b83b38;

            font-size: 15px;

            font-weight: bold;

            padding: 3px 4px;

            margin-top: 2px;

            line-height: 1.1;

        }


        /*
        |--------------------------------------------------------------------------
        | SCHEDULE TABLE
        |--------------------------------------------------------------------------
        */

        .schedule-table {

            width: 100%;

            border-collapse: collapse;

            table-layout: fixed;

            margin-top: 0;

        }


        .schedule-table th,
        .schedule-table td {

            border: 1px solid #777777;

            text-align: center;

            vertical-align: middle;

        }


        /*
        |--------------------------------------------------------------------------
        | TABLE HEADER
        |--------------------------------------------------------------------------
        */

        .schedule-table th {

            height: 27px;

            padding: 3px;

            background: #f7f7f7;

            color: #a94442;

            font-size: 10px;

            font-weight: bold;

            line-height: 1.05;

        }


        /*
        |--------------------------------------------------------------------------
        | TABLE DATA
        |--------------------------------------------------------------------------
        */

        .schedule-table td {

            padding: 4px 3px;

            font-size: 10px;

            line-height: 1.15;

        }


        /*
        |--------------------------------------------------------------------------
        | COLUMN WIDTHS
        |--------------------------------------------------------------------------
        */

        .time-column {

            width: 14%;

        }


        .day-column {

            width: 8%;

        }


        .code-column {

            width: 13%;

        }


        .subject-column {

            width: 24%;

        }


        .unit-column {

            width: 7%;

        }


        .room-column {

            width: 17%;

        }


        .instructor-column {

            width: 17%;

        }


        /*
        |--------------------------------------------------------------------------
        | SUBJECT DESCRIPTION
        |--------------------------------------------------------------------------
        */

        .subject-description {

            text-align: center;

            text-transform: uppercase;

            word-wrap: break-word;

        }


        /*
        |--------------------------------------------------------------------------
        | TIME
        |--------------------------------------------------------------------------
        */

        .time-cell {

            line-height: 1.2;

        }


        /*
        |--------------------------------------------------------------------------
        | BLUE SEPARATOR
        |--------------------------------------------------------------------------
        */

        .blue-separator td {

            height: 9px;

            padding: 0;

            background: #4c9ed8;

            border-color: #4c9ed8;

        }


        /*
        |--------------------------------------------------------------------------
        | TOTAL
        |--------------------------------------------------------------------------
        */

        .total-row td {

            height: 24px;

            font-weight: bold;

            font-size: 11px;

        }


        /*
        |--------------------------------------------------------------------------
        | PRINT BUTTON
        |--------------------------------------------------------------------------
        */

        .print-button {

            text-align: center;

            margin-top: 20px;

        }


        .print-button button {

            border: none;

            background: #007bff;

            color: #ffffff;

            padding: 8px 18px;

            border-radius: 4px;

            font-family: Arial, sans-serif;

            font-size: 14px;

            cursor: pointer;

        }


        /*
        |--------------------------------------------------------------------------
        | SCREEN
        |--------------------------------------------------------------------------
        */

        @media screen {

            body {

                background: #eeeeee;

            }


            .print-container {

                background: #ffffff;

                margin-top: 20px;

                margin-bottom: 20px;

                box-shadow:
                    0 0 8px
                    rgba(0, 0, 0, 0.15);

            }

        }


        /*
        |--------------------------------------------------------------------------
        | PRINT
        |--------------------------------------------------------------------------
        */

        @media print {

            @page {

                size: A4 portrait;

                margin: 8mm;

            }


            html,
            body {

                width: 100%;

                background: #ffffff;

            }


            .print-container {

                width: 100%;

                max-width: none;

                margin: 0;

                padding: 0;

                box-shadow: none;

            }


            .print-button {

                display: none;

            }


            .department-title {

                -webkit-print-color-adjust: exact;

                print-color-adjust: exact;

            }


            .blue-separator td {

                -webkit-print-color-adjust: exact;

                print-color-adjust: exact;

            }


            .schedule-table th {

                -webkit-print-color-adjust: exact;

                print-color-adjust: exact;

            }


            tr {

                page-break-inside: avoid;

            }

        }

    </style>

</head>


<body>


<div class="print-container">


    <!-- =====================================================
         SCHOOL HEADER
    ====================================================== -->

    <div class="school-header">


        <img
            src="<?php echo $logo; ?>"
            class="school-logo"
            alt="School Logo">


        <div class="school-name">

            <?php
            echo htmlspecialchars(
                $schoolName
            );
            ?>

        </div>


        <div class="school-address">

            <?php
            echo htmlspecialchars(
                $schoolAddress
            );
            ?>

        </div>


        <div class="school-tel">

            <?php
            echo htmlspecialchars(
                $schoolTel
            );
            ?>

        </div>


        <div class="school-email">

            Email:

            <?php
            echo htmlspecialchars(
                $schoolEmail
            );
            ?>

        </div>

    </div>



    <!-- =====================================================
         TITLE
    ====================================================== -->

    <div class="schedule-title">

        SCHEDULE OF CLASSES

    </div>


    <div class="semester-title">

        <?php
        echo htmlspecialchars(
            strtoupper($semester)
        );
        ?>

        A.Y.

        <?php
        echo htmlspecialchars(
            $school_year
        );
        ?>

    </div>


    <div class="section-title">

        <?php
        echo htmlspecialchars(
            $section
        );
        ?>

    </div>


    <div class="department-title">

        <?php
        echo htmlspecialchars(
            $departmentName
        );
        ?>

    </div>



    <!-- =====================================================
         TABLE
    ====================================================== -->

    <table class="schedule-table">


        <thead>

            <tr>

                <th class="time-column">

                    TIME

                </th>


                <th class="day-column">

                    DAY

                </th>


                <th class="code-column">

                    CODE

                </th>


                <th class="subject-column">

                    SUBJECT<br>
                    DESCRIPTION

                </th>


                <th class="unit-column">

                    UNIT<br>
                    S

                </th>


                <th class="room-column">

                    ROOM

                </th>


                <th class="instructor-column">

                    INSTRUCTOR

                </th>

            </tr>

        </thead>


        <tbody>


        <?php

        /*
        |--------------------------------------------------------------------------
        | SCHEDULE ROWS
        |--------------------------------------------------------------------------
        */

        $previousGroup = '';

        $rowCounter = 0;


        foreach ($schedules as $schedule):


            $rowCounter++;


            /*
            |--------------------------------------------------------------------------
            | DAY
            |--------------------------------------------------------------------------
            */

            $dayName =
                trim(
                    $schedule->DAY_NAME
                );


            /*
            |--------------------------------------------------------------------------
            | TIME
            |--------------------------------------------------------------------------
            */

            $startTimestamp =
                strtotime(
                    $schedule->TIME_START
                );


            $endTimestamp =
                strtotime(
                    $schedule->TIME_END
                );


            $startTime =
                date(
                    "g:i",
                    $startTimestamp
                );


            $endTime =
                date(
                    "g:i",
                    $endTimestamp
                );


            $startAMPM =
                date(
                    "A",
                    $startTimestamp
                );


            $endAMPM =
                date(
                    "A",
                    $endTimestamp
                );


            /*
            |--------------------------------------------------------------------------
            | FORMAT TIME LIKE THE REFERENCE
            |--------------------------------------------------------------------------
            */

            if ($startAMPM == $endAMPM) {

                $timeDisplay =
                    $startTime .
                    " - " .
                    $endTime .
                    " " .
                    $endAMPM;

            } else {

                $timeDisplay =
                    $startTime .
                    " " .
                    $startAMPM .
                    " - " .
                    $endTime .
                    " " .
                    $endAMPM;

            }


            /*
            |--------------------------------------------------------------------------
            | DAY DISPLAY
            |--------------------------------------------------------------------------
            */

            $dayDisplay =
                htmlspecialchars(
                    $dayName
                );


            /*
            |--------------------------------------------------------------------------
            | SUBJECT CODE
            |--------------------------------------------------------------------------
            */

            $subjectCode =
                htmlspecialchars(
                    $schedule->SUBJECT_CODE
                );


            /*
            |--------------------------------------------------------------------------
            | SUBJECT NAME
            |--------------------------------------------------------------------------
            */

            $subjectName =
                htmlspecialchars(
                    $schedule->SUBJECT_NAME
                );


            /*
            |--------------------------------------------------------------------------
            | UNITS
            |--------------------------------------------------------------------------
            */

            $units =
                htmlspecialchars(
                    $schedule->UNITS
                );


            /*
            |--------------------------------------------------------------------------
            | ROOM
            |--------------------------------------------------------------------------
            */

            $room =
                htmlspecialchars(
                    $schedule->CLASSROOM_NAME
                );


            /*
            |--------------------------------------------------------------------------
            | INSTRUCTOR
            |--------------------------------------------------------------------------
            */

            $instructor =
                htmlspecialchars(
                    $schedule->INSTRUCTOR_NAME
                );


            /*
            |--------------------------------------------------------------------------
            | BLUE SEPARATOR
            |
            | Add separator whenever the schedule day changes.
            |--------------------------------------------------------------------------
            */

            if (
                $previousGroup != '' &&
                $previousGroup != $dayName
            ):

        ?>

            <tr class="blue-separator">

                <td colspan="7"></td>

            </tr>

        <?php

            endif;


        ?>


            <!-- =================================================
                 SCHEDULE ROW
            ================================================== -->

            <tr>


                <td class="time-cell">

                    <?php
                    echo $timeDisplay;
                    ?>

                </td>


                <td>

                    <?php
                    echo $dayDisplay;
                    ?>

                </td>


                <td>

                    <?php
                    echo $subjectCode;
                    ?>

                </td>


                <td class="subject-description">

                    <?php
                    echo nl2br(
                        $subjectName
                    );
                    ?>

                </td>


                <td>

                    <?php
                    echo $units;
                    ?>

                </td>


                <td>

                    <?php
                    echo $room;
                    ?>

                </td>


                <td>

                    <?php
                    echo $instructor;
                    ?>

                </td>


            </tr>


        <?php


            $previousGroup =
                $dayName;


        endforeach;


        ?>


        <!-- =====================================================
             TOTAL UNITS
        ====================================================== -->

        <tr class="total-row">


            <td colspan="4">

            </td>


            <td>

                <?php
                echo $totalUnits;
                ?>

            </td>


            <td colspan="2">

            </td>


        </tr>


        </tbody>

    </table>



    <!-- =====================================================
         PRINT BUTTON
    ====================================================== -->

    <div class="print-button">

        <button
            type="button"
            onclick="window.print();">

            Print Schedule

        </button>

    </div>


</div>



<script>

window.onload = function () {

    setTimeout(
        function () {

            window.print();

        },
        500
    );

};

</script>


</body>

</html>