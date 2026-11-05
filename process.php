<?php
declare(strict_types=1);

const DEPARTMENTS = ['Software Engineering', 'Computer Science', 'Business Administration', 'Accounting'];
const LEVELS  = ['1', '2', '3'];
const GENDERS = ['Female', 'Male', 'Prefer not to say'];

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function field(string $key): string
{
    $value = $_POST[$key] ?? '';
    return is_string($value) ? trim($value) : '';
}

$errors = [];
$old    = [];
$notice = '';

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "student_registration";

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error) {
    die("connection failed:" . $conn->connect_error);
}
$conn->set_charset('utf8mb4');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {



    $fullname   = field('fullname');
    $email      = field('email');
    $phone      = str_replace([' ', '-'], '', field('phone'));
    $matricule  = strtoupper(field('matricule'));
    $dob        = field('dob');
    $department = field('department');
    $level      = field('level');
    $gender     = field('gender');
    $accuracy   = field('accuracy');
    $password   = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
    $confirm    = is_string($_POST['confirm_password'] ?? null) ? $_POST['confirm_password'] : '';

    
    if ($fullname === '') {
        $errors['fullname'] = 'Enter your full name.';
    } elseif (mb_strlen($fullname) < 2 || mb_strlen($fullname) > 100) {
        $errors['fullname'] = 'Full name must be between 2 and 100 characters.';
    } elseif (!preg_match("/^\p{L}[\p{L}\s.'-]*$/u", $fullname)) {
        $errors['fullname'] = 'Use letters, spaces, hyphens, apostrophes or full stops only.';
    }

    
    if ($email === '') {
        $errors['email'] = 'Enter your email address.';
    } elseif (strlen($email) > 255 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Enter a valid email address,.';
    }

   
    if ($phone === '') {
        $errors['phone'] = 'Enter your phone number.';
    } elseif (!preg_match('/^(\+237)?6[0-9]{8}$/', $phone)) {
        $errors['phone'] = 'Enter 9 digits starting with 6, e.g. 677123456 (optional +237).';
    }

    
    if ($matricule === '') {
        $errors['matricule'] = 'Enter your matriculation number.';
    } elseif (!preg_match('/^[A-Z]{2}[0-9]{7}$/', $matricule)) {
        $errors['matricule'] = 'Use 2 letters followed by 7 digits, e.g. SE2026001.';
    }

   
    if ($dob === '') {
        $errors['dob'] = 'Select your date of birth.';
    } else {
        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $dob);
        if ($date === false || $date->format('Y-m-d') !== $dob) {
            $errors['dob'] = 'Enter a real date.';
        } elseif ($date > new DateTimeImmutable('today')) {
            $errors['dob'] = 'Date of birth cannot be in the future.';
        } elseif ($date < new DateTimeImmutable('1900-01-01')) {
            $errors['dob'] = 'Enter a date after 1900.';
        }
    }


    if ($department === '') {
        $errors['department'] = 'Select your department.';
    } elseif (!in_array($department, DEPARTMENTS, true)) {
        $errors['department'] = 'Select a department from the list.';
    }

    if ($level === '') {
        $errors['level'] = 'Select your level.';
    } elseif (!in_array($level, LEVELS, true)) {
        $errors['level'] = 'Select a level from the list.';
    }

    if ($gender === '') {
        $errors['gender'] = 'Select an option.';
    } elseif (!in_array($gender, GENDERS, true)) {
        $errors['gender'] = 'Select an option from the list.';
    }

    if ($password === '') {
        $errors['password'] = 'Enter a password.';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters.';
    } elseif (strlen($password) > 72) {
        $errors['password'] = 'Password must be 72 characters or fewer.';
    }

    if ($confirm === '') {
        $errors['confirm_password'] = 'Confirm your password.';
    } elseif ($password !== $confirm) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    if ($accuracy !== 'yes') {
        $errors['accuracy'] = 'You must confirm that the information is accurate.';
    }

    if (!$errors) {
        try {
            $insert = $conn->prepare(
                "INSERT INTO students (fullname, email, phone, matricule, dob, department, level, gender)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $insert->bind_param(
                "ssssssss",
                $fullname,
                $email,
                $phone,
                $matricule,
                $dob,
                $department,
                $level,
                $gender
            );
            $insert->execute();
            $insert->close();
            $notice = 'Student registered successfully.';
        } catch (mysqli_sql_exception $exception) {
            error_log($exception->getMessage());
            $errors['database'] = 'The registration could not be saved. Check that the students table has the required columns.';
        }
    }

    if ($errors) {
        $old = [
            'fullname' => $fullname, 'email' => $email, 'phone' => field('phone'),
            'matricule' => $matricule, 'dob' => $dob, 'department' => $department,
            'level' => $level, 'gender' => $gender, 'accuracy' => $accuracy,
        ];
        $notice = 'Please correct the errors below and resubmit the form.';
    }

}

$cls = fn(string $key): string => isset($errors[$key]) ? ' has-error' : '';
$val = fn(string $key): string => e($old[$key] ?? '');
$msg = fn(string $key): string => e($errors[$key] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration result</title>
</head>
<body>
    <h1><?= e($notice) ?></h1>
    <?php if ($errors): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= e($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <p><a href="index.php">Return to the registration form</a></p>
</body>
</html>
