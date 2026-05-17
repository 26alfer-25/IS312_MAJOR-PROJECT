<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Add New Project</h1>

        <div class="card">
            <form action="../project-service/create_project.php" method="POST">
                <label>Student Number</label>
                <input type="text" name="student_number" required>

                <label>Project Title</label>
                <input type="text" name="title" required>

                <label>Project Description</label>
                <textarea name="description"></textarea>

                <label>Technologies Used</label>
                <input type="text" name="technologies">

                <label>GitHub Link</label>
                <input type="url" name="github_link">

                <button type="submit">Add Project</button>
            </form>
        </div>

        <div class="back-link">
            <a href="index.php">Back to Home</a>
        </div>
    </div>
</body>
</html>
