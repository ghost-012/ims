<?php
session_start();
include 'Inventory.php';
$inventory = new Inventory();
$inventory->checkLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'];

  if ($action == 'addBorrow') {
    $student_id = $_POST['student_id'];
    $product_id = $_POST['product_id'];
    $borrow_date = $_POST['borrow_date'];

    $query = "INSERT INTO ims_borrow_records (student_id, product_id, borrow_date) VALUES ('$student_id', '$product_id', '$borrow_date')";
    if (mysqli_query($inventory->dbConnect, $query)) {
      echo 'Record added successfully';
    } else {
      echo 'Error: ' . mysqli_error($inventory->dbConnect);
    }
  } elseif ($action == 'deleteBorrow') {
    $id = $_POST['id'];
    $query = "DELETE FROM ims_borrow_records WHERE id = '$id'";
    if (mysqli_query($inventory->dbConnect, $query)) {
      echo 'Record deleted successfully';
    } else {
      echo 'Error: ' . mysqli_error($inventory->dbConnect);
    }
  } elseif ($action == 'updateStatus') {
    $id = $_POST['id'];
    $returned_date = date('Y-m-d');
    $query = "UPDATE ims_borrow_records SET status = 'returned', returned_date = '$returned_date' WHERE borrow_record_id = '$id'";
    if (mysqli_query($inventory->dbConnect, $query)) {
      echo 'Status updated successfully';
    } else {
      echo 'Error: ' . mysqli_error($inventory->dbConnect);
    }
  }
}
?>