
<?php require_once 'php_action/db_connect.php' ?>
<?php require_once 'includes/header.php'; ?>

<div class="row">
    <div class="col-md-12">

        <ol class="breadcrumb">
            <li><a href="dashboard.php"><?php echo isset($lang['dashboard']) ? $lang['dashboard'] : 'Home'; ?></a></li>      
            <li class="active"><?php echo isset($lang['product']) ? $lang['product'] : 'Product'; ?></li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="page-heading"> 
                    <i class="glyphicon glyphicon-edit"></i> 
                    <?php echo isset($lang['manage_product']) ? $lang['manage_product'] : 'Manage Product'; ?>
                </div>
            </div> 
            
            <div class="panel-body">
                <div class="remove-messages"></div>

                <div class="div-action pull pull-right" style="padding-bottom:20px; display: flex; gap: 5px;">
                    <button onclick="exportProductToExcel()" class="btn btn-success">
                        <i class="glyphicon glyphicon-file"></i> Excel
                    </button>

                    <button class="btn btn-info" data-toggle="modal" data-target="#importExcelModal">
                        <i class="glyphicon glyphicon-import"></i> Import Excel
                    </button>

                    <button onclick="printProductTable()" class="btn btn-danger">
                        <i class="glyphicon glyphicon-print"></i> PDF / Print
                    </button>
                    
                    <button class="btn btn-default button1" data-toggle="modal" id="addProductModalBtn" data-target="#addProductModal"> 
                        <i class="glyphicon glyphicon-plus-sign"></i> 
                        <?php echo isset($lang['add_product']) ? $lang['add_product'] : 'Add Product'; ?> 
                    </button>
                </div>

                <table class="table" id="manageProductTable">
                    <thead>
                        <tr>
                            <th style="width:10%;"><?php echo isset($lang['photo']) ? $lang['photo'] : 'Photo'; ?></th>                           
                            <th><?php echo isset($lang['product_name']) ? $lang['product_name'] : 'Product Name'; ?></th>
                            <th><?php echo isset($lang['rate']) ? $lang['rate'] : 'Rate'; ?></th>                           
                            <th><?php echo isset($lang['quantity']) ? $lang['quantity'] : 'Quantity'; ?></th>
                            <th><?php echo isset($lang['brand']) ? $lang['brand'] : 'Brand'; ?></th>
                            <th><?php echo isset($lang['category']) ? $lang['category'] : 'Category'; ?></th>
                            <th><?php echo isset($lang['status']) ? $lang['status'] : 'Status'; ?></th>
                            <th style="width:15%;"><?php echo isset($lang['options']) ? $lang['options'] : 'Options'; ?></th>
                        </tr>
                    </thead>
                </table>

            </div> 
        </div> 
    </div> 
</div> 

<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" id="submitProductForm" action="php_action/createProduct.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">
                        <i class="fa fa-plus"></i> 
                        <?php echo isset($lang['add_product']) ? $lang['add_product'] : 'Add Product'; ?>
                    </h4>
                </div>

                <div class="modal-body" style="max-height:450px; overflow:auto;">
                    <div id="add-product-messages"></div>

                    <div class="form-group">
                        <label for="productImage" class="col-sm-3 control-label"><?php echo isset($lang['product_image']) ? $lang['product_image'] : 'Product Image'; ?>: </label>
                        <label class="col-sm-1 control-label">: </label>
                        <div class="col-sm-8">
                            <div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>                          
                            <div class="kv-avatar center-block">                            
                                <input type="file" class="form-control" id="productImage" name="productImage" style="width:auto;"/>
                            </div>
                        </div>
                    </div> 

                    <div class="form-group">
                        <label for="productName" class="col-sm-3 control-label"><?php echo isset($lang['product_name']) ? $lang['product_name'] : 'Product Name'; ?>: </label>
                        <label class="col-sm-1 control-label">: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="productName" placeholder="<?php echo isset($lang['product_name']) ? $lang['product_name'] : 'Product Name'; ?>" name="productName" autocomplete="off">
                        </div>
                    </div> 

                    <div class="form-group">
                        <label for="quantity" class="col-sm-3 control-label"><?php echo isset($lang['quantity']) ? $lang['quantity'] : 'Quantity'; ?>: </label>
                        <label class="col-sm-1 control-label">: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="quantity" placeholder="<?php echo isset($lang['quantity']) ? $lang['quantity'] : 'Quantity'; ?>" name="quantity" autocomplete="off">
                        </div>
                    </div> 

                    <div class="form-group">
                        <label for="rate" class="col-sm-3 control-label"><?php echo isset($lang['rate']) ? $lang['rate'] : 'Rate'; ?>: </label>
                        <label class="col-sm-1 control-label">: </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="rate" placeholder="<?php echo isset($lang['rate']) ? $lang['rate'] : 'Rate'; ?>" name="rate" autocomplete="off">
                        </div>
                    </div> 

                    <div class="form-group">
                        <label for="brandName" class="col-sm-3 control-label"><?php echo isset($lang['brand_name']) ? $lang['brand_name'] : 'Brand Name'; ?>: </label>
                        <label class="col-sm-1 control-label">: </label>
                        <div class="col-sm-8">
                            <select class="form-control" id="brandName" name="brandName">
                                <option value=""><?php echo isset($lang['select']) ? $lang['select'] : '~~SELECT~~'; ?></option>
                                <?php 
                                $sql = "SELECT brand_id, brand_name, brand_active, brand_status FROM brands WHERE brand_status = 1 AND brand_active = 1";
                                $result = $connect->query($sql);
                                while($row = $result->fetch_array()) {
                                    echo "<option value='".$row[0]."'>".$row[1]."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div> 

                    <div class="form-group">
                        <label for="categoryName" class="col-sm-3 control-label"><?php echo isset($lang['category_name']) ? $lang['category_name'] : 'Category Name'; ?>: </label>
                        <label class="col-sm-1 control-label">: </label>
                        <div class="col-sm-8">
                            <select class="form-control" id="categoryName" name="categoryName" >
                                <option value=""><?php echo isset($lang['select']) ? $lang['select'] : '~~SELECT~~'; ?></option>
                                <?php 
                                $sql = "SELECT categories_id, categories_name, categories_active, categories_status FROM categories WHERE categories_status = 1 AND categories_active = 1";
                                $result = $connect->query($sql);
                                while($row = $result->fetch_array()) {
                                    echo "<option value='".$row[0]."'>".$row[1]."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div> 

                    <div class="form-group">
                        <label for="productStatus" class="col-sm-3 control-label"><?php echo isset($lang['status']) ? $lang['status'] : 'Status'; ?>: </label>
                        <label class="col-sm-1 control-label">: </label>
                        <div class="col-sm-8">
                            <select class="form-control" id="productStatus" name="productStatus">
                                <option value=""><?php echo isset($lang['select']) ? $lang['select'] : '~~SELECT~~'; ?></option>
                                <option value="1"><?php echo isset($lang['available']) ? $lang['available'] : 'Available'; ?></option>
                                <option value="2"><?php echo isset($lang['not_available']) ? $lang['not_available'] : 'Not Available'; ?></option>
                            </select>
                        </div>
                    </div> 
                </div> 

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> <?php echo isset($lang['close']) ? $lang['close'] : 'Close'; ?></button>
                    <button type="submit" class="btn btn-primary" id="createProductBtn" data-loading-text="Loading..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> <?php echo isset($lang['save_changes']) ? $lang['save_changes'] : 'Save Changes'; ?></button>
                </div> 
            </form> 
        </div> 
    </div> 
</div> 

<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <i class="fa fa-edit"></i> 
                    <?php echo isset($lang['edit_product']) ? $lang['edit_product'] : 'Edit Product'; ?>
                </h4>
            </div>
            
            <div class="modal-body" style="max-height:450px; overflow:auto;">
                <div class="div-loading">
                    <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                    <span class="sr-only">Loading...</span>
                </div>

                <div class="div-result">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#photo" aria-controls="home" role="tab" data-toggle="tab"><?php echo isset($lang['photo']) ? $lang['photo'] : 'Photo'; ?></a></li>
                        <li role="presentation"><a href="#productInfo" aria-controls="profile" role="tab" data-toggle="tab"><?php echo isset($lang['product_info']) ? $lang['product_info'] : 'Product Info'; ?></a></li>    
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="photo">
                            <form action="php_action/editProductImage.php" method="POST" id="updateProductImageForm" class="form-horizontal" enctype="multipart/form-data">
                                <br />
                                <div id="edit-productPhoto-messages"></div>

                                <div class="form-group">
                                    <label for="editProductImage" class="col-sm-3 control-label"><?php echo isset($lang['product_image']) ? $lang['product_image'] : 'Product Image'; ?>: </label>
                                    <label class="col-sm-1 control-label">: </label>
                                    <div class="col-sm-8">                                                                                                                                                                                                            
                                        <img src="" id="getProductImage" class="thumbnail" style="width:250px; height:250px;" />
                                    </div>
                                </div> 
                                
                                <div class="form-group">
                                    <label for="editProductImage" class="col-sm-3 control-label"><?php echo isset($lang['select_photo']) ? $lang['select_photo'] : 'Select Photo'; ?>: </label>
                                    <label class="col-sm-1 control-label">: </label>
                                    <div class="col-sm-8">
                                        <div id="kv-avatar-errors-1" class="center-block" style="display:none;"></div>                          
                                        <div class="kv-avatar center-block">                            
                                            <input type="file" class="form-control" id="editProductImage" name="editProductImage" style="width:auto;"/>
                                        </div>
                                    </div>
                                </div> 
                                
                                <div class="modal-footer editProductPhotoFooter">
                                    <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> <?php echo isset($lang['close']) ? $lang['close'] : 'Close'; ?></button>
                                    <button type="submit" class="btn btn-success" id="editProductImageBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> <?php echo isset($lang['save_changes']) ? $lang['save_changes'] : 'Save Changes'; ?></button>
                                </div>
                            </form>
                        </div>

                        <div role="tabpanel" class="tab-pane" id="productInfo">
                            <form class="form-horizontal" id="editProductForm" action="php_action/editProduct.php" method="POST">                   
                                <br />
                                <div id="edit-product-messages"></div>

                                <div class="form-group">
                                    <label for="editProductName" class="col-sm-3 control-label"><?php echo isset($lang['product_name']) ? $lang['product_name'] : 'Product Name'; ?>: </label>
                                    <label class="col-sm-1 control-label">: </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="editProductName" name="editProductName" autocomplete="off">
                                    </div>
                                </div> 
                                
                                <div class="form-group">
                                    <label for="editQuantity" class="col-sm-3 control-label"><?php echo isset($lang['quantity']) ? $lang['quantity'] : 'Quantity'; ?>: </label>
                                    <label class="col-sm-1 control-label">: </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="editQuantity" name="editQuantity" autocomplete="off">
                                    </div>
                                </div> 
                                
                                <div class="form-group">
                                    <label for="editRate" class="col-sm-3 control-label"><?php echo isset($lang['rate']) ? $lang['rate'] : 'Rate'; ?>: </label>
                                    <label class="col-sm-1 control-label">: </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="editRate" name="editRate" autocomplete="off">
                                    </div>
                                </div> 
                                
                                <div class="form-group">
                                    <label for="editBrandName" class="col-sm-3 control-label"><?php echo isset($lang['brand_name']) ? $lang['brand_name'] : 'Brand Name'; ?>: </label>
                                    <label class="col-sm-1 control-label">: </label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="editBrandName" name="editBrandName">
                                            <option value=""><?php echo isset($lang['select']) ? $lang['select'] : '~~SELECT~~'; ?></option>
                                            <?php 
                                            $sql = "SELECT brand_id, brand_name, brand_active, brand_status FROM brands WHERE brand_status = 1 AND brand_active = 1";
                                            $result = $connect->query($sql);
                                            while($row = $result->fetch_array()) {
                                                echo "<option value='".$row[0]."'>".$row[1]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div> 
                                
                                <div class="form-group">
                                    <label for="editCategoryName" class="col-sm-3 control-label"><?php echo isset($lang['category_name']) ? $lang['category_name'] : 'Category Name'; ?>: </label>
                                    <label class="col-sm-1 control-label">: </label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="editCategoryName" name="editCategoryName" >
                                            <option value=""><?php echo isset($lang['select']) ? $lang['select'] : '~~SELECT~~'; ?></option>
                                            <?php 
                                            $sql = "SELECT categories_id, categories_name, categories_active, categories_status FROM categories WHERE categories_status = 1 AND categories_active = 1";
                                            $result = $connect->query($sql);
                                            while($row = $result->fetch_array()) {
                                                echo "<option value='".$row[0]."'>".$row[1]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div> 
                                
                                <div class="form-group">
                                    <label for="editProductStatus" class="col-sm-3 control-label"><?php echo isset($lang['status']) ? $lang['status'] : 'Status'; ?>: </label>
                                    <label class="col-sm-1 control-label">: </label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="editProductStatus" name="editProductStatus">
                                            <option value=""><?php echo isset($lang['select']) ? $lang['select'] : '~~SELECT~~'; ?></option>
                                            <option value="1"><?php echo isset($lang['available']) ? $lang['available'] : 'Available'; ?></option>
                                            <option value="2"><?php echo isset($lang['not_available']) ? $lang['not_available'] : 'Not Available'; ?></option>
                                        </select>
                                    </div>
                                </div> 
                                
                                <div class="modal-footer editProductFooter">
                                    <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> <?php echo isset($lang['close']) ? $lang['close'] : 'Close'; ?></button>
                                    <button type="submit" class="btn btn-success" id="editProductBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> <?php echo isset($lang['save_changes']) ? $lang['save_changes'] : 'Save Changes'; ?></button>
                                </div> 
                            </form> 
                        </div>
                    </div>
                </div>
            </div> 
        </div> 
    </div> 
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="removeProductModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> <?php echo isset($lang['remove_product']) ? $lang['remove_product'] : 'Remove Product'; ?></h4>
            </div>
            <div class="modal-body">
                <div class="removeProductMessages"></div>
                <p><?php echo isset($lang['remove_confirm']) ? $lang['remove_confirm'] : 'Do you really want to remove ?'; ?></p>
            </div>
            <div class="modal-footer removeProductFooter">
                <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> <?php echo isset($lang['close']) ? $lang['close'] : 'Close'; ?></button>
                <button type="button" class="btn btn-primary" id="removeProductBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> <?php echo isset($lang['save_changes']) ? $lang['save_changes'] : 'Save changes'; ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="importExcelModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" action="php_action/importProduct.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="glyphicon glyphicon-import"></i> Import Product Data (Import Excel)</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="excelFile" class="col-sm-4 control-label">Select Excel File: </label>
                        <div class="col-sm-8">
                            <input type="file" class="form-control" id="excelFile" name="excelFile" accept=".xls,.xlsx" required />
                            <p class="help-block" style="font-size: 12px; color: #666;">* File must have columns in order: Photo, Product Name, Rate, Quantity, Brand, Category, Status</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" name="btnImport" class="btn btn-primary">Import Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="custom/js/product.js"></script>

<script type="text/javascript">
    // 1. Export Product to Excel
    function exportProductToExcel() {
        var tableDom = document.getElementById('manageProductTable');
        if (!tableDom) {
            alert("Product data table not found!");
            return;
        }

        // Create table header (excluding Options column)
        var headerText = "<tr>";
        var headers = tableDom.querySelectorAll('thead th');
        for (var i = 0; i < headers.length - 1; i++) {
            headerText += "<th style='background-color: #10b981; color: white; font-weight: bold; padding: 8px; border: 1px solid #cbd5e1;'>" + headers[i].innerText.trim() + "</th>";
        }
        headerText += "</tr>";

        // Fetch data rows from tbody
        var bodyText = "";
        var rows = tableDom.querySelectorAll('tbody tr');
        
        rows.forEach(function(row) {
            if (row.cells.length <= 1) return; 

            bodyText += "<tr>";
            for (var j = 0; j < row.cells.length - 1; j++) {
                var cell = row.cells[j];
                
                if (j === 0 || cell.querySelector('img')) {
                    bodyText += "<td style='border: 1px solid #cbd5e1; padding: 8px; text-align: center;'>[Image]</td>";
                } else {
                    bodyText += "<td style='border: 1px solid #cbd5e1; padding: 8px;'>" + cell.innerText.trim() + "</td>";
                }
            }
            bodyText += "</tr>";
        });

        var finalTableHTML = headerText + bodyText;
        var uri = 'data:application/vnd.ms-excel;base64,';
        var template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><meta charset="utf-8"></head><body><table>{table}</table></body></html>'; 
        var base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) };
        var format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) };

        var ctx = { worksheet: 'Product_List', table: finalTableHTML };
        var link = document.createElement("a");
        link.download = "Inventory report.xls";
        link.href = uri + base64(format(template, ctx));
        link.click();
    }

    // 2. Print or Save as PDF
    function printProductTable() {
        var tableDom = document.getElementById('manageProductTable');
        if (!tableDom) {
            alert("Product data table not found!");
            return;
        }

        var printContent = "<table><thead><tr>";
        var headers = tableDom.querySelectorAll('thead th');
        for (var i = 0; i < headers.length - 1; i++) {
            printContent += "<th>" + headers[i].innerText.trim() + "</th>";
        }
        printContent += "</tr></thead><tbody>";

        var rows = tableDom.querySelectorAll('tbody tr');
        rows.forEach(function(row) {
            if (row.cells.length <= 1) return;

            printContent += "<tr>";
            for (var j = 0; j < row.cells.length - 1; j++) {
                var cell = row.cells[j];
                if (j === 0 || cell.querySelector('img')) {
                    printContent += "<td style='text-align: center;'>[Image]</td>";
                } else {
                    printContent += "<td>" + cell.innerText.trim() + "</td>";
                }
            }
            printContent += "</tr>";
        });
        printContent += "</tbody></table>";

        var newWin = window.open("");
        newWin.document.write('<html><head><title>Product Stock Report</title>');
        newWin.document.write('<style>table { width: 100%; border-collapse: collapse; margin-top: 20px; } th, td { border: 1px solid #cbd5e1; padding: 10px; text-align: left; font-family: sans-serif; font-size: 13px; } th { background-color: #f1f5f9; font-weight: bold; }</style>');
        newWin.document.write('</head><body>');
        newWin.document.write('<h2 style="font-family: sans-serif; text-align: center;">Product Stock Report</h2>');
        newWin.document.write(printContent);
        newWin.document.write('</body></html>');
        newWin.document.close();
        
        setTimeout(function(){ 
            newWin.print();
            newWin.close();
        }, 250);
    }
</script>

<?php require_once 'includes/footer.php'; ?>
