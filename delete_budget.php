<?php
session_start();
include "./connect.php";
include "./auth/verify.php";

if (isset($_POST['budget_id'])) {
    $budget_id = $_POST['budget_id'];
    $user_id = $_SESSION['user_id'];

    $sql = "DELETE FROM budget WHERE budget_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $budget_id, $user_id);

    if ($stmt->execute()) {
        header("Location: ./pages/dashboard.php");
        exit();
    } else {
        echo "Error deleting budget: " . $stmt->error;
    }

    $stmt->close();
} else {
    die("Invalid request.");
}

$conn->close();
?>