<?php

require_once("../../include/initialize.php");

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';

$title = "Instructor Module";
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

    var t = $('#tblinstructor').DataTable({

        "processing": true,

        "serverSide": true,

        "order": [],

        "ajax": {
            url: "<?php echo WEB_ROOT; ?>module/instructor/ajax.php",
            type: "POST"
        },

        "columnDefs": [
            {
                "searchable": false,
                "orderable": false,
                "targets": 0
            },
            {
                "searchable": false,
                "orderable": false,
                "targets": 4
            }
        ],

        "scrollX": true,

        "scrollY": "400px",

        "scrollCollapse": true,

        "order": [[1, 'asc']]
    });


    t.on('order.dt search.dt', function() {

        t.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {

            cell.innerHTML = i + 1;

        });

    }).draw();


});


/* ADD NEW */

$(document).on('click', '#btnAddNewInstructor', function(e) {

    e.preventDefault();

    $('#AddNewEntry').modal('show');

});


/* EDIT */

$(document).on('click', '.editEntry', function() {

    var ID = $(this).attr("ID");

    $.ajax({

        url: "<?php echo WEB_ROOT; ?>module/instructor/ajax.php",

        method: "POST",

        data: {
            ID: ID
        },

        dataType: "json",

        success: function(data) {

            $('#editEntry').modal('show');

            $('#EDIT_ID').val(data.ID);

            $('#EDIT_INSTRUCTOR_ID').val(data.INSTRUCTOR_ID);

            $('#EDIT_NAME').val(data.NAME);

            $('#EDIT_DESCRIPTION').val(data.DESCRIPTION);

        },

        error: function() {

            Swal.fire(
                'Error',
                'Unable to load instructor information.',
                'error'
            );

        }

    });

});


/* DELETE */

$(document).on('click', '.deleteEntry', function() {

    var ID = $(this).attr("ID");

    Swal.fire({

        title: 'Delete Instructor?',

        text: 'This action cannot be undone. Are you sure you want to delete this instructor?',

        icon: 'warning',

        showCancelButton: true,

        confirmButtonText: 'Yes, delete it',

        cancelButtonText: 'Cancel'

    }).then((result) => {

        if (result.value) {

            window.location.href =
                "<?php echo WEB_ROOT; ?>module/instructor/controller.php?action=delete&id="
                + ID;

        }

    });

});

</script>