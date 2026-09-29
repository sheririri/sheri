<?php

require_once("../../include/initialize.php");

global $mydb;


$id = isset($_GET['id'])
    ? intval($_GET['id'])
    : 0;


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


$instructor = $mydb->loadSingleResult();


if (!$instructor) {

    echo "Instructor not found.";

    exit;

}

?>

<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <title>
        Instructor Information
    </title>

    <style>

        body {

            font-family: Arial, sans-serif;

            margin: 40px;

        }

        .header {

            text-align: center;

            margin-bottom: 30px;

        }

        .header h2 {

            margin-bottom: 5px;

        }

        table {

            width: 100%;

            border-collapse: collapse;

        }

        th,
        td {

            border: 1px solid #000;

            padding: 10px;

            text-align: left;

        }

        th {

            width: 30%;

        }

        .print-button {

            margin-top: 20px;

            padding: 8px 15px;

            cursor: pointer;

        }

        @media print {

            .print-button {

                display: none;

            }

        }

    </style>

</head>


<body>


<div class="header">

    <h2>Instructor Information</h2>

    <p>Instructor Module</p>

</div>


<table>

    <tr>

        <th>
            Instructor ID
        </th>

        <td>
            <?php echo htmlspecialchars($instructor->instructor_id); ?>
        </td>

    </tr>


    <tr>

        <th>
            Instructor Name
        </th>

        <td>
            <?php echo htmlspecialchars($instructor->name); ?>
        </td>

    </tr>


    <tr>

        <th>
            Description
        </th>

        <td>
            <?php echo htmlspecialchars($instructor->description); ?>
        </td>

    </tr>

</table>


<button class="print-button"
        onclick="window.print();">

    Print

</button>


</body>

</html>