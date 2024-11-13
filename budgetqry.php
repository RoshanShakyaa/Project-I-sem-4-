<?php 

include 'connect.php';
$user_id = $_SESSION['user_id'];

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



?>