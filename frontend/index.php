<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Developer Portfolio</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Student Developer Portfolio App</h1>

        <?php if (isset($_GET['message'])): ?>
            <p class="success"><?php echo htmlspecialchars($_GET['message']); ?></p>
        <?php endif; ?>

        <div class="card">
            <h2>Create Profile</h2>
            <form action="../profile-service/create_profile.php" method="POST">
                <label>Student Number</label>
                <input type="text" name="student_number" required>

                <label>Full Name</label>
                <input type="text" name="full_name" required>

                <label>Email</label>
                <input type="email" name="email">

                <label>Bio</label>
                <textarea name="bio"></textarea>

                <label>Skills</label>
                <input type="text" name="skills">

                <label>GitHub Username</label>
                <input type="text" name="github_username">

                <button type="submit">Save Profile</button>
            </form>
        </div>

        <div class="card">
            <h2>Navigation</h2>
            <div class="nav-links">
                <a href="profile_form.php">Update Profile</a>
                <a href="project_form.php">Add Project</a>
                <a href="project_list.php">View Projects</a>
            </div>
        </div>
    </div>
</body>
</html>
