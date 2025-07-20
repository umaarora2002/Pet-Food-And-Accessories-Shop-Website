<?php
// Database connection
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "pet_shop_db"; 

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    // Insert data into the database
    $sql = "INSERT INTO contact (name, email, subject, message) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $subject, $message);
    
    if ($stmt->execute()) {
        echo "<script>
                alert('Message Sent Successfully!');
                window.location.href = 'index.php'; // Redirect to home page
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Error sending message.');
                window.location.href = 'contact.php'; // Stay on the contact page if there's an error
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Pet Shop</title>
    <link rel="stylesheet" href="styles.css"> <!-- External CSS -->
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2 { text-align: center; }
        form { width: 50%; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        label { font-weight: bold; display: block; margin-top: 10px; }
        input, textarea { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; }
        button { background: blue; color: white; padding: 10px; margin-top: 10px; border: none; width: 100%; cursor: pointer; }
        button:hover { background: darkblue; }
    </style>
</head>
<body>

    <h2>Contact Us</h2>
    <form action="contact.php" method="POST">
        <label>Name:</label>
        <input type="text" name="name" required>
        
        <label>Email:</label>
        <input type="email" name="email" required>
        
        <label>Subject:</label>
        <input type="text" name="subject" required>
        
        <label>Message:</label>
        <textarea name="message" required></textarea>
        
        <button type="submit">Send</button>
    </form>

</body>
</html>

<?php $conn->close(); // Close database connection ?>
