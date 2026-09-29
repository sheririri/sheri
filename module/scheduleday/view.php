<?php
// ARSUENTO SOLUTIONS
global $mydb;

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$query = "SELECT * FROM tblscheduleday WHERE id = '".$id."' LIMIT 1";
$mydb->setQuery($query);
$result = $mydb->loadResultList();
$row = (count($result) > 0) ? $result[0] : null;
?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Schedule Day Details</h3>
          </div>
          <div class="card-body">
            <?php if ($row): ?>
              <table class="table table-bordered">
                <tr>
                  <th width="25%">Day Name</th>
                  <td><?php echo htmlspecialchars($row->day_name); ?></td>
                </tr>
                <tr>
                  <th>Description</th>
                  <td><?php echo htmlspecialchars($row->description); ?></td>
                </tr>
              </table>
            <?php else: ?>
              <p>Schedule day not found.</p>
            <?php endif; ?>

            <a href="index.php" class="btn btn-secondary">Back to List</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
