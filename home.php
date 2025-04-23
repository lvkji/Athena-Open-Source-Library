<?php
// include 'db.php';
require_once("database.php"); 

$mysqli = Database::dbConnect(); 
$mysqli -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

$sql = "SELECT Title, UploadDate FROM Books ORDER BY UploadDate DESC LIMIT 5";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" type="image/x-icon" href="AthensAcropolis.ico">
    <meta charset="UTF-8">
    <title>Athena Home</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Athena Library</h1>
    </header>

    <main>
        <h2>Recently Uploaded Books</h2>
        <ul>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li>" . htmlspecialchars($row["Title"]) . " - " . $row["UploadDate"] . "</li>";
                }
            } else {
                echo "<li>No books available.</li>";
            }
            ?>
        </ul>
    </main>
</body>
</html>