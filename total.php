<?php
include "connect.php"; 

$user_id = $_SESSION['user_id'];

// calculatin total income
$income_query = "SELECT SUM(amount) AS total_income FROM income WHERE user_id = ?";
$stmt = $conn->prepare($income_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$income_row = $result->fetch_assoc();
$total_income = $income_row['total_income'] ? $income_row['total_income'] : 0;

// calculatin total expense
$expense_query = "SELECT SUM(amount) AS total_expense FROM expense WHERE user_id = ?";
$stmt = $conn->prepare($expense_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$expense_row = $result->fetch_assoc();
$total_expense = $expense_row['total_expense'] ? $expense_row['total_expense'] : 0;

// Calculating total balance
$total_balance = $total_income - $total_expense;

$limit = 6; 
$recent_transactions_query = "
    (SELECT 'income' AS type, i.description, i.amount, DATE_FORMAT(i.date, '%Y-%b %d') AS formatted_date, c.category_name 
     FROM income i 
     JOIN category c ON i.category_id = c.category_id 
     WHERE i.user_id = ? 
     ORDER BY i.date DESC 
     LIMIT ?)
    UNION ALL
    (SELECT 'expense' AS type, e.description, e.amount, DATE_FORMAT(e.date, '%Y-%b %d') AS formatted_date, c.category_name 
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
// Clean up
$stmt->close();
?>
