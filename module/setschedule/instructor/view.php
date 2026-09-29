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

    message("Instructor not found.", "error");

    redirect(WEB_ROOT . "module/instructor/index.php?view=list");

}

?>


<section class="content">

    <div class="container-fluid">

        <?php check_message(); ?>

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header">

                        <h3 class="card-title">
                            Instructor Information
                        </h3>

                    </div>


                    <div class="card-body">

                        <table class="table table-bordered table-striped">

                            <tr>

                                <th style="width: 25%;">
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


                        <br>


                        <div class="btn-group">

                            <a href="<?php echo WEB_ROOT; ?>module/instructor/index.php?view=list"
                               class="btn btn-default">

                                <i class="fas fa-arrow-left"></i>
                                Back

                            </a>


                            <a href="<?php echo WEB_ROOT; ?>module/instructor/print.php?id=<?php echo $instructor->id; ?>"
                               target="_blank"
                               class="btn btn-secondary">

                                <i class="fas fa-print"></i>
                                Print

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>