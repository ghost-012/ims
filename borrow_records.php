<?php 
ob_start();
session_start();
include('inc/header.php');
include 'Inventory.php';
$inventory = new Inventory();
$inventory->checkLogin();

// Function to fetch all borrowed items
function fetchBorrowedItems($inventory) {
  $query = "SELECT br.borrow_record_id, s.name as student_name, p.pname as product_name, br.borrow_date, br.returned_date, br.status 
            FROM ims_borrow_records br
            JOIN ims_student s ON br.student_id = s.id
            JOIN ims_product p ON br.product_id = p.pid";
  $result = mysqli_query($inventory->dbConnect, $query);
  $borrowedItems = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $borrowedItems[] = $row;
  }
  return $borrowedItems;
}

$borrowedItems = fetchBorrowedItems($inventory);

// Fetch all students for dropdown
function fetchStudents($inventory) {
  $query = "SELECT id, name FROM ims_student";
  $result = mysqli_query($inventory->dbConnect, $query);
  $students = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $students[] = $row;
  }
  return $students;
}

$students = fetchStudents($inventory);

// Fetch all products for dropdown
function fetchProducts($inventory) {
  $query = "SELECT pid, pname FROM ims_product";
  $result = mysqli_query($inventory->dbConnect, $query);
  $products = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $products[] = $row;
  }
  return $products;
}

$products = fetchProducts($inventory);
?>

<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>        
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script src="js/borrow.js"></script>
<script src="js/common.js"></script>
<?php include('inc/container.php');?>
<div class="container">        
  <?php include("menus.php"); ?> 
  <div class="row">
    <div class="col-lg-12">
      <div class="card card-default rounded-0 shadow">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
              <h3 class="card-title">Borrow Records</h3>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align="right">
              <button type="button" name="add" id="addBorrow" data-bs-toggle="modal" data-bs-target="#borrowModal" class="btn btn-primary bg-gradient btn-sm rounded-0"><i class="far fa-plus-square"></i> New Borrow Record</button>
            </div>
          </div>                       
          <div class="clear:both"></div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-sm-12 table-responsive">
              <table id="borrowList" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th class="text-center text-uppercase">#</th>                                        
                    <th class="text-center text-uppercase">Student Name</th>
                    <th class="text-center text-uppercase">Product Name</th>
                    <th class="text-center text-uppercase">Borrow Date</th>
                    <th class="text-center text-uppercase">Return Date</th>
                    <th class="text-center text-uppercase">Status</th>
                    <th class="text-center text-uppercase">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $index = 1; foreach ($borrowedItems as $item): ?>
                    <tr>
                      <td class="text-center"><?php echo $index++; ?></td>
                      <td class="text-center"><?php echo $item['student_name']; ?></td>
                      <td class="text-center"><?php echo $item['product_name']; ?></td>
                      <td class="text-center"><?php echo $item['borrow_date']; ?></td>
                      <td class="text-center"><?php echo empty($item['returned_date']) ? '------------' : date('M d, Y', strtotime($item['returned_date'])); ?></td>
                      <td class="text-center">
                        <?php 
                        $status = $item['status'];
                        $badge_class = '';
                        if ($status == 'returned') {
                            $badge_class = 'badge bg-success';
                        } else {
                            $badge_class = 'badge bg-danger';
                        }
                        ?>
                        <span class="<?php echo $badge_class; ?> text-capitalize"><?php echo $status; ?></span>
                      </td>
                      <td class="text-center">
                        <?php if ($item['status'] == 'borrowed'): ?>
                          <button type="button" class="btn btn-success btn-sm updateStatus fw-bold text-capitalize" data-id="<?php echo $item['borrow_record_id']; ?>">Update</button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-danger btn-sm deleteBorrow fw-bold text-capitalize" data-id="<?php echo $item['borrow_record_id']; ?>">Delete</button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="borrowModal" class="modal">
      <div class="modal-dialog modal-dialog-centered rounded-0">
        <div class="modal-content rounded-0">
          <div class="modal-header">
            <h4 class="modal-title"><i class="fa fa-plus"></i> Add Borrow Record</h4>
            <button type="button" class="btn-close text-xs" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
              <form method="post" id="borrowForm">
                <input type="hidden" name="action" id="action" value="addBorrow" />
                <div class="mb-3">
                  <label class="control-label fw-bold py-1">Student Name</label>
                  <select name="student_id" id="student_id" class="form-control rounded-0" required>
                    <option selected disabled>Select Student</option>
                    <?php foreach ($students as $student): ?>
                      <option value="<?php echo $student['id']; ?>"><?php echo $student['name']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="control-label fw-bold py-1">Product Name</label>
                  <select name="product_id" id="product_id" class="form-control rounded-0" required>
                    <option selected disabled>Select Product</option>
                    <?php foreach ($products as $product): ?>
                      <option value="<?php echo $product['pid']; ?>"><?php echo $product['pname']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="control-label fw-bold py-1">Borrow Date</label>
                  <input type="date" name="borrow_date" id="borrow_date" class="form-control rounded-0" required />
                </form>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" name="action" id="action" class="btn btn-sm rounded-0 btn-primary" form="borrowForm">Save</button>
              <button type="button" class="btn btn-sm rounded-0 btn-default border" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>    
    </div>    
  </div>    
</div>    
<?php include('inc/footer.php');?>

<script>
$(document).ready(function() {
  $('#borrowList').DataTable();

  // Handle form submission
  $('#borrowForm').on('submit', function(event) {
    event.preventDefault();
    $.ajax({
      url: "borrow_action.php",
      method: "POST",
      data: $(this).serialize(),
      success: function(data) {
        $('#borrowForm')[0].reset();
        $('#borrowModal').modal('hide');
        location.reload();
      }
    });
  });

  // Handle delete
  $('.deleteBorrow').on('click', function() {
    var id = $(this).data('id');
    if (confirm("Are you sure you want to delete this record?")) {
      $.ajax({
        url: "borrow_action.php",
        method: "POST",
        data: { action: 'deleteBorrow', id: id },
        success: function(data) {
          location.reload();
        }
      });
    }
  });

  // Handle update status
  $('.updateStatus').on('click', function() {
    var id = $(this).data('id');
    if (confirm("Are you sure you want to mark this item as returned?")) {
      $.ajax({
        url: "borrow_action.php",
        method: "POST",
        data: { action: 'updateStatus', id: id },
        success: function(data) {
          location.reload();
        }
    });
    }
  });
});
</script>