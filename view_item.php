<?php
session_start();
include('connection.php');
include('header.php');

?>
<head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
  <body class="vertical-layout vertical-menu 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu" data-color="bg-chartbg" data-col="2-columns">
           
  <?php
  // View and Edit item Details
if(isset($_POST['editsavebtn'])){
  $iname = $_POST['itemname'];
  $idescription= $_POST['description'];
  $idesc = mysqli_real_escape_string($connect, $idescription);
  $iprice = $_POST['sprice'];
  $cprice = $_POST['cprice'];
  $ictg = $_POST['category'];
  $id = $_POST['itemid'];


  if(isset($_FILES["image"]["name"])){
  $imageName = $_FILES["image"]["name"];
  $imageSize = $_FILES["image"]["size"];
  $tmpName = $_FILES["image"]["name"];

  // Image validation
  $validImageExtension = ['jpg', 'jpeg', 'png', 'webp'];
  $imageExtension = explode('.', $imageName);
  $imageExtension = strtolower(end($imageExtension));
  if (!in_array($imageExtension, $validImageExtension)){
    echo '<script type="text/javascript">swal("", "Invalid Image Extension", "Error");</script>';
  }
  elseif ($imageSize > 1200000){
    echo '<script type="text/javascript">swal("", "Image Size Too Large", "Error");</script>';
  }
  else{
    $newImageName = $imageName; // Generate new image name
    //move_uploaded_file($tmpName, './image/' .$newImageName);
    $fnm = $_FILES["image"]["name"];
    $dst="../FYP/image/upload_image/" .$fnm;
    move_uploaded_file($_FILES["image"]["tmp_name"],$dst);
    $imgudp = "UPDATE `item` SET `item_image`='$newImageName' WHERE `item_id`='$id'";
    $imgudp_run = mysqli_query($connect,"$imgudp");
  }
  }

  $udpsql = "UPDATE `item` SET `item_name`='$iname',`item_price`='$iprice',`description`='$idesc',`category_id`='$ictg',`item_cost` = '$cprice' WHERE `item_id`='$id'";
  $udpsql_run = mysqli_query($connect,"$udpsql");

    if($udpsql_run || $imgudp_run){
      echo '<script type="text/javascript">swal("Saved", "New Record Saved", "success");</script>';  
  }
  
}



//Add Item
if(isset($_POST['addbtn'])){
  $iname = $_POST['itemname'];
  $iprice = $_POST['sprice'];
  $cprice = $_POST['cprice'];
  $icatg = $_POST['category'];
  $ictg = mysqli_real_escape_string($connect, $icatg);
  $idescription= $_POST['description'];
  $idesc = mysqli_real_escape_string($connect, $idescription);
  $opening = $_POST['opening_stock']; 
  $current = $opening;
  $id = $_POST['itemid'];


  if(isset($_FILES["aimage"]["name"])){
  $imageName = $_FILES["aimage"]["name"];
  $imageSize = $_FILES["aimage"]["size"];
  $tmpName = $_FILES["aimage"]["name"];

  // Image validation
  $validImageExtension = ['jpg', 'jpeg', 'png'];
  $imageExtension = explode('.', $imageName);
  $imageExtension = strtolower(end($imageExtension));
  if (!in_array($imageExtension, $validImageExtension)){
    echo '<script type="text/javascript">swal("", "Invalid Image Extension", "Error");</script>';
  }
  elseif ($imageSize > 1200000){
    echo '<script type="text/javascript">swal("", "Image Size Too Large", "Error");</script>';
  }
  else{
    $newImageName = $imageName; // Generate new image name
    //move_uploaded_file($tmpName, './image/' .$newImageName);
    $fnm = $_FILES["aimage"]["name"];
    $dst="../FYP/image/upload_image/".$fnm;
    move_uploaded_file($_FILES["aimage"]["tmp_name"],$dst);

    $chkitem = mysqli_query($connect,"SELECT * FROM item where `item_name` = '$iname'");
    if(mysqli_num_rows($chkitem)>0)
    {
      echo '<script type="text/javascript">swal("Failed", "item Name cannot be same", "info");</script>';
    }
    else
    {
      $insertprosql = "INSERT INTO `item`( `item_name`, `item_price`, `item_image`, `description`, `category_id`, `item_cost`,`status`) 
      VALUES ('$iname','$iprice','$newImageName','$idesc','$ictg','$cprice','1') ";
      $insertprosql_run = mysqli_query($connect,"$insertprosql");
      $insertstock = "INSERT INTO `inventory`(`item_id`, `opening_stock`,`current_stock`,'available_stock') VALUES ('$id','$opening','$current',$current)";
      $insertstock_run = mysqli_query($connect,"$insertstock");

      if($insertprosql_run || $insertstock_run){
        echo '<script type="text/javascript">swal("Saved", "New Record Saved", "success");</script>';  
    }
    }
    
  }
  }
}

// Disable the Item
if(isset($_POST['disablebtn'])){
  $item_id = $_POST['itemid'];
  $chk_item = mysqli_query($connect, "SELECT * FROM item WHERE item_id = '$item_id'");
  if(mysqli_num_rows($chk_item) > 0)
  {
    $disable_sql = mysqli_query($connect,"UPDATE `item` SET `status`= 0 WHERE `item_id` = '$item_id'");
    if($disable_sql)
    {
      echo '<script type="text/javascript">swal("Item Disabled.","", "success");</script>';  
    }
  }
}

// Restore Item
if(isset($_POST['restorebtn'])){
  $item_id = $_POST['itemid'];
  $chk_item = mysqli_query($connect, "SELECT * FROM item WHERE item_id = '$item_id'");
  if(mysqli_num_rows($chk_item) > 0)
  {
    $restore_sql = mysqli_query($connect,"UPDATE `item` SET `status`=1 WHERE `item_id` = '$item_id'");
    if($restore_sql)
    {
      echo '<script type="text/javascript">swal("Item Restored Successfully.","", "success");</script>';  
    }  
  }
}

// Delete Item
if(isset($_POST['deletebtn'])){
    $item_id = $_POST['itemid'];
    $chk_item = mysqli_query($connect, "SELECT * FROM item WHERE item_id = '$item_id'");
    if(mysqli_num_rows($chk_item) > 0)
    {
      $delete_sql = mysqli_query($connect,"DELETE FROM `item` WHERE `item_id` = '$item_id'");
      if($delete_sql)
      {
        echo '<script type="text/javascript">swal("Item Deleted.","", "success");</script>';  
      }
    }
}

?>

<?php include('navigation.php'); ?>
        <div class="app-content content">
      <div class="content-wrapper mt-3">
        </div>
        <div class="content-header row">
        </div>
        <div class="content-body">  

    <div class="row match-height">
        <div class="col-12">
            <div class="container-fluid">
            <button class="btn btn-primary mb-4" style="margin-top: 10px" name="modaladdbtn" type="submit" data-bs-toggle="modal" data-bs-target="#addModal" >Add Item</button>

            <!-- Search Box -->
            <div class="mb-4" style="max-width: 300px; float: right;">
        <input type="text" id="searchBox" class="form-control" placeholder="Search for products...">
            </div>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-available" role="tabpanel" aria-labelledby="pills-available-tab">
                <table class="table table-striped" width="100%" id="myTable">
              <thead class="table-dark">
                <tr>
                  <th scope="col">No</th>
                  <th scope="col">Image</th>
                  <th scope="col">Name</th>
                  <th scope="col">Price</th>
                  <th scope="col">Category</th>
                  <th scope="col">Cost</th>
                  <th scope="col">Opening Stock</th>
                  <th scope="col">Quantity On Hand</th>
                  <th scope="col">Status</th> 
                  <th colspan="2">Action</th>
                </tr>
              </thead>

              
<tbody>
    <?php
    $viewitem = "SELECT * FROM `item` ";
    $sql_run = mysqli_query($connect, "$viewitem");

    if (mysqli_num_rows($sql_run) > 0) {
        $i = 1;
        while ($row = mysqli_fetch_assoc($sql_run)) {
            $cat = $row['category_id'];
            $ctg = "SELECT * FROM category WHERE category_id = '$cat'";
            $ctg_run = mysqli_query($connect, "$ctg");
            $category = mysqli_fetch_assoc($ctg_run);
            $id = $row['item_id'];
            $inv = "SELECT * FROM inventory WHERE item_id = '$id'";
            $inv_run = mysqli_query($connect, "$inv");
            $inventory = mysqli_fetch_assoc($inv_run);
    ?>
            <tr>
                <th scope="row"><?php echo $i++; ?></th>
                <td><img src="../FYP/image/upload_image/<?php echo $row['item_image']; ?>" alt="" height="80" width="80"></td>
                <td class="fs-5 "><?php echo $row['item_name'] ?></td>
                <td class="fs-5 "><?php echo "RM" . number_format((float)$row['item_price'], 2, '.', ''); ?></td>
                <td class="fs-5 "><?php echo $category['category_name'] ?></td>
                <td class="fs-5 "><?php echo "RM" . number_format((float)$row['item_cost'], 2, '.', ''); ?></td>
                <td class="fs-5 "><?php echo $inventory['opening_stock'] ?></td>
                <td class="fs-5 "><?php echo $inventory['current_stock'] ?></td>
                <td class="fs-5 ">
                    <?php
                    if ($row['status'] == 1) {
                        echo "Active";
                    } else {
                        echo "Inactive";
                    }
                    ?>
                </td>
                <td>
                    <!-- Edit Button -->
                    <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['item_id'] ?>">Edit</button>
                    <!-- Edit Modal -->
                    <div class="modal fade " id="editModal<?php echo $row['item_id'] ?>" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content ">
                                <div class="modal-header">
                                    <h5 class="modal-title " id="editLabel"><?php echo "Item Detail"; ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-start">
                                    <form method='POST' enctype="multipart/form-data">
                                        <!-- Form Row-->
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="inputitemID">Item ID</label>
                                                <input class="form-control" name="itemid" id="inputitemID" type="text" placeholder="Item ID" value="<?php echo $row['item_id']; ?>" readonly>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small mb-1" for="inputitemname">Item Name</label>
                                                <input class="form-control" name="itemname" id="inputitemname" type="text" placeholder="Item Name" value="<?php echo $row['item_name']; ?>">
                                            </div>
                                        </div>
                                        <!-- Form Row -->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="description">Description</label>
                                            <textarea class="form-control" rows="3" name="description" id="itemdesc<?php echo $row['item_id']; ?>" placeholder="Description"><?php echo $row['description']; ?></textarea>
                                            <script>
                                                CKEDITOR.replace('itemdesc<?php echo $row['item_id']; ?>');
                                            </script>
                                        </div>
                                        <div class="row gx-3 mb-3">
                                            <div class="col-md-4">
                                                <label class="small mb-1" for="Category">Category</label>
                                                <select class="form-select" name="category" value="<?php $row['category_id']; ?>">
                                                    <?php
                                                    $sqlctg = "SELECT * FROM category WHERE status = 1;";
                                                    $sqlctg_run = mysqli_query($connect, "$sqlctg");
                                                    $icat = $row['category_id'];
                                                    if (mysqli_num_rows($sqlctg_run) > 0) {
                                                        if ($icat == null) {
                                                    ?>
                                                            <option selected>NULL </option>
                                                            <?php
                                                        }

                                                        while ($ctg = mysqli_fetch_assoc($sqlctg_run)) {
                                                            $cname = $ctg['category_id'];
                                                            if ($icat == $cname) {
                                                                echo "<option selected='selected' value='" . $row['category_id'] . "'>" . $ctg['category_name'] . "</option>";
                                                            } else {
                                                            ?>
                                                                <option value="<?php echo $ctg['category_id'] ?>">
                                                                    <?php echo $ctg['category_name']; ?></option><?php
                                                                                                                }
                                                                                                            }
                                                                                                        }
                                                                                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small mb-1" for="sprice">Selling Price</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">RM</span>
                                                    </div>
                                                    <input class="form-control" name="sprice" id="inputprice" type="number" placeholder="Selling Price " value="<?= $row['item_price'] ?>" min="0.01" step="0.01" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="small mb-1" for="cprice">Cost Price</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">RM</span>
                                                    </div>
                                                    <input class="form-control" name="cprice" id="inputprice" type="number" placeholder="Cost Price " value="<?= $row['item_cost'] ?>" min="0.01" step="0.01" required>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- item picture -->
                                        <div class="col">
                                            <!-- Profile picture card-->
                                            <div class="card ">
                                                <div class="card-header">Item Picture</div>
                                                <div class="card-body text-center">
                                                    <!-- Profile picture image-->
                                                    <img class="img-fluid mb-2" name="preview" id="preview" src="../image/<?php echo $row['item_image']; ?>" onclick="triggerClick()" alt="">
                                                    <!-- Profile picture help block-->
                                                    <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                                                    <!-- Profile picture upload button-->
                                                    <input class="files btn btn-primary" name="image" id="image" type="file" accept=".jpg, .jpeg, .png">
                                                    <?php $editimage = $row['item_image']; ?>
                                                </div>
                                            </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <!-- Save changes button-->
                                    <button class="btn btn-primary" name="editsavebtn" type="submit">Save changes</button>
                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php
                    unset($_SESSION['pid']);
                    ?>
                    <!-- Disable/Enable Button -->
                    <?php
                    if ($row['status'] == 1) {
                    ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="itemid" value="<?= $row['item_id'] ?>">
                            <button type="submit" name="disablebtn" class="btn btn-warning text-white" onclick="return confirm('Disable <?= $row['item_name'] ?>?')">Disable</button>
                        </form>
                    <?php
                    } else {
                    ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="itemid" value="<?= $row['item_id'] ?>">
                            <button type="submit" name="restorebtn" class="btn btn-success" onclick="return confirm('Restore <?= $row['item_name'] ?>?')">Restore</button>
                        </form>
                    <?php
                    }
                    ?>
                    <!-- Delete Button -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="itemid" value="<?= $row['item_id'] ?>">
                        <button type="submit" name="deletebtn" class="btn btn-danger" onclick="return confirm('Delete <?= $row['item_name'] ?>?')">Delete</button>
                    </form>
                </td>
            </tr>
    <?php
        }
    }
    ?>
</tbody>

            </table>
                </div>
                <!-- Add Item Modal -->
<div class="modal fade " id="addModal" tabindex="-1" aria-labelledby="addLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title " id="editLabel"><?php echo "Item Detail";?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <form method='POST' enctype="multipart/form-data" >

                    <!-- Form Row-->
                    <div class="row gx-3 mb-3">

                        <?php
                        $sqlpro = "SELECT item_id FROM item";
                        $pid = 0;
                        $sqlpro_run = mysqli_query($connect,"$sqlpro");
                        $row = mysqli_fetch_assoc($sqlpro_run);
                        if(mysqli_num_rows($sqlpro_run)>0)
                        {
                            foreach($sqlpro_run as $row)
                            {
                                $pid = $row['item_id'];
                                $pid = $pid + 1;
                            }
                        }
                        else
                        {
                            $pid = 1;
                        }
                        ?>
                        <div class="col-md-6">
                            <label class="small mb-1" for="inputitemID">Item ID</label>
                            <input class="form-control" name="itemid" id="inputitemID" type="text" placeholder="Item ID" value="<?= $pid ?>" readonly>
                        </div>
                        <?php
                        ?>
                        <div class="col-md-6">
                            <label class="small mb-1" for="inputitemname">Item Name</label>
                            <input class="form-control" name="itemname" id="inputitemname" type="text" placeholder="Enter Item Name" value="" required>
                        </div>
                    </div>

                    <!-- Form Row -->
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="category">Category</label>
                            <select class="form-select" name="category" required>
                                <?php
                                $sqlctg = "SELECT * FROM category WHERE status = 1";
                                $sqlctg_run = mysqli_query($connect,"$sqlctg");                                $icat = $row['category_id'];
                                if(mysqli_num_rows($sqlctg_run)>0)
                                {

                                    while($ctg = mysqli_fetch_assoc($sqlctg_run))
                                    {
                                        $cname = $ctg['category_name'];
                                        ?>
                                        <?php 
                                        if($icat == $cname)
                                        {
                                            ?>
                                            <option selected><?php echo $row['category_id'] ?></option>
                                            <?php
                                        }
                                        else{
                                            ?>
                                            <option value="<?php echo $ctg['category_id']?>">
                                                <?php echo $ctg['category_name'];?></option><?php
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                                    <!-- Form Group (location)-->
                                    
                                        <label class="small mb-1" for="description">Description</label>
                                        <textarea class="form-control" rows="3" name="description" id="itemdesc" placeholder="Description" ></textarea>
                                </div>
                        <div class="col-md-6">
                            <label class="small mb-1" for="opening_stock">Opening Stock</label>
                            <input class="form-control" name="opening_stock" id="opening_stock" type="number" placeholder="Enter Opening Stock" required>
                        </div>
                    </div>

                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small mb-1" for="sprice">Selling Price</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">RM</span>
                                </div>
                                <input class="form-control" name="sprice" id="inputprice" type="number" placeholder="Selling Price " value="" min="0.01" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1" for="cprice">Cost Price</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">RM</span>
                                </div>
                                <input class="form-control" name="cprice" id="inputprice" type="number" placeholder="Cost Price " value="" min="0.01" step="0.01" required>
                            </div>
                        </div>
                    </div>

                    <!-- Item picture -->
                    <div class="col">
                        <!-- Profile picture card-->
                        <div class="card ">
                            <div class="card-header">Item Picture</div>
                            <div class="card-body text-center">
                                <!-- Profile picture image-->
                                <img class="img-fluid mb-2" name="preview" id="preview"  onclick="triggerClick()" alt="">
                                <!-- Profile picture help block-->
                                <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                                <!-- Profile picture upload button-->
                                <input class="files btn btn-primary" name="aimage" id="aimage" type="file"   accept=".jpg, .jpeg, .png" required>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <!-- Save changes button-->
                        <button class="btn btn-primary" name="addbtn" type="submit">Add Item</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



            </div>
        </div>
    </div>
    
    <script>
  $(document).ready(function() {
  if (window.File && window.FileList && window.FileReader) {
    $(".files").on("change", function(e) {
    	var clickedButton = this;
      var files = e.target.files,
        filesLength = files.length;
      for (var i = 0; i < filesLength; i++) {
        var f = files[i]
        var fileReader = new FileReader();
        fileReader.onload = (function(e) {
          var file = e.target;
          $("<div class=\"pip container border border-dark mt-3 mb-3\">" +
            "<label class=\"container\">Image Preview : </label>" +
            "<img class=\"img-fluid mb-2\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
            "<br/><span class=\"remove btn btn-danger mt-3 mb-3\">Remove image</span>" + 
            
            "</div>").insertBefore(clickedButton);
          $(".remove").click(function(){
            $(this).parent(".pip").remove();
          });
          });
        fileReader.readAsDataURL(f);
      }
    });
  } else {
    alert("Your browser doesn't support to File API")
  }
});

document.getElementById('searchBox').addEventListener('keyup', function() {
    var searchValue = this.value.toLowerCase();
    var tableRows = document.querySelectorAll('#myTable tbody tr');

    tableRows.forEach(function(row) {
        var itemName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        if (itemName.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

    </script>
    <script src="theme-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
    <script src="theme-assets/js/core/app-menu-lite.js" type="text/javascript"></script>
    <script src="theme-assets/js/core/app-lite.js" type="text/javascript"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src = "search.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    

  </body>
</html>



