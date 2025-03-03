<?php
include "./connect.php";
include "./auth/verify.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type']; 
    $amount = $_POST['amount'];
    $user_id = $_POST['user_id'];
    $description = $_POST['description'];

    if ($type === "income") {
        $sql = "DELETE FROM income WHERE user_id = ? AND amount = ? AND description = ? LIMIT 1";
    } else {
        $sql = "DELETE FROM expense WHERE user_id = ? AND amount = ? AND description = ? LIMIT 1";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ids", $user_id, $amount, $description);

    if ($stmt->execute()) {
        header("Location: ./pages/dashboard.php"); // Redirect to dashboard after deletion
        exit();
    } else {
        echo "Error deleting transaction: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
