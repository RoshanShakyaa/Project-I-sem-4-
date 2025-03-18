<?php
include "../connect.php";
include "../auth/verify.php";
include '../total.php';

$error_msg = "";


if (isset($_GET['id']) && isset($_GET['type'])) {
    $id = $_GET['id'];
    $type = $_GET['type'];
    
  
    $table = ($type == 'income') ? 'income' : 'expense';
    $id_column = ($type == 'income') ? 'income_id' : 'expense_id'; 
    

    $sql = "SELECT * FROM $table WHERE $id_column = ? AND user_id = ?"; 
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $transaction = $result->fetch_assoc();

    if (!$transaction) {
        die("Invalid transaction.");
    }
} else {
    die("Invalid request.");
}


$categories = [];
$category_type = ($type == 'income') ? 'income' : 'expense';
$sql = "SELECT category_id, category_name FROM category WHERE category_type = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $category_type);
$stmt->execute();
$result = $stmt->get_result();
$categories = $result->fetch_all(MYSQLI_ASSOC);

if (isset($_POST['update'])) {
    $category_id = $_POST['category_id'];
    $amount = $_POST['amount'];
    $date = $_POST['date'] ?? date('Y-m-d');
    $description = $_POST['description'];

    if ($amount <= 0) {
        $error_msg = "Amount must be greater than zero.";
    } else {
        $sql = "UPDATE $table SET category_id=?, amount=?, description=?, date=? WHERE $id_column=? AND user_id=?"; 
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissii", $category_id, $amount, $description, $date, $id, $_SESSION['user_id']);

        if ($stmt->execute()) {
            header("Location: history.php");
            exit();
        } else {
            $error_msg = "Error updating transaction.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaction</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet"/>
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
                        <form action="" method="post" class="card">
                            <h2>Edit Transaction</h2>

                            <select name="category_id" required>
                                <option disabled>Select category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category['category_id']); ?>"
                                        <?= ($category['category_id'] == $transaction['category_id']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($category['category_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <input type="number" name="amount" value="<?= htmlspecialchars($transaction['amount']); ?>" required min="1" placeholder="Amount">

                            <label for="date">Date:</label>
                            <input type="date" name="date" id="date" value="<?= htmlspecialchars($transaction['date']); ?>" required>

                            <input type="text" name="description" value="<?= htmlspecialchars($transaction['description']); ?>" placeholder="Description">

                            <?php if (!empty($error_msg)): ?>
                                <p style="color:red;"><?= $error_msg; ?></p>
                            <?php endif; ?>

                            <input type="submit" name="update" value="Update" class="submit-btn">
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>
    <script src="../script.js"></script>
</body>
</html>