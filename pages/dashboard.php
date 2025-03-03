<?php 
    
   include "../connect.php";
   include "../auth/verify.php";
   include "../total.php";
   include '../budgetqry.php';
  
   $stmt = $conn->prepare($budget_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $budgets = [];
    while ($row = $result->fetch_assoc()) {
    $budgets[] = $row;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css"
    rel="stylesheet"
/>
<link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="wrapper">
        <?php include '../components/sidebar.php' ?>
        <main class="page">
            <?php include '../components/navbar.php' ?>

            <section class="dashboard">
                <div class="assets-info">
                    <div class="card balance">
                        <h2>Total Balance</h2>
                        <div class="amount">Rs.<?php echo $total_balance; ?></div>
                    </div>
                    <div class="card income">
                        <h2>Total Income</h2>
                        <div class="amount">Rs.<?php echo round($total_income); ?></div>
                    </div>
                    <div class="card expense">
                        <h2>Total Expense</h2>
                        <div class="amount">Rs.<?php echo round($total_expense); ?></div>
                    </div>
                </div>
                <div class="dashboard-bottom">

                    <div class="table card">
                        <div class="title"><p>Recent Transactions</p> <a href="../pages/history.php">View all</a></div>
                        <div class="row head">
                                <h3>Date</h3>

                                
                                <h3>Amount</h3>

                                <h3>Category</h3>
                        </div>
                        <?php foreach ($recent_transactions as $transaction): ?>
                            <div class="row <?php echo $transaction['type'] == 'income' ? 'income' : 'expense'; ?>">
                                <h3>
                                    <?php echo htmlspecialchars($transaction['formatted_date']); ?>:
                                </h3>
                                <h3>
                                    <?php echo htmlspecialchars($transaction['amount']); ?> 
                                </h3>
                                <h3>
                                    <?php echo htmlspecialchars($transaction['category_name']); ?>
                                </h3>
                            </div>

                        <?php endforeach; ?>
                               
                    </div>
                    
                    
                    <div class=" budget-cards">
                        <h1>Budget</h1>
                        <?php foreach ($budgets as $budget): ?>
                            <div class="card budget-card">
                                <h1><?php echo htmlspecialchars($budget['category_name']); ?></h1>
                                <div class="flex-between"><p>Rs.<?php echo ($budget['budget_amount']); ?></p><p><?php echo htmlspecialchars($budget['end_date']); ?></p></div>
                                <progress id="progressBar" value="<?php echo htmlspecialchars($budget['total_expenses']); ?>" max="<?php echo htmlspecialchars($budget['budget_amount']); ?>"></progress>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
            </section>

        </main>
    </div>
    <script src="../script.js"></script>
</body>
</html>