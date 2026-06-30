<?php 
// ១. បើកការបង្ហាញ Error ដើម្បីងាយស្រួលរកចំណុចខុសពេលដំណើរការ
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ២. ទាញចូលហ្វាល Header និងការតភ្ជាប់ Database
require_once 'includes/header.php'; 
?>

<div class="row">
    <div class="col-md-12">
        <ol class="breadcrumb">
            <li><a href="dashboard.php">Home</a></li>
            <li class="active">Manage Purchases</li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="page-heading">
                    <i class="glyphicon glyphicon-edit"></i> Manage Purchases
                </div>
            </div>
            
            <div class="panel-body">
                <div class="div-action pull pull-right" style="padding-bottom:20px;">
                    <a href="add_purchase.php" class="btn btn-success"> 
                        <i class="glyphicon glyphicon-plus-sign"></i> Add Purchase 
                    </a>
                </div> 
                
                <table class="table table-bordered table-striped" id="managePurchaseTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Purchase Date</th>
                            <th>Invoice No.</th>
                            <th>Supplier Name</th>
                            <th>Grand Total</th>
                            <th>Paid Amount</th>
                            <th>Due Amount</th>
                            <th>Payment Status</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // សរសេរកូដ SQL ទាញទិន្នន័យទិញចូល ដោយលាយជាមួយឈ្មោះអ្នកផ្គត់ផ្គង់ (JOIN Suppliers)
                        $sql = "SELECT p.*, s.supplier_name 
                                FROM purchases p 
                                INNER JOIN suppliers s ON p.supplier_id = s.supplier_id 
                                ORDER BY p.purchase_id DESC";
                                
                        $result = $connect->query($sql);
                        $count = 1;

                        if($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                
                                // កំណត់ការបង្ហាញពណ៌ទៅតាមស្ថានភាពបង់ប្រាក់
                                $paymentStatus = "";
                                if($row['payment_status'] == 1) {
                                    $paymentStatus = "<label class='label label-success'>Full Payment</label>";
                                } else if($row['payment_status'] == 2) {
                                    $paymentStatus = "<label class='label label-info'>Partial Payment</label>";
                                } else {
                                    $paymentStatus = "<label class='label label-danger'>No Payment</label>";
                                }

                                echo "<tr>
                                    <td>".$count."</td>
                                    <td>".$row['purchase_date']."</td>
                                    <td>".$row['invoice_number']."</td>
                                    <td>".$row['supplier_name']."</td>
                                    <td>$".number_format($row['grand_total'], 2)."</td>
                                    <td>$".number_format($row['paid_amount'], 2)."</td>
                                    <td>$".number_format($row['due_amount'], 2)."</td>
                                    <td>".$paymentStatus."</td>
                                    <td>
                                        <div class='btn-group'>
                                            <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                Action <span class='caret'></span>
                                            </button>
                                            <ul class='dropdown-menu'>
                                                <li><a href='edit_purchase.php?id=".$row['purchase_id']."'><i class='glyphicon glyphicon-edit'></i> Edit</a></li>
                                                <li><a href='#' onclick='removePurchase(".$row['purchase_id'].")'><i class='glyphicon glyphicon-trash'></i> Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>";
                                $count++;
                            }
                        } else {
                            // ប្រសិនបើមិនទាន់មានទិន្នន័យទិញចូលសោះក្នុង Database
                            echo "<tr><td colspan='9' class='text-center'>No Data Available in Table</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>