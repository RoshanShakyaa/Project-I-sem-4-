<?php 
    
   include "../connect.php";
   include "../auth/verify.php";
   include '../total.php';

   $expense_categories = [];
   $sql = "SELECT category_id, category_name FROM category WHERE category_type = 'expense'";
   $result = mysqli_query($conn, $sql);
   $expense_categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

   if(isset($_POST['submit'])){
    $category_id = $_POST['category_id'];
    $amount = $_POST['amount'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $description = $_POST['description'];

    session_start();
    $user_id = $_SESSION['user_id']; 

    $sql = 'INSERT INTO budget (user_id, category_id,description, amount, start_date, end_date) 
            VALUES (?, ?, ?, ?, ?, ?)';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iisdss', $user_id, $category_id, $description, $amount, $start_date,$end_date);

    if($stmt->execute()){
        header("Location: ../pages/dashboard.php"); 
        exit();
    }else {
        echo "Error: " . $stmt->error;
    }$stmt->close();

   }

   $budget_query = "
   SELECT b.budget_id, b.amount AS budget_amount, b.start_date, b.end_date, c.category_name, 
          IFNULL(SUM(e.amount), 0) AS total_expenses
   FROM budget b
   JOIN category c ON b.category_id = c.category_id
   LEFT JOIN expense e ON b.category_id = e.category_id 
       AND e.user_id = b.user_id 
       AND e.date BETWEEN b.start_date AND b.end_date
   WHERE b.user_id = ?
   GROUP BY b.budget_id, c.category_name, b.start_date, b.end_date
";
$stmt = $conn->prepare($budget_query);
$stmt->bind_param("i",$user_id);
$stmt->execute();
$result = $stmt->get_result();

$budgets = [];
while($row = $result->fetch_assoc()){
    $budgets[] = $row;
}
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

            <section class="budget-page">
                <div class="budget-top">
                <div class="left">
                        <form action="budget.php" class="card" method="post">
                        <select class="form-select"   name="category_id" required>
                            <option selected disabled>Select category</option>
                            <?php foreach ($expense_categories as $category): ?>
                                <option  value="<?php echo htmlspecialchars($category['category_id']); ?>">
                                    <?php echo htmlspecialchars($category['category_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                            <input type="number" placeholder="Enter limit amount" name="amount">
                            <label for="start_date">Start Date:</label>
                            <input type="date" name="start_date" id="start_date" required>

                            <label for="end_date">End Date:</label>
                            <input type="date" name="end_date" id="end_date" required>
                            <input type="text" placeholder="Details" name="description">
                            <input type="submit" value="Submit" class="submit-btn" name="submit">
                        </form>
                    </div>
                </div>
                
            </section>

        </main>
    </div>
    <script src="../script.js"></script>
</body>
</html>