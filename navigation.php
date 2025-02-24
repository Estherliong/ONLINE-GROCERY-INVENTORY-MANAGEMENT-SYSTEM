<!---navbar-->
<?php
  if(isset($_SESSION['id']))
  {
    $id = $_SESSION['id'];

      ?>
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow " data-scroll-to-active="true" data-img="theme-assets/images/backgrounds/04.jpg">
      <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">       
          <li class="nav-item mr-auto"><a class="navbar-brand" href="index.php"><img class="brand-logo" src="/FYP/image/inventory.png" alt="Inventory Logo"/>
              <h3 class="brand-text">Management</h3></a></li>
          <li class="nav-item d-md-none"><a class="nav-link close-navbar"><i class="ft-x"></i></a></li>
        </ul>
      </div>
      <?php
        if($_SESSION['identity'] == 'admin')
        {
      ?>
      <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
          <li class=" nav-item"><a href="index.php"><i class="material-symbols-rounded">account_circle</i><span class="menu-title" data-i18n="">Dashboard</span></a>
          </li>
          <!-- <li class=" nav-item"><a href="#"><i class="material-symbols-rounded">notifications_active</i><span class="menu-title" data-i18n="">Notifications</span></a>
          </li> -->
          <li class=" nav-item has-sub"><a href="#"><i class="material-symbols-rounded">Inventory</i><span class="menu-title" data-i18n="">Inventory</span></a>
            <ul class="menu-content" >
              <li class="is-shown" >
                <a class="menu-item" href="view_item.php"  >Item</a>
              </li>
              <li class="is-shown">
                <a class="menu-item" href="view_category.php">Category</a>
              </li>   
              <li class="is-shown">
                <a class="menu-item" href="inventory.php">Inventory Adjustment</a>
              </li>   
              <li class="is-shown">
                <a class="menu-item" href="price.php">Price Adjustment</a>
              </li>  
              <li class="is-shown">
                <a class="menu-item" href="cost.php">Cost Adjustment</a>
              </li>    
            </ul> 
          </li>
          <li class=" nav-item has-sub"><a href="#"><i class="material-symbols-rounded">receipt_long</i><span class="menu-title" data-i18n="">Sales</span></a>
            <ul class="menu-content" >
              <li class="is-shown" >
                <a class="menu-item" href="view_customer.php"  >Customer</a>
              </li>
              <li class="is-shown" >
                <a class="menu-item" href="order.php"  >Sales Order</a>
              </li>
              <li class="is-shown" >
                <a class="menu-item" href="package.php"  >Package</a>
              </li>
              <li class="is-shown" >
                <a class="menu-item" href="invoice.php">Invoices</a>
              </li>
            </ul>
          </li>  
          <li class=" nav-item has-sub"><a href="#"><i class="material-symbols-rounded">shopping_bag</i><span class="menu-title" data-i18n="">Purchases</span></a>
            <ul class="menu-content" >
              <li class="is-shown" >
                <a class="menu-item" href="view_supplier.php"  >Supplier</a>
              </li>
              <li class="is-shown" >
                <a class="menu-item" href="expenses.php"  >Expenses</a>
              </li>
            </ul>
          </li>
          <li class=" nav-item "><a href="view_admin.php"><i class="material-symbols-rounded">manage_accounts</i><span class="menu-title" data-i18n="">Admins</span></a>
            
          </li>
          <!--<li class=" nav-item"><a href="reports.php"><i class="material-symbols-rounded">monitoring</i><span class="menu-title" data-i18n="">Reports</span></a>-->
          <li class=" nav-item"><a href="logout.php"><i class="material-symbols-rounded">logout</i><span class="menu-title" data-i18n="">Logout</span></a>
          </li>
        </ul>
      </div>
      <div class="navigation-background"></div>
</div>
    <?php
      }
      else
      {
        ?>
      <div class="main-menu-content">
      <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
      <li class=" nav-item has-sub"><a href="#"><i class="material-symbols-rounded">Inventory</i><span class="menu-title" data-i18n="">Inventory</span></a>
            <ul class="menu-content" >
              <li class="is-shown">
                <a class="menu-item" href="inventory.php">Inventory Adjustment</a>
              </li>   
              <li class="is-shown">
                <a class="menu-item" href="price.php">Price Adjustment</a>
              </li>   
            </ul> 
      </li>
      </li>
          <li class=" nav-item"><a href="logout.php"><i class="material-symbols-rounded">logout</i><span class="menu-title" data-i18n="">Logout</span></a>
        </li>
      </ul>
      </div>
      <div class="navigation-background"></div>
</div>      
        <?php
      }
  }
      
    ?>
<style>

.navbar-brand {
    display: flex;
    align-items: center;
}

.brand-logo {

    margin-right: 10px;
}

.brand-text{
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 16px; 
    color: #000; 
    
}
.menu-content {
    display: flex;
    flex-wrap: wrap;
    overflow: visible;
}

.menu-item {
    flex: 1 1 auto;
    white-space: nowrap;
}
</style>
