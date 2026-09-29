<?php

global $mydb;

?>

<section class="content">

    <div class="container-fluid">

        <?php check_message(); ?>

        <div class="row">

            <div class="col-12">

                <div class="card">

                    <div class="card-header">

                        <h3 class="card-title">
                            Set Schedule List
                        </h3>

                    </div>


                    <div class="card-body">

                        <table
                            id="tblsetschedule"
                            class="table table-bordered table-striped"
                            style="width:100%;">

                            <thead>

                                <tr>

                                    <th>#</th>

                                    <th>TIME</th>

                                    <th>DAY</th>

                                    <th>CODE</th>

                                    <th>SUBJECT DESCRIPTION</th>

                                    <th>UNIT</th>

                                    <th>ROOM</th>

                                    <th>INSTRUCTOR</th>

                                    <th>Action</th>

                                </tr>

                            </thead>

                            <tbody>
                            </tbody>

                            <tfoot>
                            </tfoot>

                        </table>


                        <div class="btn-group">

                            <button
                                type="button"
                                class="btn btn-primary"
                                data-toggle="modal"
                                data-target="#AddNewEntry">

                                Add New

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =====================================================
     ADD SCHEDULE
===================================================== -->

<div class="modal fade" id="AddNewEntry">

    <div class="modal-dialog modal-lg">

        <form
            action="controller.php?action=add"
            method="POST">

            <div class="modal-content">


                <div class="modal-header">

                    <h4 class="modal-title">
                        Add New Schedule
                    </h4>

                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>


                <div class="modal-body">

                    <div class="row">


                        <!-- DEPARTMENT -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-form-label col-form-label-sm">
                                    Department
                                </label>

                                <select
                                    name="DEPARTMENT_ID"
                                    class="form-control form-control-sm"
                                    required>

                                    <option value="">
                                        Select Department
                                    </option>

                                    <?php

                                    $mydb->setQuery("
                                        SELECT id, name
                                        FROM tbldepartment
                                        ORDER BY name ASC
                                    ");

                                    $departments =
                                        $mydb->loadResultList();

                                    foreach ($departments as $department):

                                    ?>

                                        <option
                                            value="<?php echo $department->id; ?>">

                                            <?php
                                            echo htmlspecialchars(
                                                $department->name
                                            );
                                            ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>


                        <!-- SUBJECT -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-form-label col-form-label-sm">
                                    Subject
                                </label>

                                <select
                                    name="SUBJECT_ID"
                                    class="form-control form-control-sm"
                                    required>

                                    <option value="">
                                        Select Subject
                                    </option>

                                    <?php

                                    $mydb->setQuery("
                                        SELECT
                                            SUBJECT_ID,
                                            SUBJECT_CODE,
                                            SUBJECT_NAME
                                        FROM tblsubjects
                                        ORDER BY SUBJECT_CODE ASC
                                    ");

                                    $subjects =
                                        $mydb->loadResultList();

                                    foreach ($subjects as $subject):

                                    ?>

                                        <option
                                            value="<?php echo $subject->SUBJECT_ID; ?>">

                                            <?php
                                            echo htmlspecialchars(
                                                $subject->SUBJECT_CODE
                                                . " - "
                                                . $subject->SUBJECT_NAME
                                            );
                                            ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>


                        <!-- CLASSROOM -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-form-label col-form-label-sm">
                                    Classroom
                                </label>

                                <select
                                    name="CLASSROOM_ID"
                                    class="form-control form-control-sm"
                                    required>

                                    <option value="">
                                        Select Classroom
                                    </option>

                                    <?php

                                    $mydb->setQuery("
                                        SELECT id, name
                                        FROM tblclassroom
                                        ORDER BY name ASC
                                    ");

                                    $classrooms =
                                        $mydb->loadResultList();

                                    foreach ($classrooms as $classroom):

                                    ?>

                                        <option
                                            value="<?php echo $classroom->id; ?>">

                                            <?php
                                            echo htmlspecialchars(
                                                $classroom->name
                                            );
                                            ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>


                        <!-- DAY -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-form-label col-form-label-sm">
                                    Schedule Day
                                </label>

                                <select
                                    name="DAY_ID"
                                    class="form-control form-control-sm"
                                    required>

                                    <option value="">
                                        Select Day
                                    </option>

                                    <?php

                                    $mydb->setQuery("
                                        SELECT id, name
                                        FROM tblscheduleday
                                        ORDER BY id ASC
                                    ");

                                    $days =
                                        $mydb->loadResultList();

                                    foreach ($days as $day):

                                    ?>

                                        <option
                                            value="<?php echo $day->id; ?>">

                                            <?php
                                            echo htmlspecialchars(
                                                $day->name
                                            );
                                            ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>


                        <!-- TIME -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-form-label col-form-label-sm">
                                    Schedule Time
                                </label>

                                <select
                                    name="TIME_ID"
                                    class="form-control form-control-sm"
                                    required>

                                    <option value="">
                                        Select Time
                                    </option>

                                    <?php

                                    $mydb->setQuery("
                                        SELECT
                                            id,
                                            time_start,
                                            time_end,
                                            description
                                        FROM tblscheduletime
                                        ORDER BY time_start ASC
                                    ");

                                    $times =
                                        $mydb->loadResultList();

                                    foreach ($times as $time):

                                    ?>

                                        <option
                                            value="<?php echo $time->id; ?>">

                                            <?php

                                            echo date(
                                                "h:i A",
                                                strtotime($time->time_start)
                                            );

                                            echo " - ";

                                            echo date(
                                                "h:i A",
                                                strtotime($time->time_end)
                                            );

                                            if (!empty($time->description)) {

                                                echo " - "
                                                    . htmlspecialchars(
                                                        $time->description
                                                    );

                                            }

                                            ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>


                        <!-- INSTRUCTOR -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-form-label col-form-label-sm">
                                    Instructor
                                </label>

                                <select
                                    name="INSTRUCTOR_ID"
                                    class="form-control form-control-sm">

                                    <option value="">
                                        Select Instructor
                                    </option>

                                    <?php

                                    $mydb->setQuery("
                                        SELECT id, name
                                        FROM tblinstructor
                                        ORDER BY name ASC
                                    ");

                                    $instructors =
                                        $mydb->loadResultList();

                                    foreach ($instructors as $instructor):

                                    ?>

                                        <option
                                            value="<?php echo $instructor->id; ?>">

                                            <?php
                                            echo htmlspecialchars(
                                                $instructor->name
                                            );
                                            ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>


                        <!-- SEMESTER -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-form-label col-form-label-sm">
                                    Semester
                                </label>

                                <select
                                    name="SEMESTER"
                                    class="form-control form-control-sm"
                                    required>

                                    <option value="">
                                        Select Semester
                                    </option>

                                    <option value="1st Semester">
                                        1st Semester
                                    </option>

                                    <option value="2nd Semester">
                                        2nd Semester
                                    </option>

                                    <option value="Summer">
                                        Summer
                                    </option>

                                </select>

                            </div>

                        </div>


                        <!-- SCHOOL YEAR -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-form-label col-form-label-sm">
                                    School Year
                                </label>

                                <input
                                    type="text"
                                    name="SCHOOL_YEAR"
                                    class="form-control form-control-sm"
                                    placeholder="2026-2027"
                                    required>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="modal-footer justify-content-between">

                    <button
                        type="button"
                        class="btn btn-default"
                        data-dismiss="modal">

                        Close

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        name="save">

                        Save Schedule

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


<!-- =====================================================
     EDIT SCHEDULE
===================================================== -->

<div class="modal fade" id="editEntry">

    <div class="modal-dialog modal-lg">

        <form
            action="controller.php?action=edit"
            method="POST">

            <div class="modal-content">


                <div class="modal-header">

                    <h4 class="modal-title">
                        Modify Schedule
                    </h4>

                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal">

                        <span>&times;</span>

                    </button>

                </div>


                <div class="modal-body">

                    <input
                        type="hidden"
                        name="ID"
                        id="EDIT_ID">


                    <div class="row">


                        <!-- DEPARTMENT -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-form-label col-form-label-sm">
                                    Department
                                </label>

                                <select
                                    name="DEPARTMENT_ID"
                                    id="EDIT_DEPARTMENT_ID"
                                    class="form-control form-control-sm"
                                    required>

                                    <option value="">
                                        Select Department
                                    </option>

                                    <?php

                                    $mydb->setQuery("
                                        SELECT id, name
                                        FROM tbldepartment
                                        ORDER BY name ASC
                                    ");

                                    $departments =
                                        $mydb->loadResultList();

                                    foreach ($departments as $department):

                                    ?>

                                        <option
                                            value="<?php echo $department->id; ?>">

                                            <?php
                                            echo htmlspecialchars(
                                                $department->name
                                            );
                                            ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>


                        <!-- SUBJECT -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-form-label col-form-label-sm">
                                    Subject
                                </label>

                                <select
                                    name="SUBJECT_ID"
                                    id="EDIT_SUBJECT_ID"
                                    class="form-control form-control-sm"
                                    required>

                                    <option value="">
                                        Select Subject
                                    </option>

                                    <?php

                                    $mydb->setQuery("
                                        SELECT
                                            SUBJECT_ID,
                                            SUBJECT_CODE,
                                            SUBJECT_NAME
                                        FROM tblsubjects
                                        ORDER BY SUBJECT_CODE ASC
                                    ");

                                    $subjects =
                                        $mydb->loadResultList();

                                    foreach ($subjects as $subject):

                                    ?>

                                        <option
                                            value="<?php echo $subject->SUBJECT_ID; ?>">

                                            <?php
                                            echo htmlspecialchars(
                                                $subject->SUBJECT_CODE
                                                . " - "
                                                . $subject->SUBJECT_NAME
                                            );
                                            ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>


                        <!-- CLASSROOM -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-form-label col-form-label-sm">
                                    Classroom
                                </label>

                                <select
                                    name="CLASSROOM_ID"
                                    id="EDIT_CLASSROOM_ID"
                                    class="form-control form-control-sm"
                                    required>

                                    <option value="">
                                        Select Classroom
                                    </option>

                                    <?php

                                    $mydb->setQuery("
                                        SELECT id, name
                                        FROM tblclassroom
                                        ORDER BY name ASC
                                    ");

                                    $classrooms =
                                        $mydb->loadResultList();

                                    foreach ($classrooms as $classroom):

                                    ?>

                                        <option
                                            value="<?php echo $classroom->id; ?>">

                                            <?php
                                            echo htmlspecialchars(
                                                $classroom->name
                                            );
                                            ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>


                        <!-- DAY -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-form-label col-form-label-sm">
                                    Schedule Day
                                </label>

                                <select
                                    name="DAY_ID"
                                    id="EDIT_DAY_ID"
                                    class="form-control form-control-sm"
                                    required>

                                    <option value="">
                                        Select Day
                                    </option>

                                    <?php

                                    $mydb->setQuery("
                                        SELECT id, name
                                        FROM tblscheduleday
                                        ORDER BY id ASC
                                    ");

                                    $days =
                                        $mydb->loadResultList();

                                    foreach ($days as $day):

                                    ?>

                                        <option
                                            value="<?php echo $day->id; ?>">

                                            <?php
                                            echo htmlspecialchars(
                                                $day->name
                                            );
                                            ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>


                        <!-- TIME -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-form-label col-form-label-sm">
                                    Schedule Time
                                </label>

                                <select
                                    name="TIME_ID"
                                    id="EDIT_TIME_ID"
                                    class="form-control form-control-sm"
                                    required>

                                    <option value="">
                                        Select Time
                                    </option>

                                    <?php

                                    $mydb->setQuery("
                                        SELECT
                                            id,
                                            time_start,
                                            time_end,
                                            description
                                        FROM tblscheduletime
                                        ORDER BY time_start ASC
                                    ");

                                    $times =
                                        $mydb->loadResultList();

                                    foreach ($times as $time):

                                    ?>

                                        <option
                                            value="<?php echo $time->id; ?>">

                                            <?php

                                            echo date(
                                                "h:i A",
                                                strtotime($time->time_start)
                                            );

                                            echo " - ";

                                            echo date(
                                                "h:i A",
                                                strtotime($time->time_end)
                                            );

                                            ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>


                        <!-- INSTRUCTOR -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-form-label col-form-label-sm">
                                    Instructor
                                </label>

                                <select
                                    name="INSTRUCTOR_ID"
                                    id="EDIT_INSTRUCTOR_ID"
                                    class="form-control form-control-sm">

                                    <option value="">
                                        Select Instructor
                                    </option>

                                    <?php

                                    $mydb->setQuery("
                                        SELECT id, name
                                        FROM tblinstructor
                                        ORDER BY name ASC
                                    ");

                                    $instructors =
                                        $mydb->loadResultList();

                                    foreach ($instructors as $instructor):

                                    ?>

                                        <option
                                            value="<?php echo $instructor->id; ?>">

                                            <?php
                                            echo htmlspecialchars(
                                                $instructor->name
                                            );
                                            ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                        </div>


                        <!-- SEMESTER -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-form-label col-form-label-sm">
                                    Semester
                                </label>

                                <select
                                    name="SEMESTER"
                                    id="EDIT_SEMESTER"
                                    class="form-control form-control-sm"
                                    required>

                                    <option value="">
                                        Select Semester
                                    </option>

                                    <option value="1st Semester">
                                        1st Semester
                                    </option>

                                    <option value="2nd Semester">
                                        2nd Semester
                                    </option>

                                    <option value="Summer">
                                        Summer
                                    </option>

                                </select>

                            </div>

                        </div>


                        <!-- SCHOOL YEAR -->

                        <div class="col-md-6">

                            <div class="form-group">

                                <label class="col-form-label col-form-label-sm">
                                    School Year
                                </label>

                                <input
                                    type="text"
                                    name="SCHOOL_YEAR"
                                    id="EDIT_SCHOOL_YEAR"
                                    class="form-control form-control-sm"
                                    required>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="modal-footer justify-content-between">

                    <button
                        type="button"
                        class="btn btn-default"
                        data-dismiss="modal">

                        Close

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        name="edit">

                        Update Schedule

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>