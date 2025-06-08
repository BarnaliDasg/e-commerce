<?php
require_once 'configu.php'; // Ensure this file sets up the database connection

$db = getDbConnection(); // Global DB connection

// Show page function
function showPage($page, $data = "") {
    include("assets/pages/$page.php");
}

// Follow a user
function followUser($u_id) {
    global $db;
    $current_user = $_SESSION['userdata']['id'];
    $query = "INSERT INTO follow_list(follower_id, u_id) VALUES($current_user, $u_id)";
    return mysqli_query($db, $query);
}

// Unfollow a user
function unfollowUser($u_id) {
    global $db;
    $current_user = $_SESSION['userdata']['id'];
    $query = "DELETE FROM follow_list WHERE follower_id = $current_user AND u_id = $u_id";
    return mysqli_query($db, $query);
}

// Check like status
function checklikeStatus($post_id) {
    global $db;
    $current_user = $_SESSION['userdata']['id'];
    $query = "SELECT COUNT(*) as row FROM likes WHERE u_id = $current_user AND post_id = $post_id";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_assoc($run)['row'];
}

// Check comment status
function checkcommentStatus($post_id) {
    global $db;
    $current_user = $_SESSION['userdata']['id'];
    $query = "SELECT COUNT(*) as row FROM comments WHERE u_id = $current_user AND post_id = $post_id";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_assoc($run)['row'];
}

// Like a post
function like($post_id) {
    global $db;
    $current_user = $_SESSION['userdata']['id'];
    $query = "INSERT INTO likes(post_id, u_id) VALUES($post_id, $current_user)";
    return mysqli_query($db, $query);
}

// Unlike a post
function unlike($post_id) {
    global $db;
    $current_user = $_SESSION['userdata']['id'];
    $query = "DELETE FROM likes WHERE u_id = $current_user AND post_id = $post_id";
    return mysqli_query($db, $query);
}

// Get like counts
function getLikes($post_id) {
    global $db;
    $query = "SELECT * FROM likes WHERE post_id = $post_id";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_all($run, true);
}

// Show form error
function showError($field) {
    if (isset($_SESSION['error'])) {
        $error = $_SESSION['error'];
        if (isset($error['field']) && $field == $error['field']) {
            ?>
            <div class="alert alert-danger my-2" role="alert">
                <?php echo htmlspecialchars($error['msg']); ?>
            </div>
            <?php
        }
    }
}

// Show previous form data
function showFormData($field) {
    if (isset($_SESSION['formdata'])) {
        $formdata = $_SESSION['formdata'];
        return isset($formdata[$field]) ? htmlspecialchars($formdata[$field]) : '';
    }
    return '';
}

// Validate signup form
function validateSignupForm($form_data) {
    $response = ['status' => true];

    if (empty($form_data['password'])) {
        $response = ['status' => false, 'msg' => "Password is not given", 'field' => 'password'];
    } elseif (empty($form_data['email'])) {
        $response = ['status' => false, 'msg' => "Email is not given", 'field' => 'email'];
    } elseif (empty($form_data['lname'])) {
        $response = ['status' => false, 'msg' => "Last name is not given", 'field' => 'lname'];
    } elseif (empty($form_data['fname'])) {
        $response = ['status' => false, 'msg' => "First name is not given", 'field' => 'fname'];
    } elseif (isEmailRegistered($form_data['email'])) {
        $response = ['status' => false, 'msg' => "Email ID is already registered", 'field' => 'email'];
    }

    return $response;
}

// Check if email is already registered
function isEmailRegistered($email) {
    global $db;
    $email = mysqli_real_escape_string($db, $email);
    $query = "SELECT COUNT(*) as row FROM user WHERE email = '$email'";
    $run = mysqli_query($db, $query);
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'] > 0;
}

// Create new user
function createUser($data) {
    global $db;
    $fname = mysqli_real_escape_string($db, $data['fname']);
    $lname = mysqli_real_escape_string($db, $data['lname']);
    $email = mysqli_real_escape_string($db, $data['email']);
    $password = mysqli_real_escape_string($db, $data['password']);

    $password=md5($password);

    $query = "INSERT INTO user (fname, lname, email, password) 
              VALUES ('$fname', '$lname', '$email', '$password')";
    return mysqli_query($db, $query);
}

// Validate login form
function validateLoginForm($form_data) {
    $response = ['status' => true];
    $blank = false;

    if (empty($form_data['email'])) {
        $response = ['status' => false, 'msg' => "Email is not given", 'field' => 'email'];
        $blank = true;
    }

    if (empty($form_data['password'])) {
        $response = ['status' => false, 'msg' => "Password is not given", 'field' => 'password'];
        $blank = true;
    }

    if (!$blank) {
        $userCheck = checkUser($form_data);

        if (!$userCheck['status']) {
            // Return message from checkUser (e.g. not verified or invalid)
            $response = [
                'status' => false,
                'msg' => $userCheck['msg'] ?? "Invalid email or password",
                'field' => $userCheck['field'] ?? 'checkUser'
            ];
        } else {
            $response['user'] = $userCheck['user'];
        }
    }

    return $response;
}

// Check user during login with ac_status check
function checkUser($login_data) {
    $db = getDbConnection();

    // Escape email input to prevent SQL injection
    $email = mysqli_real_escape_string($db, $login_data['email']);
    $password = md5($login_data['password']); // Hashing using md5 (must match signup)

    // Build and execute query
    $query = "SELECT * FROM user WHERE email = '$email' AND password = '$password'";
    $run = mysqli_query($db, $query);

    if (!$run) {
        // Don't show raw error to users; log instead
        error_log("Login query failed: " . mysqli_error($db));
        return [
            'status' => false,
            'msg' => "Something went wrong. Please try again later."
        ];
    }

    $user = mysqli_fetch_assoc($run);

    // Check if user exists and account is verified
    if ($user && $user['ac_status'] == 1) {
        return ['status' => true, 'user' => $user];
    } elseif ($user && $user['ac_status'] == 0) {
        return [
            'status' => false,
            'msg' => "Account not verified. Please check your email for verification.",
            'field' => 'verification'
        ];
    } else {
        return [
            'status' => false,
            'msg' => "Invalid email or password."
        ];
    }
}


// Get user by ID
function getUser($user_id) {
    global $db;
    if (!is_numeric($user_id) || $user_id <= 0) return false;

    $user_id = mysqli_real_escape_string($db, $user_id);
    $query = "SELECT * FROM user WHERE id = $user_id";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_assoc($run);
}

// Get user by email
function getUserByEmail($email) {
    global $db;
    $email = mysqli_real_escape_string($db, $email);
    $query = "SELECT * FROM user WHERE email = '$email'";
    $run = mysqli_query($db, $query);
    return mysqli_fetch_assoc($run);
}

// Verify email (set ac_status = 1)
function verifyEmail($email) {
    global $db;
    $stmt = $db->prepare("UPDATE user SET ac_status = 1 WHERE email = ?");
    if ($stmt === false) {
        error_log("Prepare failed: " . $db->error);
        return false;
    }
    $stmt->bind_param("s", $email);
    return $stmt->execute();
}

// Resend verification code (assuming sendCode exists)
function resendCode($email) {
    $verificationCode = rand(111111, 999999);
    if (sendCode($email, 'Verify your email', $verificationCode)) {
        $_SESSION['code'] = $verificationCode;
        return "Verification code has been resent to $email.";
    } else {
        return "Failed to send verification code.";
    }
}

// Reset password
function resetPassword($email, $newPassword) {
    $conn = getDbConnection();

    // Using password_hash here for better security (consider updating login as well)
    $hashedPassword = md5($newPassword);

    $sql = "UPDATE user SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ss", $hashedPassword, $email);
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        }
        $stmt->close();
    }
    return false;
}


//get tablename
function getAllTableNames() {
    $db = getDbConnection();
    $result = mysqli_query($db, "SHOW TABLES");
    $tables = [];
    if ($result) {
        while ($row = mysqli_fetch_array($result)) {
            $tables[] = $row[0];
        }
    }
    return $tables;
}
//get tabledata

function getTableData($table) {
    $db = getDbConnection();
    // Validate table name to avoid SQL injection
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
        return false;
    }
    // Get columns
    $columnsResult = mysqli_query($db, "DESCRIBE `$table`");
    if (!$columnsResult) return false;
    $columns = [];
    while ($col = mysqli_fetch_assoc($columnsResult)) {
        $columns[] = $col['Field'];  // Only store column names
    }
    // Get rows (limit 100)
    $rowsResult = mysqli_query($db, "SELECT * FROM `$table` LIMIT 100");
    if (!$rowsResult) return false;
    $rows = [];
    while ($row = mysqli_fetch_row($rowsResult)) {
        $rows[] = $row;
    }
    return ['columns' => $columns, 'rows' => $rows];
}
// Function to edit a row (USE PREPARED STATEMENTS!)
function editRow($table, $rowIndex, $columnName, $newValue) {
    $db = getDbConnection();
    // Validate table name to avoid SQL injection
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
        return false;
    }
    // Sanitize the inputs
    $table = mysqli_real_escape_string($db, $table);
    $columnName = mysqli_real_escape_string($db, $columnName);
    $newValue = mysqli_real_escape_string($db, $newValue);
    // Construct the update query (USE PREPARED STATEMENTS!)
    $sql = "UPDATE `$table` SET `$columnName` = '$newValue' WHERE id = '$rowIndex'"; //VERY INSECURE!
    if (mysqli_query($db, $sql)) {
        return true;
    } else {
        error_log("Error updating row: " . mysqli_error($db));
        return false;
    }
}
// Function to delete a row (USE PREPARED STATEMENTS!)
function deleteRow($table, $id) {
    $db = getDbConnection();

    // Validate table name to allow only alphanumeric + underscore
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
        error_log("Invalid table name.");
        return false;
    }

    // Validate that ID is a number
    if (!is_numeric($id)) {
        error_log("Invalid ID.");
        return false;
    }

    // Use prepared statements to avoid SQL injection
    $stmt = $db->prepare("DELETE FROM `$table` WHERE id = ?");
    if (!$stmt) {
        error_log("Prepare failed: " . $db->error);
        return false;
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        return true;
    } else {
        error_log("Execute failed: " . $stmt->error);
        return false;
    }
}


//add product
function validateProductForm($data, $file) {
    $errors = [];

    if (empty($data['name'])) $errors[] = "Product name is required.";
    if (empty($data['category'])) $errors[] = "Category is required.";
    if (empty($data['target_audience'])) $errors[] = "Target audience is required.";
    if (!is_numeric($data['price'])) $errors[] = "Valid price is required.";
    if (!is_numeric($data['stock'])) $errors[] = "Valid stock is required.";

    if (!isset($file['product_image']) || $file['product_image']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Product image is required.";
    } else {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!in_array($file['product_image']['type'], $allowedTypes)) {
            $errors[] = "Image must be JPG, PNG, WEBP, or GIF.";
        }

        if ($file['product_image']['size'] > 2 * 1024 * 1024) { // 2MB limit
            $errors[] = "Image size must be under 2MB.";
        }
    }

    return [
        'status' => empty($errors),
        'messages' => $errors
    ];
}


function createProduct($data, $file) {
    global $db;

    // Folder to save uploaded files
    $uploadDir = __DIR__ . '/../images/product_image/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $imageName = uniqid('img_') . '_' . basename($file['product_image']['name']);
    $targetPath = $uploadDir . $imageName;

    if (!move_uploaded_file($file['product_image']['tmp_name'], $targetPath)) {
        return false;
    }

    // Path to store in DB (relative to your project root)
    $imagePathForDB = 'assets/images/product_image/' . $imageName;

    $stmt = $db->prepare("INSERT INTO products (name, description, category, target_audience, price, image_url, stock) VALUES (?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) return false;

    $name = $data['name'];
    $description = $data['description'] ?? null;
    $category = $data['category'];
    $target_audience = $data['target_audience'];
    $price = $data['price'];
    $stock = $data['stock'];

    $stmt->bind_param("ssssdsi", $name, $description, $category, $target_audience, $price, $imagePathForDB, $stock);
    return $stmt->execute();
}


?>
