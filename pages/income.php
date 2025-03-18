<?php 
    
   include "../connect.php";
   include "../auth/verify.php";
   include "../total.php";

   $income_categories = [];
   $sql = "SELECT category_id, category_name FROM category WHERE category_type = 'income'";
    $result = mysqli_query($conn, $sql);
    $income_categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

   if(isset($_POST['submit'])){
    $category_id = $_POST['category_id'];
    $amount = $_POST['amount'];
    $date = $_POST['date'] ?? date('Y-m-d');
    $description = $_POST['description'];

    session_start();
    $user_id = $_SESSION['user_id']; 

    $sql = 'INSERT INTO income (user_id, category_id, description, amount, date) VALUES (?, ?, ?, ?, ?)';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iisss', $user_id, $category_id, $description, $amount, $date);

    if($stmt->execute()){
        header("Location: ../pages/dashboard.php");
        exit();
    }else {
        echo "Error: " . $stmt->error;
    }$stmt->close();

   }

   $recent_income_query = "
    SELECT amount,date, category_name
    FROM income
    JOIN category ON income.category_id = category.category_id
    WHERE income.user_id = ?
    ORDER BY date DESC
    LIMIT 8";
    $stmt = $conn->prepare($recent_income_query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $recent_incomes = $result->fetch_all(MYSQLI_ASSOC);


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

            <section class="income-page">
                <div class="income-top">
                    <div class="left">
                        <form action="income.php" class="card" method="post">
                            <select class="form-select" aria-label="Default select example"  name="category_id" required>
                            <option selected disabled>Select category</option>
                            <?php foreach ($income_categories as $category): ?>
                                <option  value="<?php echo htmlspecialchars($category['category_id']); ?>">
                                    <?php echo htmlspecialchars($category['category_name']); ?>
                                </option>
                            <?php endforeach; ?>
                            </select>

                            <input type="number" placeholder="Amount" name="amount">
                            <input type="date" name="date" id="">
                            <input type="text" placeholder="detail" name="description">
                            <input type="submit" value="Submit" class="submit-btn" name="submit">
                        </form>
                    </div>
                    <div class="right">

                        <div class="card income">
                            <h2>Total Income</h2>
                            <div class="amount">Rs. <?php echo number_format($total_income) ?></div>
                        </div>
                        <div class="table table-expense card">
                            <div class="row head">
                                <h3>Date</h3>
                                <h3>Amount</h3>
                                <h3>Category</h3>
                            </div>
                                <?php foreach ($recent_incomes as $income): ?>
                                <div class="row ">
                                    <h3>
                                        <?php echo date('Y-M-d', strtotime($income['date'])); ?>
                                    </h3>
                                    <h3>
                                        <?php echo  number_format($income['amount']); ?> 
                                    </h3>
                                    <h3>
                                        <?php echo htmlspecialchars($income['category_name']); ?>
                                    </h3>
                                </div>
                                <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </section>

        </main>
    </div>
    <script src="../script.js"></script>
</body>
</html>