<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CampusTails</title>
    <link rel="stylesheet" href="register.css">
    <!-- Font Awesome for Social Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <!-- Header Navigation -->
    <header>
        <div class="nav-container">
            <div class="logo">
                <img src="../resources/Logo.png" alt="CampusTails" height="45">
            </div>
            <nav class="main-nav">
                <a href="#">home</a>
                <a href="#">about</a>
                <a href="#">pets</a>
                <a href="#" class="active">login</a>
            </nav>
        </div>
    </header>

    <!-- Main Registration Section -->
    <main class="registration-container">
        <div class="registration-header">
            <img src="../resources/Logo.png" alt="CampusTails" class="form-logo">
            <h2>Welcome PawCrew!</h2>
            <p>Please fill out all necessary information below.</p>
        </div>

        <!-- Form started -->
        <form action="register_process.php" method="POST" class="register-form">
            
            <!-- Row 1: Names -->
            <div class="form-group span-5">
                <label for="firstName">First Name <span class="required">*</span></label>
                <input type="text" id="firstName" name="first_name" required>
            </div>
            <div class="form-group span-5">
                <label for="lastName">Last Name <span class="required">*</span></label>
                <input type="text" id="lastName" name="last_name" required>
            </div>
            <div class="form-group span-2">
                <label for="mi">M.I. <span class="required">*</span></label>
                <input type="text" id="mi" name="mi" maxlength="2" required>
            </div>

            <!-- Row 2: ID & Email -->
            <div class="form-group span-6">
                <label for="idNumber">ID Number <span class="required">*</span></label>
                <input type="text" id="idNumber" name="id_number" required>
            </div>
            <div class="form-group span-6">
                <label for="email">Institutional Email <span class="required">*</span></label>
                <input type="email" id="email" name="email" required>
            </div>

            <!-- Row 3: Birthday, Role & Code -->
            <div class="form-group span-4">
                <label for="birthday">Birthday <span class="required">*</span></label>
                <input type="date" id="birthday" name="birthday" required>
            </div>
            <div class="form-group span-4">
                <label for="role">Role <span class="required">*</span></label>
                <select id="role" name="role" required>
                    <option value="" disabled selected hidden>Student/Faculty</option>
                    <option value="student">Student</option>
                    <option value="faculty">Faculty</option>
                </select>
            </div>
            <div class="form-group span-4">
                <label for="crewCode">PawCrewCode</label>
                <input type="text" id="crewCode" name="crew_code" placeholder="Enter code if Admin">
            </div>

            <!-- Row 4: Student Conditional Fields -->
            <div class="form-group span-6 student-field hidden">
                <label for="program">Program <span class="required">*</span> <span class="helper-text">"appears only for student"</span></label>
                <input type="text" id="program" name="program">
            </div>
            <div class="form-group span-6 student-field hidden">
                <label for="yearLevel">Year Level <span class="required">*</span> <span class="helper-text">"appears only for student"</span></label>
                <input type="text" id="yearLevel" name="year_level">
            </div>

            <!-- Row 5: Faculty Conditional Field -->
            <div class="form-group span-12 faculty-field hidden">
                <label for="office">Office <span class="required">*</span> <span class="helper-text">"appears only for faculty"</span></label>
                <input type="text" id="office" name="office">
            </div>

            <!-- Row 6: Contact & Username -->
            <div class="form-group span-6">
                <label for="contact">Contact Number <span class="required">*</span></label>
                <input type="tel" id="contact" name="contact" required>
            </div>
            <div class="form-group span-6">
                <label for="username">Username <span class="required">*</span></label>
                <input type="text" id="username" name="username" required>
            </div>

            <!-- Row 7: Passwords -->
            <div class="form-group span-6">
                <label for="password">Password <span class="required">*</span></label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group span-6">
                <label for="confirmPassword">Confirm Password <span class="required">*</span></label>
                <input type="password" id="confirmPassword" name="confirm_password" required>
            </div>

            <!-- Row 8: Affiliations -->
            <div class="form-group span-12">
                <label for="affiliations">Affiliations</label>
                <textarea id="affiliations" name="affiliations" rows="3"></textarea>
            </div>

            <!-- Checkbox Terms -->
            <div class="terms-container span-12">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">By signing up, I agree to the <a href="#">Terms and Conditions</a>.</label>
            </div>

            <!-- Submit Button -->
            <div class="submit-container span-12">
                <button type="submit" class="signup-submit-btn">SIGNUP</button>
            </div>

        </form>
    </main>

    <!-- Footer Area -->
    <footer class="footer-cta">
        <div class="footer-content">
            <div class="footer-image-wrapper">
                <img src="../resources/man-dog.png" alt="Crew Member" class="footer-character">
            </div>
            <div class="footer-text-block">
                <h2>Want to be a<br>Campus Crew?</h2>
                <p class="email-text">Email us at <strong>campustails@gmail.com</strong></p>
                <div class="social-links">
                    <a href="#"><i class="fa-brands fa-facebook"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript to toggle Student/Faculty fields dynamically -->
    <script>
        const roleSelect = document.getElementById('role');
        const studentFields = document.querySelectorAll('.student-field');
        const facultyFields = document.querySelectorAll('.faculty-field');

        const programInput = document.getElementById('program');
        const yearLevelInput = document.getElementById('yearLevel');
        const officeInput = document.getElementById('office');

        roleSelect.addEventListener('change', function() {
            if (this.value === 'student') {
                // Show Student, Hide Faculty
                studentFields.forEach(f => f.classList.remove('hidden'));
                facultyFields.forEach(f => f.classList.add('hidden'));
                
                // Toggle required tags
                programInput.required = true;
                yearLevelInput.required = true;
                officeInput.required = false;
                officeInput.value = ""; // Clear input
            } else if (this.value === 'faculty') {
                // Show Faculty, Hide Student
                studentFields.forEach(f => f.classList.add('hidden'));
                facultyFields.forEach(f => f.classList.remove('hidden'));
                
                // Toggle required tags
                programInput.required = false;
                yearLevelInput.required = false;
                officeInput.required = true;
                programInput.value = ""; // Clear inputs
                yearLevelInput.value = "";
            }
        });
    </script>
</body>
</html>