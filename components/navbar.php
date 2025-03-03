<?php
    $current_page = basename($_SERVER['PHP_SELF']);
?>  
<div class="navbar">
    <div class="navbar-left">
        <h1> <?php
            if ($current_page == 'dashboard.php') {
                echo 'Dashboard';
            } elseif ($current_page == 'income.php') {
                echo 'Income';
            } elseif ($current_page == 'expense.php') {
                echo 'Expense';
            }
             elseif ($current_page == 'history.php') {
                echo 'History';
            }
             elseif ($current_page == 'budget.php') {
                echo 'Budget';
            }
            ?></h1>
    </div>
    <div class="navbar-right">
        <div class="user">
            <img src="../assets/default_avatar.jpg" alt="">
            <?php echo $_SESSION['username']; ?>
        </div>
    </div>
</div>
