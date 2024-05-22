<?php 
ob_start();
session_start();
include('inc/header.php');
include 'Inventory.php';
$inventory = new Inventory();
$inventory->checkLogin();
?>

<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>		
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
<script src="js/customer.js"></script>
<script src="js/common.js"></script>
<?php include('inc/container.php');?>
<div class="container">		
    <?php include("menus.php"); ?> 
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-default rounded-0 shadow">
                <div class="card-header">
                    <h3 class="card-title">Borrow Products</h3>                   
                    <div class="clear:both"></div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <form method="post" id="borrowForm">
                                <div class="mb-3">
                                    <label class="control-label">Select Customer</label>
                                    <select name="customer_id" id="customer_id" class="form-control rounded-0" required>
                                        <option value="">Select Customer</option>
                                        <?php 
                                            // Fetch customers from database and populate the dropdown
                                            $customers = getCustomers();
                                            foreach ($customers as $customer) {
                                                echo "<option value='".$customer['id']."'>".$customer['name']."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="control-label">Select Product</label>
                                    <select name="product_id" id="product_id" class="form-control rounded-0" required>
                                        <option value="">Select Product</option>
                                        <?php 
                                            // Fetch products from database and populate the dropdown
                                            $products = getProducts();
                                            foreach ($products as $product) {
                                                echo "<option value='".$product['pid']."'>".$product['pname']."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="control-label">Quantity</label>
                                    <input type="number" name="quantity" id="quantity" class="form-control rounded-0" required />
                                </div>
                                <button type="submit" name="action" id="action" class="btn btn-sm rounded-0 btn-primary">Borrow Product</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>	
<?php include('inc/footer.php');?>
