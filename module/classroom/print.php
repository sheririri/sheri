```php
<?php

require_once("../../include/initialize.php");

global $mydb;


$classroom = null;


if (isset($_GET['id']) && $_GET['id'] != '') {

    $classroom_id = (int)$_GET['id'];


    $mydb->setQuery("
        SELECT *
        FROM tblclassroom
        WHERE id = '".$classroom_id."'
        LIMIT 1
    ");


    $classroom = $mydb->loadSingleResult();

}

?>

<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <title>
        Classroom Information
    </title>


    <style>

        body {

            font-family: Arial, sans-serif;

            margin: 0;

            padding: 20px;

            color: #222;

        }


        .container {

            width: 90%;

            margin: auto;

        }


        .school-header {

            text-align: center;

            margin-bottom: 25px;

        }


        .school-header h2 {

            margin: 0;

            font-size: 22px;

        }


        .school-header p {

            margin: 5px 0;

            font-size: 14px;

        }


        .title {

            text-align: center;

            margin: 20px 0;

        }


        .title h3 {

            margin: 0;

            font-size: 20px;

            text-transform: uppercase;

        }


        .info-table {

            width: 100%;

            border-collapse: collapse;

            margin-top: 20px;

        }


        .info-table th,
        .info-table td {

            border: 1px solid #000;

            padding: 10px;

            text-align: left;

        }


        .info-table th {

            width: 25%;

            background: #f2f2f2;

        }


        .description {

            min-height: 100px;

        }


        .buttons {

            text-align: center;

            margin-top: 30px;

        }


        .btn {

            display: inline-block;

            padding: 8px 15px;

            margin: 3px;

            border: 1px solid #333;

            background: #eee;

            color: #000;

            text-decoration: none;

            cursor: pointer;

        }


        @media print {

            .buttons {

                display: none;

            }


            body {

                padding: 0;

            }

        }

    </style>

</head>


<body>


<div class="container">


    <div class="school-header">

        <h2>
            Colegio de Santa Rita de San Carlos, Inc.
        </h2>

        <p>
            Classroom Information
        </p>

    </div>



    <div class="title">

        <h3>
            Classroom Information
        </h3>

    </div>



    <?php if (!$classroom): ?>


        <table class="info-table">

            <tr>

                <th>
                    Status
                </th>

                <td>
                    No classroom was selected.
                </td>

            </tr>

        </table>


    <?php else: ?>


        <table class="info-table">


            <tr>

                <th>
                    Classroom ID
                </th>

                <td>

                    <?php

                    echo htmlspecialchars(
                        $classroom->id
                    );

                    ?>

                </td>

            </tr>


            <tr>

                <th>
                    Classroom Name
                </th>

                <td>

                    <?php

                    echo htmlspecialchars(
                        $classroom->name
                    );

                    ?>

                </td>

            </tr>


            <tr>

                <th>
                    Description
                </th>

                <td class="description">

                    <?php

                    echo !empty($classroom->description)

                        ? nl2br(
                            htmlspecialchars(
                                $classroom->description
                            )
                        )

                        : 'No description provided.';

                    ?>

                </td>

            </tr>


        </table>


    <?php endif; ?>



    <div class="buttons">

        <button
            type="button"
            class="btn"
            onclick="window.print();">

            Print

        </button>


        <button
            type="button"
            class="btn"
            onclick="window.close();">

            Close

        </button>

    </div>


</div>


<script>

window.onload = function() {

    window.print();

};

</script>


</body>

</html>
```
