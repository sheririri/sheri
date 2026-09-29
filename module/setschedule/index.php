<?php

require_once("../../include/initialize.php");

$view = (isset($_GET['view']) && $_GET['view'] != '')
    ? $_GET['view']
    : '';

$title = "Set Schedule Module";

$header = $view;


switch ($view) {

    case 'list':

        $content = 'list.php';

        break;


    case 'view':

        $content = 'view.php';

        break;


    default:

        $content = 'list.php';

        break;

}


require_once("../../theme/template.php");

?>



<script type="text/javascript">

$(document).ready(function() {


    // ============================================================
    // SET SCHEDULE DATATABLE
    // ============================================================

    var t = $('#tblsetschedule').DataTable({

        "processing": true,

        "serverSide": true,

        "ajax": {

            "url":
                "<?php echo WEB_ROOT; ?>module/setschedule/ajax.php",

            "type": "POST",

            "error": function(xhr) {

                console.log(
                    "Set Schedule AJAX Error:"
                );

                console.log(
                    xhr.responseText
                );

            }

        },

        "order": [],

        "columnDefs": [

            {
                "searchable": false,
                "orderable": false,
                "targets": 0
            },

            {
                "searchable": false,
                "orderable": false,
                "targets": 8
            }

        ],

        "scrollX": true,

        "scrollY": "400px",

        "scrollCollapse": true,

        "order": [
            [1, "asc"]
        ]

    });



    // ============================================================
    // NUMBER COLUMN
    // ============================================================

    t.on(
        'order.dt search.dt',
        function() {

            t.column(
                0,
                {
                    search: 'applied',
                    order: 'applied'
                }
            )
            .nodes()
            .each(
                function(cell, i) {

                    cell.innerHTML = i + 1;

                }
            );

        }
    ).draw();



    // ============================================================
    // EDIT
    // ============================================================

    $(document).on(
        'click',
        '.editEntry',
        function() {

            var ID = $(this).attr("ID");


            $.ajax({

                url:
                    "<?php echo WEB_ROOT; ?>module/setschedule/ajax.php",

                method: "POST",

                data: {
                    ID: ID
                },

                dataType: "json",


                success: function(data) {


                    if (!data || data.error) {

                        Swal.fire(
                            'Error',
                            data.error ||
                            'Unable to load schedule.',
                            'error'
                        );

                        return;

                    }


                    $('#EDIT_ID')
                        .val(data.id);


                    $('#EDIT_DEPARTMENT_ID')
                        .val(data.DEPARTMENT_ID);


                    $('#EDIT_SUBJECT_ID')
                        .val(data.SUBJECT_ID);


                    $('#EDIT_CLASSROOM_ID')
                        .val(data.CLASSROOM_ID);


                    $('#EDIT_DAY_ID')
                        .val(data.DAY_ID);


                    $('#EDIT_TIME_ID')
                        .val(data.TIME_ID);


                    $('#EDIT_INSTRUCTOR_ID')
                        .val(data.INSTRUCTOR_ID);


                    $('#EDIT_SEMESTER')
                        .val(data.SEMESTER);


                    $('#EDIT_SCHOOL_YEAR')
                        .val(data.SCHOOL_YEAR);


                    $('#editEntry')
                        .modal('show');

                },


                error: function(xhr) {

                    console.log(
                        xhr.responseText
                    );


                    Swal.fire(
                        'Error',
                        'Unable to load schedule information.',
                        'error'
                    );

                }

            });

        }
    );



    // ============================================================
    // DELETE
    // ============================================================

    $(document).on(
        'click',
        '.deleteEntry',
        function() {


            var ID = $(this).attr("ID");


            Swal.fire({

                title: 'Delete Schedule?',

                text:
                    'This action cannot be undone.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonText:
                    'Yes, delete it',

                cancelButtonText:
                    'Cancel'

            }).then(function(result) {


                if (result.value) {


                    window.location.href =
                        "<?php echo WEB_ROOT; ?>module/setschedule/controller.php?action=delete&id="
                        + ID;


                }

            });


        }
    );


});

</script>