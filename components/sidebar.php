<?php
      $current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
        
    
    <div class="sidebar-top">
          <div class="logo">
                  <i class="ri-menu-line toggle"></i>
              <a href="../pages/dashboard.php"><p>PocketPlan</p></a>
          </div>
            <div class="middle">
                  <a href="../pages/dashboard.php" class="tabs-container <?php echo $current_page == 'dashboard.php' ? 'active': '' ?> ">
                        <i class="ri-layout-grid-fill "></i> 
                        <p>Dashboard</p>
                  </a>
                  
                  
                  <a href="../pages/income.php" class="tabs-container <?php echo $current_page == 'income.php' ? 'active': '' ?>"><i class="ri-money-rupee-circle-line"></i><p>Income</p></a>
                  
                  
                  <a href="../pages/expense.php"  class="tabs-container <?php echo $current_page == 'expense.php' ? 'active': '' ?>"><i class="ri-hand-coin-line"></i><p>Expense</p></a>
                  <a href="../pages/budget.php"  class="tabs-container <?php echo $current_page == 'budget.php' ? 'active': '' ?>"><i class="ri-coin-fill"></i><p>Budget</p></a>
                  <a href="../pages/history.php"  class="tabs-container <?php echo $current_page == 'history.php' ? 'active': '' ?>"><i class="ri-history-line"></i><p>History</p></a>
            </div>
           
      </div>
      <div class="sidebar-bottom">
            <a href="../auth/logout.php" class="tabs-container <?php echo $current_page == 'settings.php' ? 'active': '' ?>">

            <i class="ri-logout-box-line"></i></i> <p>Logout</p>
            </a>
            
      </div>
</div>
