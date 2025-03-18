<?php 
    include "../connect.php";
    include "../auth/verify.php";

    $user_id = $_SESSION['user_id'];
    $limit = 25;

    $recent_transactions_query = "
    (SELECT i.income_id AS id, 'income' AS type, i.description, i.amount, DATE_FORMAT(i.date, '%Y-%b %d') AS formatted_date, c.category_name 
     FROM income i 
     JOIN category c ON i.category_id = c.category_id 
     WHERE i.user_id = ? 
     ORDER BY i.date DESC 
     LIMIT ?)
    UNION ALL
    (SELECT e.expense_id AS id, 'expense' AS type, e.description, e.amount, DATE_FORMAT(e.date, '%Y-%b %d') AS formatted_date, c.category_name 
     FROM expense e 
     JOIN category c ON e.category_id = c.category_id 
     WHERE e.user_id = ? 
     ORDER BY e.date DESC 
     LIMIT ?)
    ORDER BY formatted_date DESC
    LIMIT ?"; 

    $stmt = $conn->prepare($recent_transactions_query);
    $stmt->bind_param("iiiii", $user_id, $limit, $user_id, $limit, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $recent_transactions = [];
    while ($row = $result->fetch_assoc()) {
        $recent_transactions[] = $row;
    }
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="wrapper">
        <?php include '../components/sidebar.php' ?>
        <main class="page">
            <?php include '../components/navbar.php' ?>

            <section class="dashboard">
                <div class="table card">
                    <div class="title"><p>Recent Transactions</p></div>
                    <div class="row head">
                        <h3>Date</h3>
                        <h3>Amount</h3>
                        <h3>Category</h3>
                        <h3>Description</h3> 
                        <h3>Actions</h3>
                    </div>
                    <?php foreach ($recent_transactions as $transaction): ?>
                        <div class="row <?php echo $transaction['type'] == 'income' ? 'income' : 'expense'; ?>">
                            <h3>
                                <?php echo htmlspecialchars($transaction['formatted_date']); ?>
                            </h3>
                            <h3>
                                <?php echo number_format($transaction['amount']); ?> 
                            </h3>
                            <h3>
                                <?php echo htmlspecialchars($transaction['category_name']); ?>
                            </h3>
                            <h3>
                                <?php echo htmlspecialchars($transaction['description']); ?>
                            </h3>
                            <div class="actions">
                                <div>
                                    <a href="edit_transaction.php?id=<?= urlencode($transaction['id']); ?>&type=<?= urlencode($transaction['type']); ?>" class="edit-btn">
                                    <i class="ri-pencil-line"></i>
                                    </a>
                                </div>
                                <div>
                                    <form action="../delete_transaction.php" method="POST">
                                        <input type="hidden" name="type" value="<?php echo $transaction['type']; ?>">
                                        <input type="hidden" name="amount" value="<?php echo $transaction['amount']; ?>">
                                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                                        <input type="hidden" name="description" value="<?php echo $transaction['description']; ?>">
                                        <button type="submit" class="btn" onclick="return confirm('Are you sure you want to delete this transaction?');">
                                        <i class="ri-close-line cross"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </main>
    </div>
    <script src="../script.js"></script>
</body>
</html>