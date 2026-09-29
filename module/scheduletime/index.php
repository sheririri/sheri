<?php

// ARSUENTO SOLUTIONS

require_once("../../include/initialize.php");
// if (!isset($_SESSION['ACCOUNT_ID'])){
  //    redirect(web_root."/index.php");
//     }

$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';
 $title="Schedule Time Module";
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
            var t = $('#tblscheduletime').DataTable( {
            "processing":true,
            "serverSide":true,
            "order":[],
            "ajax":{
              url:"<?php echo WEB_ROOT; ?>module/scheduletime/scheduletime_ajax.php",
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
    var SCHEDULETIME_ROW_ID = $(this).attr("SCHEDULETIME_ROW_ID");
    $.ajax({
      url:"<?php echo WEB_ROOT; ?>module/scheduletime/scheduletime_ajax.php",
      method:"POST",
      data:{SCHEDULETIME_ROW_ID:SCHEDULETIME_ROW_ID},
      dataType:"json",
      success:function(data)
      {
       $('#editEntry').modal('show');
       $('#SCHEDULETIME_ROW_ID').val(data.SCHEDULETIME_ROW_ID);
       $('#TIME_START1').val(data.TIME_START);
       $('#TIME_END1').val(data.TIME_END);
       $('#DESCRIPTION1').val(data.DESCRIPTION);
        $('.modal-title').text("Modify Schedule Time");
      }
    })
  });
</script>

<script type="text/javascript">
  $(document).on('click', '.deleteEntry', function(){
    var SCHEDULETIME_ROW_ID = $(this).attr("SCHEDULETIME_ROW_ID");
    Swal.fire({
      title: 'Delete Schedule Time?',
      text: "This action cannot be undone. Are you sure you want to delete this schedule time?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.value) {
        window.location.href = "<?php echo WEB_ROOT; ?>module/scheduletime/controller.php?action=delete&id=" + SCHEDULETIME_ROW_ID;
      }
    });
  });
</script>
