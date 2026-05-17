<?php
include '../config/db.php';

if (!isset($_GET['id'])) {
    die("Project ID is required.");
}

$id = intval($_GET['id']);

$sql = "SELECT * FROM projects WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$project = $result->fetch_assoc();

$stmt->close();

if (!$project) {
    die("Project not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Project</h1>

        <div class="card">
            <form action="../project-service/update_project.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                <input type="hidden" name="student_number" value="<?php echo htmlspecialchars($project['student_number']); ?>">

                <label>Project Title</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($project['title']); ?>" required>

                <label>Description</label>
                <textarea name="description"><?php echo htmlspecialchars($project['description']); ?></textarea>

                <label>Technologies</label>
                <input type="text" name="technologies" value="<?php echo htmlspecialchars($project['technologies']); ?>">

                <label>GitHub Link</label>
                <input type="url" name="github_link" value="<?php echo htmlspecialchars($project['github_link']); ?>">

                <button type="submit">Update Project</button>
            </form>
        </div>

        <div class="back-link">
            <a href="project_list.php?student_number=<?php echo urlencode($project['student_number']); ?>">Back to Project List</a>
        </div>
    </div>
</body>
</html>
