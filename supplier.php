<?php 
// ១. បើកការបង្ហាញ Error ដើម្បីងាយស្រួលរកចំណុចខុស
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ២. ទាញចូលហ្វាល Header និងការតភ្ជាប់ Database
require_once 'includes/header.php'; 

// ៣. កូដសម្រាប់ដំណើរការនៅពេលចុចប៊ូតុង Save (Insert ទិន្នន័យចូល Database)
if($_POST) {
    $supplierName = $_POST['supplierName'];
    $contactName  = $_POST['contactName'];
    $phone        = $_POST['phone'];
    $email        = $_POST['email'];
    $address      = $_POST['address'];
    $status       = $_POST['status'];

    $sql = "INSERT INTO suppliers (supplier_name, contact_name, phone, email, address, status) 
            VALUES ('$supplierName', '$contactName', '$phone', '$email', '$address', '$status')";

    if($connect->query($sql) === TRUE) {
        echo "<div class='alert alert-success alert-dismissible' role='alert'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                <strong>Success!</strong> Successfully Added Supplier.
              </div>";
    } else {
        echo "<div class='alert alert-danger alert-dismissible' role='alert'>
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                <strong>Error!</strong> Error while adding supplier: " . $connect->error . "
              </div>";
    }
}
?>

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="dashboard.php">Home</a></li>
            <li class="active">Suppliers</li>
        </ol>

        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="glyphicon glyphicon-plus-sign"></i> Add Supplier
                    </div>
                    <div class="panel-body">
                        <form action="supplier.php" method="POST" id="submitSupplierForm">
                            <div class="form-group">
                                <label for="supplierName">Supplier Name *</label>
                                <input type="text" class="form-control" id="supplierName" name="supplierName" placeholder="e.g. Apple Wholesale" required>
                            </div>
                            <div class="form-group">
                                <label for="contactName">Contact Person</label>
                                <input type="text" class="form-control" id="contactName" name="contactName" placeholder="e.g. Mr. John">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="e.g. 012345678">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="e.g. supplier@mail.com">
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2" placeholder="e.g. Phnom Penh"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success btn-block"><i class="glyphicon glyphicon-ok-sign"></i> Save Supplier</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="glyphicon glyphicon-th-list"></i> Manage Suppliers
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered table-striped" id="manageSupplierTable">
                            <thead>
                                <tr>
                                    <th>Supplier Name</th>
                                    <th>Contact</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $selectSql = "SELECT * FROM suppliers ORDER BY supplier_id DESC";
                                $result = $connect->query($selectSql);

                                if($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        $statusLabel = ($row['status'] == 1) ? "<label class='label label-success'>Active</label>" : "<label class='label label-danger'>Inactive</label>";
                                        
                                        echo "<tr>
                                            <td><strong>".$row['supplier_name']."</strong><br><small class='text-muted'>".$row['address']."</small></td>
                                            <td>".$row['contact_name']."</td>
                                            <td>".$row['phone']."</td>
                                            <td>".$statusLabel."</td>
                                            <td>
                                                <div class='btn-group'>
                                                    <button type='button' class='btn btn-default btn-sm dropdown-toggle' data-toggle='dropdown'>
                                                        Action <span class='caret'></span>
                                                    </button>
                                                    <ul class='dropdown-menu dropdown-menu-right'>
                                                        <li><a href='#'><i class='glyphicon glyphicon-edit'></i> Edit</a></li>
                                                        <li><a href='#'><i class='glyphicon glyphicon-trash'></i> Delete</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center'>No suppliers found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once 'includes/footer.php'; ?>