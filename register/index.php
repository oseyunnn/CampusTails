<?php
require_once '../db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... (Your variable collection stays the same) ...

    try {
        // SQL INSERT using PDO placeholders
        $sql = "INSERT INTO users (first_name, last_name, mi, id_number, email, birthday, role, program, year_level, office, contact, username, password, affiliations, user_type) 
                VALUES (:fname, :lname, :mi, :id_num, :email, :bday, :role, :prog, :year, :off, :cont, :user, :pass, :aff, :utype)";
        
        $stmt = $conn->prepare($sql);
        
        // Execute with an array (much cleaner than mysqli bind_param)
        $stmt->execute([
            ':fname' => $fname,
            ':lname' => $lname,
            ':mi'    => $mi,
            ':id_num'=> $id_num,
            ':email' => $email,
            ':bday'  => $birthday,
            ':role'  => $role,
            ':prog'  => $program,
            ':year'  => $year_level,
            ':off'   => $office,
            ':cont'  => $contact,
            ':user'  => $username,
            ':pass'  => $password,
            ':aff'   => $affiliations,
            ':utype' => $user_type
        ]);

        header("Location: ../login/login.php?signup=success");
        exit();

    } catch (PDOException $e) {
        $error = "Registration failed: " . $e->getMessage();
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
                    <label class="check-container">
                        <input type="checkbox" required> By signing up, I agree to the <a href="#">Terms and Conditions</a>.
                    </label>
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