<?php require_once 'includes/header.php'; ?>

<div class="row">
    <div class="col-md-12">

        <ol class="breadcrumb">
          <li><a href="dashboard.php"><?php echo isset($lang['dashboard']) ? $lang['dashboard'] : 'Home'; ?></a></li>       
          <li class="active"><?php echo isset($lang['category']) ? $lang['category'] : 'Category'; ?></li>
        </ol>

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="page-heading"> 
                    <i class="glyphicon glyphicon-edit"></i> 
                    <?php echo isset($lang['manage_category']) ? $lang['manage_category'] : 'Manage Categories'; ?>
                </div>
            </div> <div class="panel-body">

                <div class="remove-messages"></div>

                <div class="div-action pull pull-right" style="padding-bottom:20px;">
                    <button class="btn btn-default button1" data-toggle="modal" id="addCategoriesModalBtn" data-target="#addCategoriesModal"> 
                        <i class="glyphicon glyphicon-plus-sign"></i> 
                        <?php echo isset($lang['add_category']) ? $lang['add_category'] : 'Add Categories'; ?> 
                    </button>
                </div> <table class="table" id="manageCategoriesTable">
                    <thead>
                        <tr>                            
                            <th><?php echo isset($lang['category_name']) ? $lang['category_name'] : 'Categories Name'; ?></th>
                            <th><?php echo isset($lang['status']) ? $lang['status'] : 'Status'; ?></th>
                            <th style="width:15%;"><?php echo isset($lang['options']) ? $lang['options'] : 'Options'; ?></th>
                        </tr>
                    </thead>
                </table>
                </div> </div> </div> </div> <div class="modal fade" id="addCategoriesModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">

        <form class="form-horizontal" id="submitCategoriesForm" action="php_action/createCategories.php" method="POST">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">
                <i class="fa fa-plus"></i> 
                <?php echo isset($lang['add_category']) ? $lang['add_brand'] : 'Add Categories'; ?>
            </h4>
          </div>
          <div class="modal-body">

            <div id="add-categories-messages"></div>

            <div class="form-group">
                <label for="categoriesName" class="col-sm-4 control-label"><?php echo isset($lang['category_name']) ? $lang['category_name'] : 'Categories Name'; ?>: </label>
                <label class="col-sm-1 control-label">: </label>
                    <div class="col-sm-7">
                      <input type="text" class="form-control" id="categoriesName" placeholder="<?php echo isset($lang['category_name']) ? $lang['category_name'] : 'Categories Name'; ?>" name="categoriesName" autocomplete="off">
                    </div>
            </div> <div class="form-group">
                <label for="categoriesStatus" class="col-sm-4 control-label"><?php echo isset($lang['status']) ? $lang['status'] : 'Status'; ?>: </label>
                <label class="col-sm-1 control-label">: </label>
                    <div class="col-sm-7">
                      <select class="form-control" id="categoriesStatus" name="categoriesStatus">
                        <option value=""><?php echo isset($lang['select']) ? $lang['select'] : '~~SELECT~~'; ?></option>
                        <option value="1"><?php echo isset($lang['available']) ? $lang['available'] : 'Available'; ?></option>
                        <option value="2"><?php echo isset($lang['not_available']) ? $lang['not_available'] : 'Not Available'; ?></option>
                      </select>
                    </div>
            </div> </div> <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> <?php echo isset($lang['close']) ? $lang['close'] : 'Close'; ?></button>
            <button type="submit" class="btn btn-primary" id="createCategoriesBtn" data-loading-text="Loading..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> <?php echo isset($lang['save_changes']) ? $lang['save_changes'] : 'Save Changes'; ?></button>
          </div> </form> </div> </div> </div> 
<div class="modal fade" id="editCategoriesModal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
        
        <form class="form-horizontal" id="editCategoriesForm" action="php_action/editCategories.php" method="POST">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">
                <i class="fa fa-edit"></i> 
                <?php echo isset($lang['edit_category']) ? $lang['edit_category'] : 'Edit Category'; ?>
            </h4>
          </div>
          <div class="modal-body">

            <div id="edit-categories-messages"></div>

            <div class="modal-loading div-hide" style="width:50px; margin:auto;padding-top:50px; padding-bottom:50px;">
                <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                <span class="sr-only">Loading...</span>
            </div>

            <div class="edit-categories-result">
                <div class="form-group">
                    <label for="editCategoriesName" class="col-sm-4 control-label"><?php echo isset($lang['category_name']) ? $lang['category_name'] : 'Categories Name'; ?>: </label>
                    <label class="col-sm-1 control-label">: </label>
                        <div class="col-sm-7">
                          <input type="text" class="form-control" id="editCategoriesName" placeholder="<?php echo isset($lang['category_name']) ? $lang['category_name'] : 'Categories Name'; ?>" name="editCategoriesName" autocomplete="off">
                        </div>
                </div> <div class="form-group">
                    <label for="editCategoriesStatus" class="col-sm-4 control-label"><?php echo isset($lang['status']) ? $lang['status'] : 'Status'; ?>: </label>
                    <label class="col-sm-1 control-label">: </label>
                        <div class="col-sm-7">
                          <select class="form-control" id="editCategoriesStatus" name="editCategoriesStatus">
                            <option value=""><?php echo isset($lang['select']) ? $lang['select'] : '~~SELECT~~'; ?></option>
                            <option value="1"><?php echo isset($lang['available']) ? $lang['available'] : 'Available'; ?></option>
                            <option value="2"><?php echo isset($lang['not_available']) ? $lang['not_available'] : 'Not Available'; ?></option>
                          </select>
                        </div>
                </div> </div>                    
              </div> <div class="modal-footer editCategoriesFooter">
            <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> <?php echo isset($lang['close']) ? $lang['close'] : 'Close'; ?></button>
            <button type="submit" class="btn btn-success" id="editCategoriesBtn" data-loading-text="Loading..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> <?php echo isset($lang['save_changes']) ? $lang['save_changes'] : 'Save Changes'; ?></button>
          </div>
          </form>
         </div>
    </div>
  </div>
<div class="modal fade" tabindex="-1" role="dialog" id="removeCategoriesModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> <?php echo isset($lang['remove_category']) ? $lang['remove_category'] : 'Remove Category'; ?></h4>
      </div>
      <div class="modal-body">
        <p><?php echo isset($lang['remove_confirm']) ? $lang['remove_confirm'] : 'Do you really want to remove ?'; ?></p>
      </div>
      <div class="modal-footer removeCategoriesFooter">
        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> <?php echo isset($lang['close']) ? $lang['close'] : 'Close'; ?></button>
        <button type="button" class="btn btn-primary" id="removeCategoriesBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> <?php echo isset($lang['save_changes']) ? $lang['save_changes'] : 'Save changes'; ?></button>
      </div>
    </div></div></div><script src="custom/js/categories.js"></script>

<?php require_once 'includes/footer.php'; ?>