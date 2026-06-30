<?php 
// ១. បើកការបង្ហាញ Error ដើម្បីងាយស្រួលរកចំណុចខុស
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
            <li class="active">Purchase</li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="glyphicon glyphicon-plus-sign"></i> Add Purchase
            </div>
            
            <div class="panel-body">
                <form class="form-horizontal" method="POST" id="submitPurchaseForm" action="php_action/createPurchase.php">
                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Purchase Date</label>
                        <div class="col-sm-4">
                            <input type="date" class="form-control" id="purchaseDate" name="purchaseDate" required />
                        </div>
                        
                        <label class="col-sm-2 control-label">Invoice No.</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="invoiceNumber" name="invoiceNumber" placeholder="Invoice Number" required />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Supplier Name</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="supplierName" name="supplierName" required>
                                <option value="">~~ Select Supplier ~~</option>
                                <?php 
                                // ទាញទិន្នន័យពីតារាង suppliers មកបង្ហាញក្នុង Dropdown
                                $sql = "SELECT supplier_id, supplier_name FROM suppliers WHERE status = 1";
                                $query = $connect->query($sql);
                                while($row = $query->fetch_assoc()) {
                                    echo "<option value='".$row['supplier_id']."'>".$row['supplier_name']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <table class="table" id="purchaseItemTable">
                        <thead>
                            <tr>
                                <th style="width:40%;">Product</th>
                                <th style="width:20%;">Rate (Cost Price)</th>
                                <th style="width:15%;">Quantity</th>
                                <th style="width:25%;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="row1">
                                <td>
                                    <select class="form-control" name="productName[]" id="productName1" onchange="getProductData(1)" required>
                                        <option value="">~~ Select Product ~~</option>
                                        <?php 
                                        // ទាញទិន្នន័យពីតារាង product ចាស់របស់អ្នក
                                        $productSql = "SELECT product_id, product_name FROM product WHERE status = 1 AND active = 1";
                                        $productData = $connect->query($productSql);
                                        while($row = $productData->fetch_assoc()) {
                                            echo "<option value='".$row['product_id']."'>".$row['product_name']."</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="rate[]" id="rate1" autocomplete="off" class="form-control" step="0.01" onkeyup="getTotal(1)" required />
                                </td>
                                <td>
                                    <input type="number" name="quantity[]" id="quantity1" autocomplete="off" class="form-control" onkeyup="getTotal(1)" required />
                                </td>
                                <td>
                                    <input type="text" name="total[]" id="total1" autocomplete="off" class="form-control" disabled="true" />
                                    <input type="hidden" name="totalValue[]" id="totalValue1" autocomplete="off" class="form-control" />
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="form-group">
                        <label class="col-sm-offset-6 col-sm-2 control-label">Sub Amount</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="subTotal" name="subTotal" disabled="true" />
                            <input type="hidden" class="form-control" id="subTotalValue" name="subTotalValue" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-offset-6 col-sm-2 control-label">Grand Total</label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="grandTotal" name="grandTotal" disabled="true" />
                            <input type="hidden" class="form-control" id="grandTotalValue" name="grandTotalValue" />
                        </div>
                    </div>

                    <div class="form-group submit-button-section">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" id="createPurchaseBtn" class="btn btn-success" data-loading-text="Loading..."><i class="glyphicon glyphicon-ok-sign"></i> Save Purchase</button>
                            <button type="reset" class="btn btn-default"><i class="glyphicon glyphicon-erase"></i> Reset</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
function getTotal(row = null) {
    if(row) {
        var total = Number($("#rate"+row).val()) * Number($("#quantity"+row).val());
        total = total.toFixed(2);
        $("#total"+row).val(total);
        $("#totalValue"+row).val(total);
        
        subAmount();
    }
}

function subAmount() {
    var tableProductLength = $("#purchaseItemTable tbody tr").length;
    var totalSubAmount = 0;
    for(var x = 0; x < tableProductLength; x++) {
        var tr = $("#purchaseItemTable tbody tr")[x];
        var count = $(tr).attr('id').substring(3);
        totalSubAmount = Number(totalSubAmount) + Number($("#totalValue"+count).val());
    }
    
    totalSubAmount = totalSubAmount.toFixed(2);
    $("#subTotal").val(totalSubAmount);
    $("#subTotalValue").val(totalSubAmount);
    
    $("#grandTotal").val(totalSubAmount);
    $("#grandTotalValue").val(totalSubAmount);
}
</script>

<?php require_once 'includes/footer.php'; ?>