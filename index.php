<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <h2>STUDENT REGISTRATION</h2>

    <form action="process.php" method="POST">

        <label>Full name </label>
        <input type="text" name="fullname"
        placeholder="Enter your name" required>

        <label>Email address </label>
        <input type="email" name="email"
        placeholder="Enter your email" required>

        <label>Phone number </label>
        <input type="tel" name="phone"
        placeholder=" Enter your phone number" required>

        <label>Matriculation number</label>
        <input type="text" name="matricule"
        placeholder="Enter your matricule" required>

        <label>Date of birth</label>
        <input type="date" name="dob" 
        placeholder="Enter your date of birth" required>

        <label>Department</label>
        <select name="department" required>
            <option value="">Select department</option>
            <option>Software Engineering</option>
            <option>Computer Science</option>
            <option>Business Administration</option>
            <option>Accounting</option>
        </select>

        <label>Level</label>
        <select name="level" required>
            <option value="">Select level</option>
            <option value="1">Level 1</option>
            <option value="2">Level 2</option>
            <option value="3">Level 3</option>
        </select>

        <label>Gender</label>

        <div class="gender">
            <input type="radio" name="gender"
            value="Female" required> Female

            <input type="radio" name="gender"
            value="Male"> Male

            <input type="radio" name="gender"
            value="Prefer not to say">
            Prefer not to say
        </div>

        <label>Password </label>
        <input type="password" name="password"
        required>

        <label>Confirm password </label>
        <input type="password" name="confirm_password"
        required>

        <div class="check">
            <input type="checkbox" name="accuracy"
            value="yes" required>
            I confirm that the information provided is accurate.
        </div>

        <button type="submit">
            SUBMIT REGISTRATION
        </button>

    </form>

</div>

</body>
</html>