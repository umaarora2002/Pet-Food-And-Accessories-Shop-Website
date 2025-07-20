<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $user_name = $_POST["user_name"];
    $rating = $_POST["rating"];
    $review = $_POST["review"];

    $sql = "INSERT INTO ratings (product_id, user_name, rating, review) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isis", $product_id, $user_name, $rating, $review);
    
    if ($stmt->execute()) {
        echo "<script>
                alert('Thank you for your feedback!');
                window.location.href = 'index.php'; // Redirect to the home page
              </script>";
        exit();
    } else {
        echo "<script>alert('Error submitting rating. Please try again.');</script>";
    }
}

// Fetch existing reviews
$product_id = $_GET['product_id'] ?? 1; // Default product ID
$sql = "SELECT user_name, rating, review, created_at FROM ratings WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Ratings - Pet Shop</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Rate Our Product</h2>
    <form action="rating.php" method="POST">
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
        
        <label>Your Name:</label>
        <input type="text" name="user_name" required>
        
        <label>Rating (1-5):</label>
        <select name="rating" required>
            <option value="1">1 - Poor</option>
            <option value="2">2 - Fair</option>
            <option value="3">3 - Good</option>
            <option value="4">4 - Very Good</option>
            <option value="5">5 - Excellent</option>
        </select>
        
        <label>Review:</label>
        <textarea name="review" required></textarea>
        
        <button type="submit">Submit</button>
    </form>

    <h2>Customer Reviews</h2>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="review">
            <p><strong><?php echo htmlspecialchars($row["user_name"]); ?></strong> (Rated: <?php echo $row["rating"]; ?>/5)</p>
            <p><?php echo htmlspecialchars($row["review"]); ?></p>
            <small><?php echo $row["created_at"]; ?></small>
        </div>
    <?php endwhile; ?>
</body>
</html>
