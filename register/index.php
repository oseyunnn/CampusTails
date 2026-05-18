<?php
include('../utils/db_config.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Collect Data & Validate Passwords
    $role = $_POST['role']; 
    $username = $_POST['username'];
    $password_raw = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password_raw !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $password = password_hash($password_raw, PASSWORD_DEFAULT);

        // 2. Check PawCrewCode (Admin Verification)
        $account_type = 'user';
        if (!empty($_POST['crew_code'])) {
            $codeCheck = supabase_query("admin_codes?admin_code=eq." . urlencode($_POST['crew_code']), "GET");
            if (!empty($codeCheck)) { 
                $account_type = 'admin'; 
            }
        }

        // 3. Prepare User Data
        $userData = [
            "first_name"     => $_POST['first_name'],
            "last_name"      => $_POST['last_name'],
            "username"       => $username,
            "email"          => $_POST['email'],
            "password_hash"  => $password,
            "contact_number" => $_POST['contact'],
            "affiliation"    => $_POST['affiliations'],
            "role"           => $role,
            "account_type"   => $account_type
        ];

        // 4. Attempt Insert
        $userResponse = supabase_query("paw_users", "POST", $userData);

        // Check for unique constraint violations or errors
        if (isset($userResponse['code'])) {
            if ($userResponse['code'] === '23505') {
                if (strpos($userResponse['message'], 'username') !== false) {
                    $error = "The username '" . $username . "' is already taken. Please choose another one.";
                } else {
                    $error = "This email address is already registered to an account.";
                }
            } else {
                $error = "Database Error: " . $userResponse['message'];
            }
        } 
        // 5. If Success, Insert Into Specific Profile Type
        else if (!empty($userResponse) && isset($userResponse[0]['user_id'])) {
            $new_uuid = $userResponse[0]['user_id'];
            
            if ($role === 'student') {
                $profileData = [
                    "user_id"        => $new_uuid, 
                    "student_number" => $_POST['id_number'], 
                    "program"        => $_POST['program'], 
                    "year_level"     => $_POST['year_level']
                ];
                supabase_query("student_profiles", "POST", $profileData);
            } else if ($role === 'faculty') {
                $profileData = [
                    "user_id"             => $new_uuid, 
                    "office"              => $_POST['office'], 
                    "institutional_email" => $_POST['email']
                ];
                supabase_query("faculty_profiles", "POST", $profileData);
            }

            // Success Pop-up and Redirect
            echo "<script>
                    alert('Sign up successful! You can now log in.');
                    window.location.href = '../login/index.php';
                  </script>";
            exit();
        } else {
            $error = "Registration failed due to an unexpected response configuration format.";
        }
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
        <header class="reg-header">
            <div class="nav-container">
                <div class="logo-side"><img src="../resources/Logo.png" alt="CampusTails"></div>
                <nav class="nav-links">
                    <a href="#">home</a><a href="#">about</a><a href="#">pets</a><a href="../login/index.php">login</a>
                </nav>
            </div>
        </header>

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

                    <div class="input-group" id="programBox" style="display: none;">
                        <label>Program <span class="req">*</span></label>
                        <input type="text" name="program" id="programInput">
                    </div>
                    <div class="input-group" id="yearBox" style="display: none;">
                        <label>Year Level <span class="req">*</span></label>
                        <input type="text" name="year_level" id="yearInput">
                    </div>
                    <div class="input-group span-full" id="officeBox" style="display: none;">
                        <label>Office <span class="req">*</span></label>
                        <input type="text" name="office" id="officeInput">
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
        const roleSelect = document.getElementById('roleSelect');
        const pBox = document.getElementById('programBox');
        const yBox = document.getElementById('yearBox');
        const oBox = document.getElementById('officeBox');
        
        const programInput = document.getElementById('programInput');
        const yearInput = document.getElementById('yearInput');
        const officeInput = document.getElementById('officeInput');

        roleSelect.addEventListener('change', () => {
            if(roleSelect.value === 'student') {
                pBox.style.display = 'block'; 
                yBox.style.display = 'block'; 
                oBox.style.display = 'none';
                
                programInput.required = true;
                yearInput.required = true;
                officeInput.required = false;
            } else if(roleSelect.value === 'faculty') {
                pBox.style.display = 'none'; 
                yBox.style.display = 'none'; 
                oBox.style.display = 'block';
                
                programInput.required = false;
                yearInput.required = false;
                officeInput.required = true;
            }
        });
    </script>

    <?php if (!empty($error)): ?>
        <script>
            // Safely converts PHP string variables into valid JavaScript structures
            alert(<?php echo json_encode($error); ?>);
        </script>
    <?php endif; ?>
</body>
</html>