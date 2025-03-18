<?php 
    
   include "../connect.php";
   include "../auth/verify.php";
   include '../total.php';

   $expense_categories = [];
   $sql = "SELECT category_id, category_name FROM category WHERE category_type = 'expense'";
   $result = mysqli_query($conn, $sql);
   $expense_categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
   $error_msg= '';

   if(isset($_POST['submit'])){
    $category_id = $_POST['category_id'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    

    $user_id = $_SESSION['user_id']; 
    if ($amount <= 0) {
        $error_msg = "Amount must be greater than zero.";
    } elseif ($amount > $total_balance) {
        $error_msg = "Expense can't exceed total balance.";
    } else {
        $sql = 'INSERT INTO expense (user_id, category_id, description, amount, date) VALUES (?, ?, ?, ?, ?)';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iisss', $user_id, $category_id, $description, $amount, $date);
        
        if ($stmt->execute()) {
            header("Location: ../pages/dashboard.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    }
    

   

   
   $recent_expense_query = "
    SELECT amount,date, category_name
    FROM expense
    JOIN category ON expense.category_id = category.category_id
    WHERE expense.user_id = ?
    ORDER BY date DESC
    LIMIT 8";
    $stmt = $conn->prepare($recent_expense_query);
    $stmt->bind_param('i', $user_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    $recent_expenses = $result->fetch_all(MYSQLI_ASSOC);
   $conn->close();
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

            <section class="expense-page">
                <div class="expense-top">
                    <div class="left">
                        <form action="expense.php" class="card" method="post">
                        <select class="form-select" aria-label="Default select example"  name="category_id" required>
                            <option selected disabled>Select category</option>
                            <?php foreach ($expense_categories as $category): ?>
                                <option  value="<?php echo htmlspecialchars($category['category_id']); ?>">
                                    <?php echo htmlspecialchars($category['category_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                            <input type="number" placeholder="Amount" name="amount" min="1" required>
                            
                            <input type="date" name="date" required>
                            <input type="text" placeholder="detail" name="description">
                            <?php if (!empty($error_msg)): ?>
                                <p class="error-msg"><?php echo $error_msg; ?></p>
                            <?php endif; ?>
                            <input type="submit" value="Submit" class="submit-btn" name="submit">
                        </form>
                    </div>
                    <div class="right">

                        <div class="card expense">
                            <h2>Total Expense</h2>
                            <div class="amount">Rs.<?php echo round($total_expense) ?></div>
                        </div>
                        <div class="table table-expense card">
                        <div class="title"><p>Recent Transactions</p> <a href="../pages/history.php">View all</a></div>
                    <div class="row head">
                        <h3>Date</h3>
                        <h3>Amount</h3>
                        <h3>Category</h3>
                    </div>
                    <?php foreach ($recent_expenses as $expense): ?>
                            <div class="row ">
                                <h3>
                                    <?php echo date('Y-M-d', strtotime($expense['date'])); ?>:
                                </h3>
                                <h3>
                                    <?php echo  round($expense['amount']); ?> 
                                </h3>
                                <h3>
                                    <?php echo htmlspecialchars($expense['category_name']); ?>
                                </h3>
                            </div>

                    <?php endforeach; ?>
                </div>
            </section>

        </main>
    </div>
    <script src="../script.js"></script>
</body>
</html>