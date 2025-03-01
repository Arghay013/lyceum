<?php
session_start();
require __DIR__.'/../config/database.php';

// Check if the user is logged in and is a teacher
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $community_name = trim($_POST['community_name']);
    $university_id = intval($_POST['university_id']);
    $institute_id = intval($_POST['institute_id']);
    $course_id = intval($_POST['course_id']);
    $batch_id = intval($_POST['batch_id']);
    $description = trim($_POST['description']);
    $user_id = $_SESSION['user_id'];

    // Validate form inputs
    if (empty($community_name) || empty($description)) {
        echo "<script>console.log('All fields are required.');</script>";
        header("Location: create_community.php");
        exit();
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO community (community_name, university_id, institute_id, course_id, batch_id, description, created_by, created_at) 
            VALUES (:community_name, :university_id, :institute_id, :course_id, :batch_id, :description, :created_by, NOW())";
    $stmt = $pdo->prepare($sql);

    // Bind the parameters
    $stmt->bindParam(':community_name', $community_name);
    $stmt->bindParam(':university_id', $university_id);
    $stmt->bindParam(':institute_id', $institute_id);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->bindParam(':batch_id', $batch_id);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':created_by', $user_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>console.log('Community created successfully!');</script>";
        header("Location: view_communities.php");
        exit();
    } else {
        echo "<script>console.log('Error: " . $stmt->errorInfo()[2] . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Community</title>
</head>
<body>
    <h2>Create Community</h2>
    <form action="create_community.php" method="POST">
        <label for="community_name">Community Name:</label>
        <input type="text" id="community_name" name="community_name" required><br><br>

        <label for="university_id">University ID:</label>
        <input type="number" id="university_id" name="university_id" required><br><br>

        <label for="institute_id">Institute ID:</label>
        <input type="number" id="institute_id" name="institute_id" required><br><br>

        <label for="course_id">Course ID:</label>
        <input type="number" id="course_id" name="course_id" required><br><br>

        <label for="batch_id">Batch ID:</label>
        <input type="number" id="batch_id" name="batch_id" required><br><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br><br>

        <button type="submit">Create Community</button>
    </form>
    <a href="view_communities.php"><button>Back to Communities</button></a>
</body>
</html>