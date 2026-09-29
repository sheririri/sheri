<?php

// ARSUENTO SOLUTIONS

require_once("../../include/initialize.php");
// if (!isset($_SESSION['ACCOUNT_ID'])){
  //    redirect(web_root."/index.php");
//     }

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';
 $title="Schedule Day Module";
 $header=$view;
switch ($view) {
	case 'list' :
		$content    = 'list.php';
		break;

	case 'view' :
		$content    = 'view.php';
		break;

	default :
		$content    = 'list.php';
}
require_once ("../../theme/template.php");

?>

 <script type="text/javascript">
        $(document).ready(function() {
            var t = $('#tblscheduleday').DataTable( {
            "processing":true,
            "serverSide":true,
            "order":[],
            "ajax":{
              url:"<?php echo WEB_ROOT; ?>module/scheduleday/scheduleday_ajax.php",
              type:"POST"
            },
                "columnDefs": [ {
                    "searchable": true,
                    "orderable": true,
                    "targets": 1
                } ],
                //vertical scroll
                 "scrollY":        "400px",
                "scrollCollapse": true,
                //ordering start at column 2
               "order": [[ 2, 'asc' ]]
            } );

                t.on( 'order.dt search.dt', function () {
                t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();

        });
    </script>

  <script type="text/javascript">
  $(document).on('click', '.editEntry', function(){
    var SCHEDULEDAY_ROW_ID = $(this).attr("SCHEDULEDAY_ROW_ID");
    $.ajax({
      url:"<?php echo WEB_ROOT; ?>module/scheduleday/scheduleday_ajax.php",
      method:"POST",
      data:{SCHEDULEDAY_ROW_ID:SCHEDULEDAY_ROW_ID},
      dataType:"json",
      success:function(data)
      {
       $('#editEntry').modal('show');
       $('#SCHEDULEDAY_ROW_ID').val(data.SCHEDULEDAY_ROW_ID);
       $('#DAY_NAME1').val(data.DAY_NAME);
       $('#DESCRIPTION1').val(data.DESCRIPTION);
        $('.modal-title').text("Modify Schedule Day");
      }
    })
  });
</script>

<script type="text/javascript">
  $(document).on('click', '.deleteEntry', function(){
    var SCHEDULEDAY_ROW_ID = $(this).attr("SCHEDULEDAY_ROW_ID");
    Swal.fire({
      title: 'Delete Schedule Day?',
      text: "This action cannot be undone. Are you sure you want to delete this schedule day?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.value) {
        window.location.href = "<?php echo WEB_ROOT; ?>module/scheduleday/controller.php?action=delete&id=" + SCHEDULEDAY_ROW_ID;
      }
    });
  });
</script>
