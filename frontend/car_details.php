<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Update Developer Profile</h1>

        <div class="card">
            <form action="../profile-service/update_profile.php" method="POST">
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

                <button type="submit">Update Profile</button>
            </form>
        </div>

        <div class="back-link">
            <a href="index.php">Back to Home</a>
        </div>
    </div>
</body>
</html>
