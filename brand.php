<?php require_once 'includes/header.php'; ?>

<div class="row">
    <div class="col-md-12">

        <ol class="breadcrumb">
          <li><a href="dashboard.php"><?php echo isset($lang['dashboard']) ? $lang['dashboard'] : 'Home'; ?></a></li>      
          <li class="active"><?php echo isset($lang['brand']) ? $lang['brand'] : 'Brand'; ?></li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="page-heading"> 
                    <i class="glyphicon glyphicon-edit"></i> 
                    <?php echo isset($lang['manage_brand']) ? $lang['manage_brand'] : 'Manage Brand'; ?>
                </div>
            </div> <div class="panel-body">
                <div class="remove-messages"></div>

                <div class="div-action pull pull-right" style="padding-bottom:20px;">
                    <button class="btn btn-default button1" data-toggle="modal" data-target="#addBrandModel"> 
                        <i class="glyphicon glyphicon-plus-sign"></i> 
                        <?php echo isset($lang['add_brand']) ? $lang['add_brand'] : 'Add Brand'; ?> 
                    </button>
                </div> <table class="table" id="manageBrandTable">
                    <thead>
                        <tr>                            
                            <th><?php echo isset($lang['brand_name']) ? $lang['brand_name'] : 'Brand Name'; ?></th>
                            <th><?php echo isset($lang['status']) ? $lang['status'] : 'Status'; ?></th>
                            <th style="width:15%;"><?php echo isset($lang['options']) ? $lang['options'] : 'Options'; ?></th>
                        </tr>
                    </thead>
                </table>
                </div> </div> </div> </div> <div class="modal fade" id="addBrandModel" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
        
        <form class="form-horizontal" id="submitBrandForm" action="php_action/createBrand.php" method="POST">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">
                <i class="fa fa-plus"></i> 
                <?php echo isset($lang['add_brand']) ? $lang['add_brand'] : 'Add Brand'; ?>
            </h4>
          </div>
          <div class="modal-body">

            <div id="add-brand-messages"></div>

            <div class="form-group">
                <label for="brandName" class="col-sm-3 control-label"><?php echo isset($lang['brand_name']) ? $lang['brand_name'] : 'Brand Name'; ?>: </label>
                <label class="col-sm-1 control-label">: </label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="brandName" placeholder="<?php echo isset($lang['brand_name']) ? $lang['brand_name'] : 'Brand Name'; ?>" name="brandName" autocomplete="off">
                    </div>
            </div> <div class="form-group">
                <label for="brandStatus" class="col-sm-3 control-label"><?php echo isset($lang['status']) ? $lang['status'] : 'Status'; ?>: </label>
                <label class="col-sm-1 control-label">: </label>
                    <div class="col-sm-8">
                      <select class="form-control" id="brandStatus" name="brandStatus">
                        <option value=""><?php echo isset($lang['select']) ? $lang['select'] : '~~SELECT~~'; ?></option>
                        <option value="1"><?php echo isset($lang['available']) ? $lang['available'] : 'Available'; ?></option>
                        <option value="2"><?php echo isset($lang['not_available']) ? $lang['not_available'] : 'Not Available'; ?></option>
                      </select>
                    </div>
            </div> </div> <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo isset($lang['close']) ? $lang['close'] : 'Close'; ?></button>
            <button type="submit" class="btn btn-primary" id="createBrandBtn" data-loading-text="Loading..." autocomplete="off"><?php echo isset($lang['save_changes']) ? $lang['save_changes'] : 'Save Changes'; ?></button>
          </div>
          </form>
         </div>
    </div>
  </div>
<div class="modal fade" id="editBrandModel" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
        
        <form class="form-horizontal" id="editBrandForm" action="php_action/editBrand.php" method="POST">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">
                <i class="fa fa-edit"></i> 
                <?php echo isset($lang['edit_brand']) ? $lang['edit_brand'] : 'Edit Brand'; ?>
            </h4>
          </div>
          <div class="modal-body">

            <div id="edit-brand-messages"></div>

            <div class="modal-loading div-hide" style="width:50px; margin:auto;padding-top:50px; padding-bottom:50px;">
                <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                <span class="sr-only">Loading...</span>
            </div>

            <div class="edit-brand-result">
                <div class="form-group">
                    <label for="editBrandName" class="col-sm-3 control-label"><?php echo isset($lang['brand_name']) ? $lang['brand_name'] : 'Brand Name'; ?>: </label>
                    <label class="col-sm-1 control-label">: </label>
                        <div class="col-sm-8">
                          <input type="text" class="form-control" id="editBrandName" placeholder="<?php echo isset($lang['brand_name']) ? $lang['brand_name'] : 'Brand Name'; ?>" name="editBrandName" autocomplete="off">
                        </div>
                </div> <div class="form-group">
                    <label for="editBrandStatus" class="col-sm-3 control-label"><?php echo isset($lang['status']) ? $lang['status'] : 'Status'; ?>: </label>
                    <label class="col-sm-1 control-label">: </label>
                        <div class="col-sm-8">
                          <select class="form-control" id="editBrandStatus" name="editBrandStatus">
                            <option value=""><?php echo isset($lang['select']) ? $lang['select'] : '~~SELECT~~'; ?></option>
                            <option value="1"><?php echo isset($lang['available']) ? $lang['available'] : 'Available'; ?></option>
                            <option value="2"><?php echo isset($lang['not_available']) ? $lang['not_available'] : 'Not Available'; ?></option>
                          </select>
                        </div>
                </div> </div>                    
              </div> <div class="modal-footer editBrandFooter">
            <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> <?php echo isset($lang['close']) ? $lang['close'] : 'Close'; ?></button>
            <button type="submit" class="btn btn-success" id="editBrandBtn" data-loading-text="Loading..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> <?php echo isset($lang['save_changes']) ? $lang['save_changes'] : 'Save Changes'; ?></button>
          </div>
          </form>
         </div>
    </div>
  </div>
<div class="modal fade" tabindex="-1" role="dialog" id="removeMemberModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> <?php echo isset($lang['remove_brand']) ? $lang['remove_brand'] : 'Remove Brand'; ?></h4>
      </div>
      <div class="modal-body">
        <p><?php echo isset($lang['remove_confirm']) ? $lang['remove_confirm'] : 'Do you really want to remove ?'; ?></p>
      </div>
      <div class="modal-footer removeBrandFooter">
        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> <?php echo isset($lang['close']) ? $lang['close'] : 'Close'; ?></button>
        <button type="button" class="btn btn-primary" id="removeBrandBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> <?php echo isset($lang['save_changes']) ? $lang['save_changes'] : 'Save changes'; ?></button>
      </div>
    </div></div></div><script src="custom/js/brand.js"></script>

<?php require_once 'includes/footer.php'; ?>