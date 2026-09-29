<section class="content">

   <!-- ARSUENTO SOLUTIONS -->

      <div class="container-fluid">
         <?php check_message(); ?>
        <div class="row">
          <div class="col-12">

            <div class="card">
              <div class="card-header">
                <h3 class="card-title">List of Schedule Times</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="tblscheduletime" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th width="5%">#</th>
                    <th>Time Start</th>
                    <th>Time End</th>
                    <th>Description</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>

                  </tbody>
                  <tfoot>

                  </tfoot>
                </table>
                  <div class="btn-group">

                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#AddNewEntry">Add New</button>
                  <a href="print.php" target="_blank" class="btn btn-success"><i class="fas fa-print"></i> Print All Schedule Times</a>


                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>

<!-----START of Add Form---->
     <div class="modal fade" id="AddNewEntry">
        <div class="modal-dialog">
        <form action="controller.php?action=add" method="POST">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Add New Schedule Time</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <!-- /.card-header -->
              <div class="row">

                    <div class="col-sm-12">
                      <div class="form-group">
                       <label for="TIME_START"  class="col-form-label col-form-label-sm">Time Start</label>
                        <input type="time" class="form-control form-control-sm" name="TIME_START"
                        id="TIME_START" required>
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="form-group">
                       <label for="TIME_END"  class="col-form-label col-form-label-sm">Time End</label>
                        <input type="time" class="form-control form-control-sm" name="TIME_END"
                        id="TIME_END" required>
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="form-group">
                       <label for="DESCRIPTION"  class="col-form-label col-form-label-sm">Description</label>
                        <textarea class="form-control form-control-sm" name="DESCRIPTION"
                        id="DESCRIPTION" placeholder="Short description"></textarea>
                      </div>
                    </div>

                  </div>

              <!-- /.card-body -->

            </div>
            <div class="modal-footer justify-content-between">
             <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                 <button type="submit" class="btn btn-primary" name="save" type="submit">Save changes</button>

            </div>
          </div>
          </form>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>

<!-----End of Add Form---->
<!-----Start of edit Form---->
<div class="modal fade" id="editEntry">
        <div class="modal-dialog">
        <form action="controller.php?action=edit" method="POST">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Modify Schedule Time</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <!-- /.card-header -->
              <div class="row">
                    <input type="hidden" name="SCHEDULETIME_ROW_ID" id="SCHEDULETIME_ROW_ID">
                    <div class="col-sm-12">
                      <div class="form-group">
                       <label for="TIME_START1"  class="col-form-label col-form-label-sm">Time Start</label>
                        <input type="time" class="form-control form-control-sm" name="TIME_START1"
                        id="TIME_START1">
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="form-group">
                       <label for="TIME_END1"  class="col-form-label col-form-label-sm">Time End</label>
                        <input type="time" class="form-control form-control-sm" name="TIME_END1"
                        id="TIME_END1">
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="form-group">
                       <label for="DESCRIPTION1"  class="col-form-label col-form-label-sm">Description</label>
                        <textarea class="form-control form-control-sm" name="DESCRIPTION1"
                        id="DESCRIPTION1" placeholder="Short description"></textarea>
                      </div>
                    </div>

                  </div>

              <!-- /.card-body -->

            </div>
            <div class="modal-footer justify-content-between">
             <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                 <button type="submit" class="btn btn-primary" name="edit" type="submit">Save changes</button>

            </div>
          </div>
          </form>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
