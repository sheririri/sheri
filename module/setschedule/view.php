<?php

$setschedule = null;

if (isset($_GET['id']) && $_GET['id'] != '') {

    $ID = (int)$_GET['id'];

    $mydb->setQuery("
        SELECT

            ss.id,

            ss.department_id,
            ss.subject_id,
            ss.classroom_id,
            ss.day_id,
            ss.time_id,
            ss.instructor_id,

            ss.semester,
            ss.school_year,

            d.name AS DEPARTMENT_NAME,

            sub.SUBJECT_CODE,
            sub.SUBJECT_NAME,
            sub.UNITS,

            c.name AS CLASSROOM_NAME,

            sd.name AS DAY_NAME,

            st.time_start,
            st.time_end,

            i.name AS INSTRUCTOR_NAME

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

        WHERE ss.id = '".$ID."'

        LIMIT 1
    ");

    $setschedule = $mydb->loadSingleResult();

}

?>



<section class="content">

    <div class="container-fluid">


        <?php if (!$setschedule): ?>


            <div class="alert alert-warning">

                No schedule was selected.

                Please go back to the

                <a
                    href="<?php echo WEB_ROOT; ?>module/setschedule/">

                    Set Schedule List

                </a>

                and click the view button of a schedule.

            </div>


        <?php else: ?>


            <div class="row">


                <!-- ================================================= -->
                <!-- LEFT SIDE -->
                <!-- ================================================= -->

                <div class="col-md-4">


                    <!-- SCHEDULE PROFILE -->

                    <div class="card card-primary card-outline">

                        <div class="card-body box-profile">


                            <div class="text-center">

                                <i
                                    class="fa fa-calendar"
                                    style="
                                        font-size:64px;
                                        color:#8B0000;
                                    "
                                ></i>

                            </div>


                            <h3 class="profile-username text-center">

                                <?php
                                echo htmlspecialchars(
                                    $setschedule->SUBJECT_CODE
                                );
                                ?>

                            </h3>


                            <p class="text-muted text-center">

                                <?php
                                echo htmlspecialchars(
                                    $setschedule->SUBJECT_NAME
                                );
                                ?>

                            </p>


                            <ul
                                class="
                                    list-group
                                    list-group-unbordered
                                    mb-3
                                "
                            >


                                <li class="list-group-item">

                                    <b>Day</b>

                                    <span class="float-right">

                                        <?php
                                        echo htmlspecialchars(
                                            $setschedule->DAY_NAME
                                        );
                                        ?>

                                    </span>

                                </li>


                                <li class="list-group-item">

                                    <b>Time</b>

                                    <span class="float-right">

                                        <?php

                                        echo date(
                                            'h:i A',
                                            strtotime(
                                                $setschedule->time_start
                                            )
                                        );

                                        echo ' - ';

                                        echo date(
                                            'h:i A',
                                            strtotime(
                                                $setschedule->time_end
                                            )
                                        );

                                        ?>

                                    </span>

                                </li>


                                <li class="list-group-item">

                                    <b>Room</b>

                                    <span class="float-right">

                                        <?php

                                        echo $setschedule->CLASSROOM_NAME
                                            ? htmlspecialchars(
                                                $setschedule->CLASSROOM_NAME
                                            )
                                            : 'Not Assigned';

                                        ?>

                                    </span>

                                </li>


                                <li class="list-group-item">

                                    <b>Instructor</b>

                                    <span class="float-right">

                                        <?php

                                        echo $setschedule->INSTRUCTOR_NAME
                                            ? htmlspecialchars(
                                                $setschedule->INSTRUCTOR_NAME
                                            )
                                            : 'Not Assigned';

                                        ?>

                                    </span>

                                </li>


                                <li class="list-group-item">

                                    <b>Units</b>

                                    <span class="float-right">

                                        <?php
                                        echo htmlspecialchars(
                                            $setschedule->UNITS
                                        );
                                        ?>

                                    </span>

                                </li>


                                <li class="list-group-item">

                                    <b>Semester</b>

                                    <span class="float-right">

                                        <?php
                                        echo htmlspecialchars(
                                            $setschedule->semester
                                        );
                                        ?>

                                    </span>

                                </li>


                                <li class="list-group-item">

                                    <b>School Year</b>

                                    <span class="float-right">

                                        <?php
                                        echo htmlspecialchars(
                                            $setschedule->school_year
                                        );
                                        ?>

                                    </span>

                                </li>


                            </ul>


                            <a
                                href="<?php echo WEB_ROOT; ?>module/setschedule/"
                                class="btn btn-primary btn-block"
                            >

                                <b>Back to List</b>

                            </a>


                        </div>

                    </div>



                    <!-- DEPARTMENT -->

                    <div class="card card-secondary card-outline">

                        <div class="card-header">

                            <h3 class="card-title">

                                Department

                            </h3>

                        </div>


                        <div class="card-body">

                            <p class="text-muted">

                                <?php

                                echo $setschedule->DEPARTMENT_NAME
                                    ? htmlspecialchars(
                                        $setschedule->DEPARTMENT_NAME
                                    )
                                    : 'No department assigned.';

                                ?>

                            </p>

                        </div>

                    </div>


                </div>



                <!-- ================================================= -->
                <!-- RIGHT SIDE -->
                <!-- ================================================= -->

                <div class="col-md-8">


                    <div class="card">


                        <div class="card-header">

                            <h3 class="card-title">

                                <i class="fa fa-calendar"></i>

                                Schedule Information

                            </h3>

                        </div>


                        <div class="card-body">


                            <table class="table table-bordered">


                                <tr>

                                    <th width="30%">
                                        Schedule ID
                                    </th>

                                    <td>

                                        <?php
                                        echo htmlspecialchars(
                                            $setschedule->id
                                        );
                                        ?>

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Department
                                    </th>

                                    <td>

                                        <?php
                                        echo htmlspecialchars(
                                            $setschedule->DEPARTMENT_NAME
                                        );
                                        ?>

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Subject Code
                                    </th>

                                    <td>

                                        <?php
                                        echo htmlspecialchars(
                                            $setschedule->SUBJECT_CODE
                                        );
                                        ?>

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Subject Description
                                    </th>

                                    <td>

                                        <?php
                                        echo htmlspecialchars(
                                            $setschedule->SUBJECT_NAME
                                        );
                                        ?>

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Units
                                    </th>

                                    <td>

                                        <?php
                                        echo htmlspecialchars(
                                            $setschedule->UNITS
                                        );
                                        ?>

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Classroom
                                    </th>

                                    <td>

                                        <?php
                                        echo htmlspecialchars(
                                            $setschedule->CLASSROOM_NAME
                                        );
                                        ?>

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Schedule Day
                                    </th>

                                    <td>

                                        <?php
                                        echo htmlspecialchars(
                                            $setschedule->DAY_NAME
                                        );
                                        ?>

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Schedule Time
                                    </th>

                                    <td>

                                        <?php

                                        echo date(
                                            'h:i A',
                                            strtotime(
                                                $setschedule->time_start
                                            )
                                        );

                                        echo ' - ';

                                        echo date(
                                            'h:i A',
                                            strtotime(
                                                $setschedule->time_end
                                            )
                                        );

                                        ?>

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Instructor
                                    </th>

                                    <td>

                                        <?php

                                        echo $setschedule->INSTRUCTOR_NAME
                                            ? htmlspecialchars(
                                                $setschedule->INSTRUCTOR_NAME
                                            )
                                            : 'Not Assigned';

                                        ?>

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        Semester
                                    </th>

                                    <td>

                                        <?php
                                        echo htmlspecialchars(
                                            $setschedule->semester
                                        );
                                        ?>

                                    </td>

                                </tr>


                                <tr>

                                    <th>
                                        School Year
                                    </th>

                                    <td>

                                        <?php
                                        echo htmlspecialchars(
                                            $setschedule->school_year
                                        );
                                        ?>

                                    </td>

                                </tr>


                            </table>


                        </div>

                    </div>



                    <!-- SCHEDULE SUMMARY -->

                    <div class="card card-info card-outline">


                        <div class="card-header">

                            <h3 class="card-title">

                                <i class="fa fa-clock-o"></i>

                                Schedule Summary

                            </h3>

                        </div>


                        <div class="card-body">


                            <div class="row">


                                <div class="col-md-4">

                                    <strong>

                                        <i class="fa fa-calendar"></i>

                                        Day

                                    </strong>


                                    <p class="text-muted">

                                        <?php
                                        echo htmlspecialchars(
                                            $setschedule->DAY_NAME
                                        );
                                        ?>

                                    </p>

                                </div>



                                <div class="col-md-4">

                                    <strong>

                                        <i class="fa fa-clock-o"></i>

                                        Time

                                    </strong>


                                    <p class="text-muted">

                                        <?php

                                        echo date(
                                            'h:i A',
                                            strtotime(
                                                $setschedule->time_start
                                            )
                                        );

                                        echo ' - ';

                                        echo date(
                                            'h:i A',
                                            strtotime(
                                                $setschedule->time_end
                                            )
                                        );

                                        ?>

                                    </p>

                                </div>



                                <div class="col-md-4">

                                    <strong>

                                        <i class="fa fa-building"></i>

                                        Room

                                    </strong>


                                    <p class="text-muted">

                                        <?php

                                        echo $setschedule->CLASSROOM_NAME
                                            ? htmlspecialchars(
                                                $setschedule->CLASSROOM_NAME
                                            )
                                            : 'Not Assigned';

                                        ?>

                                    </p>

                                </div>


                            </div>


                        </div>

                    </div>


                </div>


            </div>


        <?php endif; ?>


    </div>

</section>