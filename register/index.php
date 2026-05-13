<?php
include('../utils/db_config.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Collect Data from Form
    $role = $_POST['role']; // 'student' or 'faculty'
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // 2. Check PawCrewCode for Admin Privileges (Logic from before)
    $account_type = 'user';
    if (!empty($_POST['crew_code'])) {
        $codeCheck = supabase_query("admin_codes?admin_code=eq." . $_POST['crew_code']);
        if (!empty($codeCheck)) { $account_type = 'admin'; }
    }

    // 3. Prepare User Data
    $userData = [
        "first_name" => $_POST['first_name'],
        "last_name"  => $_POST['last_name'],
        "username"   => $username,
        "email"      => $_POST['email'],
        "password_hash" => $password,
        "contact_number" => $_POST['contact'],
        "affiliation" => $_POST['affiliations'],
        "role" => $role,
        "account_type" => $account_type
    ];

    // 4. Insert into 'users' table
    $userResponse = supabase_query("users", "POST", $userData);

    // If Supabase returns the new user object (it should have 'user_id' because of return=representation)
    if (isset($userResponse[0]['user_id'])) {
        $new_uuid = $userResponse[0]['user_id'];

        // 5. Insert into the specific profile table based on role
        if ($role === 'student') {
            $profileData = [
                "user_id" => $new_uuid,
                "student_number" => $_POST['id_number'],
                "program" => $_POST['program'],
                "year_level" => $_POST['year_level']
            ];
            supabase_query("student_profiles", "POST", $profileData);
        } else {
            $profileData = [
                "user_id" => $new_uuid,
                "office" => $_POST['office'],
                "institutional_email" => $_POST['email']
            ];
            supabase_query("faculty_profiles", "POST", $profileData);
        }

        // --- THE REDIRECT ---
        // Go up one level to the project root, then into the login folder
        header("Location: ../login/index.php?signup=success");
        exit(); // Always exit after a header redirect
        
    } else {
        $error = "Registration failed. Username or Email might already exist.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusTails | Register</title>
    <link rel="stylesheet" href="register.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="reg-master">
        <!-- NAVBAR -->
        <header class="reg-header">
            <div class="nav-container">
                <div class="logo-side"><img src="../resources/Logo.png" alt="CampusTails"></div>
                <nav class="nav-links">
                    <a href="#">home</a><a href="#">about</a><a href="#">pets</a><a href="../login/login.php">login</a>
                </nav>
            </div>
        </header>

        <!-- FORM CONTENT -->
        <main class="reg-container">
            <div class="reg-title">
                <img src="../resources/Logo.png" alt="Logo" class="mini-logo">
                <h3>Welcome PawCrew!</h3>
                <p>Please fill out all necessary information below.</p>
            </div>

            <form action="" method="POST" class="reg-form">
                <div class="form-grid">
                    <div class="input-group">
                        <label>First Name <span class="req">*</span></label>
                        <input type="text" name="first_name" required>
                    </div>
                    <div class="input-group">
                        <label>Last Name <span class="req">*</span></label>
                        <input type="text" name="last_name" required>
                    </div>
                    <div class="input-group">
                        <label>M.I. <span class="req">*</span></label>
                        <input type="text" name="mi" maxlength="2" required>
                    </div>

                    <div class="input-group span-2">
                        <label>ID Number <span class="req">*</span></label>
                        <input type="text" name="id_number" required>
                    </div>
                    <div class="input-group span-2">
                        <label>Institutional Email <span class="req">*</span></label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="input-group">
                        <label>Birthday <span class="req">*</span></label>
                        <input type="date" name="birthday" required>
                    </div>
                    <div class="input-group">
                        <label>Role <span class="req">*</span></label>
                        <select name="role" id="roleSelect" required>
                            <option value="" disabled selected>Student/Faculty</option>
                            <option value="student">Student</option>
                            <option value="faculty">Faculty</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label>PawCrewCode</label>
                        <input type="password" name="crew_code">
                    </div>

                    <!-- Hidden fields toggled by JS -->
                    <div class="input-group" id="programBox">
                        <label>Program <span class="req">*</span> <small>"appear only for student"</small></label>
                        <input type="text" name="program">
                    </div>
                    <div class="input-group" id="yearBox">
                        <label>Year Level <span class="req">*</span> <small>"appear only for student"</small></label>
                        <input type="text" name="year_level">
                    </div>
                    <div class="input-group span-full" id="officeBox">
                        <label>Office <span class="req">*</span> <small>"appear only for faculty"</small></label>
                        <input type="text" name="office">
                    </div>

                    <div class="input-group">
                        <label>Contact Number <span class="req">*</span></label>
                        <input type="text" name="contact" required>
                    </div>
                    <div class="input-group">
                        <label>Username <span class="req">*</span></label>
                        <input type="text" name="username" required>
                    </div>

                    <div class="input-group">
                        <label>Password <span class="req">*</span></label>
                        <input type="password" name="password" required>
                    </div>
                    <div class="input-group">
                        <label>Confirm Password <span class="req">*</span></label>
                        <input type="password" name="confirm_password" required>
                    </div>

                    <div class="input-group span-full">
                        <label>Affiliations</label>
                        <textarea name="affiliations" rows="4"></textarea>
                    </div>
                </div>

                <div class="form-footer">
                 <div class="terms-container">
                      <input type="checkbox" id="terms" required>
                     <label for="terms">By signing up, I agree to the <a href="#">Terms and Conditions</a>.</label>
                </div>
                    <button type="submit" class="signup-btn">SIGNUP</button>
                </div>
            </form>
        </main>

        <!-- FOOTER VISUAL -->
        <div class="footer-visual">
            <div class="cta-content">
                <h2>Want to be a<br>Campus Crew?</h2>
                <p>Email us at campustails@gmail.com</p>
                <div class="socials">
                    <i class="fab fa-facebook"></i><i class="fab fa-instagram"></i><i class="fab fa-tiktok"></i>
                </div>
            </div>
            <img src="../resources/footer.png" alt="Footer Character" class="footer-img">
        </div>
    </div>

    <script>
        // Role toggle logic
        const roleSelect = document.getElementById('roleSelect');
        const pBox = document.getElementById('programBox');
        const yBox = document.getElementById('yearBox');
        const oBox = document.getElementById('officeBox');

        roleSelect.addEventListener('change', () => {
            if(roleSelect.value === 'student') {
                pBox.style.display = 'block'; yBox.style.display = 'block'; oBox.style.display = 'none';
            } else {
                pBox.style.display = 'none'; yBox.style.display = 'none'; oBox.style.display = 'block';
            }
        });
    </script>
</body>
</html>