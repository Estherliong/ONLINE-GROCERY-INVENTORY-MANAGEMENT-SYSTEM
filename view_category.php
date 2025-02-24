<?php
session_start();
include('connection.php');
include('header.php');

// Add New Product Category
if(isset($_POST['addbtn'])){
  $cname = $_POST['categoryname'];

  $chkcategory = mysqli_query($connect,"SELECT * FROM category where `category_name` = '$cname'");
  if(mysqli_num_rows($chkcategory)>0)
  {
    echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire("Failed", "Category Name exists", "info");
            });
          </script>';
  }
  else
  {
    $insertsql = "INSERT INTO `category`(`category_name`) VALUES ('$cname')";
    $insertsql_run = mysqli_query($connect,"$insertsql");
    if($insertsql_run){
      echo '<script>
              document.addEventListener("DOMContentLoaded", function() {
                  Swal.fire("Saved", "Category added", "success");
              });
            </script>';  
    }
  }
}

// Edit category & update
if(isset($_POST['savebtn'])){
  $cname = $_POST['categoryname'];
  $cid = $_POST['categoryid'];

  $chkcategory = mysqli_query($connect,"SELECT * FROM category where `category_id` = '$cid'");
  if(mysqli_num_rows($chkcategory)>0)
  {
    $update_sql = mysqli_query($connect,"UPDATE `category` SET `category_name`='$cname' WHERE `category_id` = '$cid'");
    if($update_sql){
      echo '<script>
              document.addEventListener("DOMContentLoaded", function() {
                  Swal.fire("Updated Category Successfully", "", "success");
              });
            </script>';
    }
  }
  else
  {
    echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire("Please Type Correct Information", "", "error");
            });
          </script>';
  }
}

// Disable the Category 
if(isset($_POST['disablebtn'])){
  $remove_id = $_POST['categoryid'];
  $chk_category = mysqli_query($connect, "SELECT * FROM item WHERE category_id = '$remove_id'");
  if(mysqli_num_rows($chk_category)>0)
  {
    echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire("Cannot Delete Category.", "There are products inside the category", "info");
            });
          </script>';  
  }
  else
  {
    $delete_sql = mysqli_query($connect,"UPDATE `category` SET `status`= 0 WHERE `category_id` = '$remove_id'");
    if($delete_sql)
    {
      echo '<script>
              document.addEventListener("DOMContentLoaded", function() {
                  Swal.fire("Category Disabled.","", "success");
              });
            </script>';  
    }
  }
}

// Restore category
if(isset($_POST['restorebtn'])){
  $restore_id = $_POST['categoryid'];
  $chk_category = mysqli_query($connect, "SELECT * FROM category WHERE category_id = '$restore_id'");
  if(mysqli_num_rows($chk_category)>0)
  {
    $restore_sql = mysqli_query($connect,"UPDATE `category` SET `status`=1 WHERE `category_id` = '$restore_id'");
    if($restore_sql)
    {
      echo '<script>
              document.addEventListener("DOMContentLoaded", function() {
                  Swal.fire("Category Restored Successfully.","", "success");
              });
            </script>';  
    }  
  }
}

// Delete category
if(isset($_POST['deletebtn'])){
    $remove_id = $_POST['categoryid'];
    $chk_category = mysqli_query($connect, "SELECT * FROM item WHERE category_id = '$remove_id'");
    if(mysqli_num_rows($chk_category)>0)
    {
      echo '<script>
              document.addEventListener("DOMContentLoaded", function() {
                  Swal.fire("Cannot Delete Category.", "There are products inside the category", "info");
              });
            </script>';  
    }
    else
    {
      $delete_sql = mysqli_query($connect,"DELETE FROM `category` WHERE `category_id` = '$remove_id'");
      if($delete_sql)
      {
        echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire("Category Deleted.","", "success");
                });
              </script>';  
      }
    }
}

include('navigation.php'); 
?>

<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>Category</title>
    <link rel="icon" href="../image/logo.png">
    <!--ICON-->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700" rel="stylesheet">
    <link href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome.min.css" rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="theme-assets/css/vendors.css">
    <!-- END VENDOR CSS-->
    <!-- BEGIN CSS-->
    <link rel="stylesheet" type="text/css" href="theme-assets/css/app-lite.css">
    <!-- END CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="theme-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="theme-assets/css/core/colors/palette-gradient.css">
    <!-- <link rel="stylesheet" type="text/css" href="theme-assets/css/pages/dashboard-ecommerce.css"> -->
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <!-- END Custom CSS-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="vertical-layout vertical-menu 2-columns menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu" data-color="bg-chartbg" data-col="2-columns">
    <div class="app-content content">
        <div class="content-wrapper mt-3"></div>
        <div class="content-header row"></div>
        <div class="content-body">
            <div class="row match-height">
                <div class="col-12">
                    <div class="container-fluid">
                        <table class="table table-striped fs-5" width="100%" id="myTable">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="text-center">No</th>
                                    <th scope="col" class="text-center">Category Name</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $viewcat = "SELECT * FROM `category`";
                                    $sql_run = mysqli_query($connect, $viewcat);
                                    $count = 1;
                                    if(mysqli_num_rows($sql_run) > 0)
                                    {
                                        foreach($sql_run as $cate)
                                        {
                                            ?>
                                            <tr>
                                                <th scope="row" class="text-center"><?= $count++ ?></th>
                                                <td class="text-center"><?= $cate['category_name'] ?></td>
                                                <td class="text-center">
                                                <?php
                                                    if($cate['status'] == 1)
                                                    {
                                                        echo "Active";
                                                    }
                                                    else
                                                    {
                                                        echo "Inactive";
                                                    }
                                                ?>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#editModal<?= $cate['category_id'] ?>">Edit</button>
                                                    <!-- Modal Edit Category -->
                                                    <div class="modal fade" id="editModal<?= $cate['category_id'] ?>" tabindex="-1" aria-labelledby="editLabel<?= $cate['category_id'] ?>" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editLabel<?= $cate['category_id'] ?>">Edit Product Category</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-start">
                                                                    <form method="POST" enctype="multipart/form-data">
                                                                        <!-- Form Row-->
                                                                        <div class="row gx-3 mb-3">
                                                                            <div class="col-md-6">
                                                                                <label class="small mb-1" for="inputcategoryid<?= $cate['category_id'] ?>">Category ID</label>
                                                                                <input type="hidden" id="inputcategoryid<?= $cate['category_id'] ?>" name="categoryid" value="<?= $cate['category_id'] ?>">
                                                                                <input class="form-control" id="inputcategoryid<?= $cate['category_id'] ?>" type="text" placeholder="Category ID" value="<?= $cate['category_id'] ?>" disabled>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <label class="small mb-1" for="inputcategoryname<?= $cate['category_id'] ?>">Category Name</label>
                                                                                <input class="form-control" name="categoryname" id="inputcategoryname<?= $cate['category_id'] ?>" type="text" placeholder="Enter Category Name" value="<?= $cate['category_name'] ?>" required>
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <!-- Save changes button-->
                                                                    <button class="btn btn-primary" name="savebtn" type="submit">Save Change</button>
                                                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                        if($cate['status'] == 1)
                                                        {
                                                    ?>
                                                    <form method="POST" style="display:inline;">
                                                        <input type="hidden" name="categoryid" value="<?= $cate['category_id'] ?>">
                                                        <button type="submit" name="disablebtn" class="btn btn-warning text-white" onclick="return confirm('Disable <?= $cate['category_name'] ?> From Category?')">Disable</button>
                                                    </form>
                                                    <?php
                                                        }
                                                        else
                                                        {
                                                    ?>
                                                    <form method="POST" style="display:inline;">
                                                        <input type="hidden" name="categoryid" value="<?= $cate['category_id'] ?>">
                                                        <button type="submit" name="restorebtn" class="btn btn-success" onclick="return confirm('Restore <?= $cate['category_name'] ?> From Category?')">Restore</button>
                                                    </form>
                                                    <?php
                                                        }
                                                    ?>
                                                    <form method="POST" style="display:inline;">
                                                        <input type="hidden" name="categoryid" value="<?= $cate['category_id'] ?>">
                                                        <button type="submit" name="deletebtn" class="btn btn-danger" onclick="return confirm('Delete <?= $cate['category_name'] ?> From Category?')">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                        
                        <button class="btn btn-primary mb-4" name="modaladdbtn" type="button" data-bs-toggle="modal" data-bs-target="#addModal">Add Category</button>
                        <!-- Modal Add Category -->
                        <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addLabel">Add New Product Category</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-start">
                                        <form method="POST" enctype="multipart/form-data">
                                            <!-- Form Row-->
                                            <?php
                                                $sql_run = mysqli_query($connect, $viewcat);
                                                $count = mysqli_num_rows($sql_run) + 1;
                                            ?>
                                            <div class="row gx-3 mb-3">
                                                <div class="col-md-6">
                                                    <label class="small mb-1" for="inputcategoryid">Category ID</label>
                                                    <input class="form-control" name="categoryid" id="inputcategoryid" type="text" placeholder="Category ID" value="<?= $count ?>" disabled>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="small mb-1" for="inputcategoryname">Category Name</label>
                                                    <input class="form-control" name="categoryname" id="inputcategoryname" type="text" placeholder="Enter Category Name" value="" required>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <!-- Save changes button-->
                                        <button class="btn btn-primary" name="addbtn" type="submit">Add Category</button>
                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>             
                    </div>
                </div>
            </div>
        </div> <!------- close div for app-content------>
    </div>

    <!-- ////////////////////////////////////////////////////////////////////////////-->

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- BEGIN VENDOR JS-->
    <script src="theme-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
    <!-- BEGIN VENDOR JS-->
    <script src="theme-assets/js/core/app-menu-lite.js" type="text/javascript"></script>
    <script src="theme-assets/js/core/app-lite.js" type="text/javascript"></script>
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="theme-assets/vendors/js/charts/chartist.min.js" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN JS-->
    <script src="theme-assets/js/core/app-menu-lite.js" type="text/javascript"></script>
    <script src="theme-assets/js/core/app-lite.js" type="text/javascript"></script>
    <!-- END JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    <script src="theme-assets/js/scripts/pages/dashboard-lite.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS-->
    <script src="search.js"></script>
</body>
</html>