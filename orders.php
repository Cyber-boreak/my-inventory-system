<?php 
// បើកការបង្ហាញ error ដើម្បីងាយស្រួលរកកន្លែងខុសលើកក្រោយ
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'php_action/db_connect.php'; 
require_once 'includes/header.php'; 

// កំណត់តម្លៃចរន្តនៃទំព័រ (add, man, editOrd)
$page_action = isset($_GET['o']) ? $_GET['o'] : '';

if($page_action == 'add') { 
    echo "<div class='div-request div-hide'>add</div>";
} else if($page_action == 'man') { 
    echo "<div class='div-request div-hide'>manord</div>";
} else if($page_action == 'editOrd') { 
    echo "<div class='div-request div-hide'>editOrd</div>";
}
?>

<ol class="breadcrumb">
    <li><a href="dashboard.php">Home</a></li>
    <li>Order</li>
    <li class="active">
        <?php 
        if($page_action == 'add') { echo "Add Order"; } 
        else if($page_action == 'man') { echo "Manage Order"; } 
        else if($page_action == 'editOrd') { echo "Edit Order"; }
        ?>
    </li>
</ol>

<h4>
    <i class='glyphicon glyphicon-circle-arrow-right'></i>
    <?php 
    if($page_action == 'add') { echo "Add Order"; } 
    else if($page_action == 'man') { echo "Manage Order"; } 
    else if($page_action == 'editOrd') { echo "Edit Order"; }
    ?>  
</h4>

<div class="panel panel-default">
    <div class="panel-heading">
        <?php if($page_action == 'add') { ?>
            <i class="glyphicon glyphicon-plus-sign"></i> Add Order
        <?php } else if($page_action == 'man') { ?>
            <i class="glyphicon glyphicon-edit"></i> Manage Order
        <?php } else if($page_action == 'editOrd') { ?>
            <i class="glyphicon glyphicon-edit"></i> Edit Order
        <?php } ?>
    </div> 
    
    <div class="panel-body">
        
        <?php if($page_action == 'add') { ?>          
            <div class="success-messages"></div> 
            <form class="form-horizontal" method="POST" action="php_action/createOrder.php" id="createOrderForm">
                <div class="form-group">
                    <label for="orderDate" class="col-sm-2 control-label">Order Date</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="orderDate" name="orderDate" autocomplete="off" />
                    </div>
                </div> 
                <div class="form-group">
                    <label for="clientName" class="col-sm-2 control-label">Client Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="clientName" name="clientName" placeholder="Client Name" autocomplete="off" />
                    </div>
                </div> 
                <div class="form-group">
                    <label for="clientContact" class="col-sm-2 control-label">Client Contact</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="clientContact" name="clientContact" placeholder="Contact Number" autocomplete="off" />
                    </div>
                </div> 

                <table class="table" id="productTable">
                    <thead>
                        <tr>                        
                            <th style="width:40%;">Product</th>
                            <th style="width:20%;">Rate</th>
                            <th style="width:10%;">Available Quantity</th>
                            <th style="width:15%;">Quantity</th>                        
                            <th style="width:25%;">Total</th>                       
                            <th style="width:10%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $arrayNumber = 0;
                        for($x = 1; $x < 2; $x++) { ?>
                            <tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">                          
                                <td style="margin-left:20px;">
                                    <div class="form-group">
                                        <select class="form-control" name="productName[]" id="productName<?php echo $x; ?>" onchange="getProductData(<?php echo $x; ?>)" >
                                            <option value="">~~SELECT~~</option>
                                            <?php
                                                $productSql = "SELECT * FROM product WHERE active = 1 AND status = 1 AND quantity != 0";
                                                $productData = $connect->query($productSql);
                                                while($row = $productData->fetch_array()) {                                         
                                                    echo "<option value='".$row['product_id']."' id='changeProduct".$row['product_id']."'>".$row['product_name']."</option>";
                                                } 
                                            ?>
                                        </select>
                                    </div>
                                </td>
                                <td style="padding-left:20px;">                             
                                    <input type="text" name="rate[]" id="rate<?php echo $x; ?>" autocomplete="off" disabled="true" class="form-control" />                              
                                    <input type="hidden" name="rateValue[]" id="rateValue<?php echo $x; ?>" autocomplete="off" class="form-control" />                              
                                </td>
                                <td style="padding-left:20px;">
                                    <div class="form-group">
                                        <p id="available_quantity<?php echo $x; ?>"></p>
                                    </div>
                                </td>
                                <td style="padding-left:20px;">
                                    <div class="form-group">
                                        <input type="number" name="quantity[]" id="quantity<?php echo $x; ?>" onkeyup="getTotal(<?php echo $x ?>)" autocomplete="off" class="form-control" min="1" />
                                    </div>
                                </td>
                                <td style="padding-left:20px;">                             
                                    <input type="text" name="total[]" id="total<?php echo $x; ?>" autocomplete="off" class="form-control" disabled="true" />                                
                                    <input type="hidden" name="totalValue[]" id="totalValue<?php echo $x; ?>" autocomplete="off" class="form-control" />                                
                                </td>
                                <td>
                                    <button class="btn btn-default removeProductRowBtn" type="button" id="removeProductRowBtn" onclick="removeProductRow(<?php echo $x; ?>)"><i class="glyphicon glyphicon-trash"></i></button>
                                </td>
                            </tr>
                        <?php
                        $arrayNumber++;
                        } 
                        ?>
                    </tbody>                
                </table>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="subTotal" class="col-sm-3 control-label">Sub Amount</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="subTotal" name="subTotal" disabled="true" />
                            <input type="hidden" class="form-control" id="subTotalValue" name="subTotalValue" />
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="totalAmount" class="col-sm-3 control-label">Total Amount</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="totalAmount" name="totalAmount" disabled="true"/>
                            <input type="hidden" class="form-control" id="totalAmountValue" name="totalAmountValue" />
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="discount" class="col-sm-3 control-label">Discount</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="discount" name="discount" onkeyup="discountFunc()" autocomplete="off" />
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="grandTotal" class="col-sm-3 control-label">Grand Total</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="grandTotal" name="grandTotal" disabled="true" />
                            <input type="hidden" class="form-control" id="grandTotalValue" name="grandTotalValue" />
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="vat" class="col-sm-3 control-label gst">GST 18%</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="vat" name="gstn" readonly="true" />
                            <input type="hidden" class="form-control" id="vatValue" name="vatValue" />
                        </div>
                    </div>              
                </div> 

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="paid" class="col-sm-3 control-label">Paid Amount</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="paid" name="paid" autocomplete="off" onkeyup="paidAmount()" />
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="due" class="col-sm-3 control-label">Due Amount</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="due" name="due" disabled="true" />
                            <input type="hidden" class="form-control" id="dueValue" name="dueValue" />
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Payment Type</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="paymentType" id="paymentType">
                                <option value="">~~SELECT~~</option>
                                <option value="1">Cheque</option>
                                <option value="2">Cash</option>
                                <option value="3">Credit Card</option>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Payment Status</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="paymentStatus" id="paymentStatus">
                                <option value="">~~SELECT~~</option>
                                <option value="1">Full Payment</option>
                                <option value="2">Advance Payment</option>
                                <option value="3">No Payment</option>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Payment Place</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="paymentPlace" id="paymentPlace">
                                <option value="">~~SELECT~~</option>
                                <option value="1">Islamabad</option>
                                <option value="2">Out Of Islamabad</option>
                            </select>
                        </div>
                    </div> 
                </div> 

                <div class="form-group submitButtonFooter">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="button" class="btn btn-default" onclick="addRow()" id="addRowBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-plus-sign"></i> Add Row </button>
                        <button type="submit" id="createOrderBtn" data-loading-text="Loading..." class="btn btn-success"><i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
                        <button type="reset" class="btn btn-default" onclick="resetOrderForm()"><i class="glyphicon glyphicon-erase"></i> Reset</button>
                    </div>
                </div>
            </form>

        <?php } else if($page_action == 'man') { ?>
            <div id="success-messages"></div>
            <table class="table" id="manageOrderTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order Date</th>
                        <th>Client Name</th>
                        <th>Contact</th>
                        <th>Total Order Item</th>
                        <th>Payment Status</th>
                        <th>Option</th>
                    </tr>
                </thead>
            </table>

        <?php } else if($page_action == 'editOrd') { 
            $orderId = (int)$_GET['i'];

            $sql = "SELECT order_id, order_date, client_name, client_contact, sub_total, vat, total_amount, discount, grand_total, paid, due, payment_type, payment_status, payment_place, gstn 
                    FROM orders 
                    WHERE order_id = {$orderId}";

            $result = $connect->query($sql);
            $data = $result->fetch_row();
            ?>
            
            <div class="success-messages"></div> 
            <form class="form-horizontal" method="POST" action="php_action/editOrder.php" id="editOrderForm">
                <div class="form-group">
                    <label for="orderDate" class="col-sm-2 control-label">Order Date</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="orderDate" name="orderDate" autocomplete="off" value="<?php echo $data[1] ?>" />
                    </div>
                </div> 
                <div class="form-group">
                    <label for="clientName" class="col-sm-2 control-label">Client Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="clientName" name="clientName" placeholder="Client Name" autocomplete="off" value="<?php echo $data[2] ?>" />
                    </div>
                </div> 
                <div class="form-group">
                    <label for="clientContact" class="col-sm-2 control-label">Client Contact</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="clientContact" name="clientContact" placeholder="Contact Number" autocomplete="off" value="<?php echo $data[3] ?>" />
                    </div>
                </div> 

                <table class="table" id="productTable">
                    <thead>
                        <tr>                        
                            <th style="width:40%;">Product</th>
                            <th style="width:20%;">Rate</th>
                            <th style="width:15%;">Available Quantity</th>                      
                            <th style="width:15%;">Quantity</th>                        
                            <th style="width:15%;">Total</th>                       
                            <th style="width:10%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $orderItemSql = "SELECT order_item_id, order_id, product_id, quantity, rate, total FROM order_item WHERE order_id = {$orderId}";
                        $orderItemResult = $connect->query($orderItemSql);
                        
                        $arrayNumber = 0;
                        $x = 1;
                        while($orderItemData = $orderItemResult->fetch_array()) { ?>
                            <tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">                          
                                <td style="margin-left:20px;">
                                    <div class="form-group">
                                        <select class="form-control" name="productName[]" id="productName<?php echo $x; ?>" onchange="getProductData(<?php echo $x; ?>)" >
                                            <option value="">~~SELECT~~</option>
                                            <?php
                                                $productSql = "SELECT * FROM product WHERE active = 1 AND status = 1 AND quantity != 0";
                                                $productData = $connect->query($productSql);

                                                while($row = $productData->fetch_array()) {                                         
                                                    $selected = ($row['product_id'] == $orderItemData['product_id']) ? "selected" : "";
                                                    echo "<option value='".$row['product_id']."' id='changeProduct".$row['product_id']."' ".$selected." >".$row['product_name']."</option>";
                                                } 
                                            ?>
                                        </select>
                                    </div>
                                </td>
                                <td style="padding-left:20px;">                             
                                    <input type="text" name="rate[]" id="rate<?php echo $x; ?>" autocomplete="off" disabled="true" class="form-control" value="<?php echo $orderItemData['rate']; ?>" />                                
                                    <input type="hidden" name="rateValue[]" id="rateValue<?php echo $x; ?>" autocomplete="off" class="form-control" value="<?php echo $orderItemData['rate']; ?>" />                                
                                </td>
                                <td style="padding-left:20px;">
                                    <div class="form-group">
                                        <?php
                                        // កែសម្រួលកន្លែងនេះឱ្យមាន id="available_quantityX" ត្រឹមត្រូវដូចផ្នែក Add Order ដើម្បីកុំឱ្យជាន់គ្នា និងងាយស្រួល Update តាម JavaScript
                                        $pId = $orderItemData['product_id'];
                                        $pSql = "SELECT quantity FROM product WHERE product_id = {$pId}";
                                        $pResult = $connect->query($pSql);
                                        $pData = $pResult->fetch_row();
                                        ?>
                                        <p id="available_quantity<?php echo $x; ?>"><?php echo $pData[0]; ?></p>
                                    </div>
                                </td>
                                <td style="padding-left:20px;">
                                    <div class="form-group">
                                        <input type="number" name="quantity[]" id="quantity<?php echo $x; ?>" onkeyup="getTotal(<?php echo $x ?>)" autocomplete="off" class="form-control" min="1" value="<?php echo $orderItemData['quantity']; ?>" />
                                    </div>
                                </td>
                                <td style="padding-left:20px;">                             
                                    <input type="text" name="total[]" id="total<?php echo $x; ?>" autocomplete="off" class="form-control" disabled="true" value="<?php echo $orderItemData['total']; ?>"/>                              
                                    <input type="hidden" name="totalValue[]" id="totalValue<?php echo $x; ?>" autocomplete="off" class="form-control" value="<?php echo $orderItemData['total']; ?>"/>                              
                                </td>
                                <td>
                                    <button class="btn btn-default removeProductRowBtn" type="button" id="removeProductRowBtn" onclick="removeProductRow(<?php echo $x; ?>)"><i class="glyphicon glyphicon-trash"></i></button>
                                </td>
                            </tr>
                        <?php
                        $arrayNumber++;
                        $x++;
                        } 
                        ?>
                    </tbody>                
                </table>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="subTotal" class="col-sm-3 control-label">Sub Amount</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="subTotal" name="subTotal" disabled="true" value="<?php echo $data[4] ?>" />
                            <input type="hidden" class="form-control" id="subTotalValue" name="subTotalValue" value="<?php echo $data[4] ?>" />
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="totalAmount" class="col-sm-3 control-label">Total Amount</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="totalAmount" name="totalAmount" disabled="true" value="<?php echo $data[6] ?>" />
                            <input type="hidden" class="form-control" id="totalAmountValue" name="totalAmountValue" value="<?php echo $data[6] ?>"  />
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="discount" class="col-sm-3 control-label">Discount</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="discount" name="discount" onkeyup="discountFunc()" autocomplete="off" value="<?php echo $data[7] ?>" />
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="grandTotal" class="col-sm-3 control-label">Grand Total</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="grandTotal" name="grandTotal" disabled="true" value="<?php echo $data[8] ?>"  />
                            <input type="hidden" class="form-control" id="grandTotalValue" name="grandTotalValue" value="<?php echo $data[8] ?>"  />
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="vat" class="col-sm-3 control-label gst"><?php echo ($data[13] == 2) ? "IGST 18%" : "GST 18?"; ?></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="vat" name="vat" disabled="true" value="<?php echo $data[5] ?>"  />
                            <input type="hidden" class="form-control" id="vatValue" name="vatValue" value="<?php echo $data[5] ?>"  />
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="gstn" class="col-sm-3 control-label gst">G.S.T.IN</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="gstn" name="gstn" value="<?php echo $data[14] ?>"  />
                        </div>
                    </div>
                </div> 

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="paid" class="col-sm-3 control-label">Paid Amount</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="paid" name="paid" autocomplete="off" onkeyup="paidAmount()" value="<?php echo $data[9] ?>"  />
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="due" class="col-sm-3 control-label">Due Amount</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="due" name="due" disabled="true" value="<?php echo $data[10] ?>"  />
                            <input type="hidden" class="form-control" id="dueValue" name="dueValue" value="<?php echo $data[10] ?>"  />
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Payment Type</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="paymentType" id="paymentType" >
                                <option value="">~~SELECT~~</option>
                                <option value="1" <?php if($data[11] == 1) { echo "selected"; } ?> >Cheque</option>
                                <option value="2" <?php if($data[11] == 2) { echo "selected"; } ?> >Cash</option>
                                <option value="3" <?php if($data[11] == 3) { echo "selected"; } ?> >Credit Card</option>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Payment Status</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="paymentStatus" id="paymentStatus">
                                <option value="">~~SELECT~~</option>
                                <option value="1" <?php if($data[12] == 1) { echo "selected"; } ?> >Full Payment</option>
                                <option value="2" <?php if($data[12] == 2) { echo "selected"; } ?> >Advance Payment</option>
                                <option value="3" <?php if($data[12] == 3) { echo "selected"; } ?> >No Payment</option>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Payment Place</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="paymentPlace" id="paymentPlace">
                                <option value="">~~SELECT~~</option>
                                <option value="1" <?php if($data[13] == 1) { echo "selected"; } ?> >In Gujarat</option>
                                <option value="2" <?php if($data[13] == 2) { echo "selected"; } ?> >Out Gujarat</option>
                            </select>
                        </div>
                    </div>                                                                  
                </div> 

                <div class="form-group editButtonFooter">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="button" class="btn btn-default" onclick="addRow()" id="addRowBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-plus-sign"></i> Add Row </button>
                        <input type="hidden" name="orderId" id="orderId" value="<?php echo $_GET['i']; ?>" />
                        <button type="submit" id="editOrderBtn" data-loading-text="Loading..." class="btn btn-success"><i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
                    </div>
                </div>
            </form>
        <?php } ?>

    </div> 
</div> 

<div class="modal fade" tabindex="-1" role="dialog" id="paymentOrderModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="glyphicon glyphicon-edit"></i> Edit Payment</h4>
            </div>      
            <div class="modal-body form-horizontal" style="max-height:500px; overflow:auto;" >
                <div class="paymentOrderMessages"></div>
                <div class="form-group">
                    <label for="due" class="col-sm-3 control-label">Due Amount</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="due" name="due" disabled="true" />                    
                    </div>
                </div> 
                <div class="form-group">
                    <label for="payAmount" class="col-sm-3 control-label">Pay Amount</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="payAmount" name="payAmount"/>                        
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-3 control-label">Payment Type</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="paymentType" id="paymentType" >
                            <option value="">~~SELECT~~</option>
                            <option value="1">Cheque</option>
                            <option value="2">Cash</option>
                            <option value="3">Credit Card</option>
                        </select>
                    </div>
                </div> 
                <div class="form-group">
                    <label class="col-sm-3 control-label">Payment Status</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="paymentStatus" id="paymentStatus">
                            <option value="">~~SELECT~~</option>
                            <option value="1">Full Payment</option>
                            <option value="2">Advance Payment</option> 
                            <option value="3">No Payment</option>
                        </select>
                    </div>
                </div> 
            </div> 
        </div> 
    </div> 
</div> 

<?php require_once 'includes/footer.php'; ?>