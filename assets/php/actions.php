<?php
require_once 'functions.php';
require_once 'send_code.php';

// Handle Signup
if (isset($_GET['signup'])) {
    $response = validateSignupForm($_POST);

    if ($response['status']) {
        if (createUser($_POST)) {
            header("Location: ../../?login&newuser");
            exit();
        } else {
            echo "<script>alert('Something went wrong while creating user!')</script>";
        }
    } else {
        $_SESSION['formdata'] = $_POST;
        $_SESSION['error'] = $response;
        header("Location: ../../?signup");
        exit();
    }
}

// Handle Login
if (isset($_GET['login'])) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        exit("Invalid request method.");
    }
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        header("Location: ../../?login");
        exit();
    }

    $email_esc = mysqli_real_escape_string($db, $email);
    $password_md5 = md5($password);

    $query = "SELECT * FROM user WHERE email = '$email_esc' AND password = '$password_md5'";
    $result = mysqli_query($db, $query);

    if ($user = mysqli_fetch_assoc($result)) {
        $_SESSION['Auth'] = true;
        $_SESSION['userdata'] = $user;

        if ($user['ac_status'] == 0) {
            $code = rand(111111, 999999);
            $_SESSION['code'] = $code;
            sendCode($user['email'], 'Verify your email', $code);
            header("Location: ../../?verify");
            exit();
        } elseif ($user['ac_status'] == 3) {
            header("Location: /ecommerce_e/#");
            exit();
        }

        header("Location: ../../");
        exit();
    } else {
        $_SESSION['formdata'] = $_POST;
        $_SESSION['error'] = ['message' => 'Invalid email or password'];
        header("Location: ../../?login");
        exit();
    }
}







// Handle Email Verification
if (isset($_GET['verify_email'])) {
    $user_code = $_POST['code'] ?? null;
    $code = $_SESSION['code'] ?? null;

    if ($code && $code == $user_code) {
        if (verifyEmail($_SESSION['userdata']['email'])) {
            // Update ac_status to 1 inside verifyEmail()
            header('Location: ../../?verified');
            exit();
        } else {
            echo "Something went wrong during email verification.";
        }
    } else {
        $_SESSION['error'] = [
            'msg' => empty($user_code) ? 'Enter six-digit verification code' : 'Incorrect verification code',
            'field' => 'email_verify'
        ];
        header('Location: ../../?verify');
        exit();
    }
}

// Resend Verification Code
if (isset($_GET['resendCode'])) {
    $email = $_SESSION['userdata']['email'] ?? null;

    if ($email) {
        $code = rand(111111, 999999);
        $_SESSION['code'] = $code;
        sendCode($email, 'Your new verification code', $code);
        $_SESSION['message'] = "Verification code resent successfully.";
    } else {
        $_SESSION['error'] = ['msg' => 'Unable to resend verification code.'];
    }
    header('Location: ../../?verify');
    exit();
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../../');
    exit();
}

// Forgot Password Request
if (isset($_GET['forgotpassword'])) {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $_SESSION['error'] = ['msg' => "Enter your email ID", 'field' => 'email'];
        header('Location: ../../?forgotpassword');
        exit();
    } elseif (!isEmailRegistered($email)) {
        $_SESSION['error'] = ['msg' => "Email ID is not registered", 'field' => 'email'];
        header('Location: ../../?forgotpassword');
        exit();
    } else {
        $_SESSION['forgot_email'] = $email;
        $forgotCode = rand(111111, 999999);
        $_SESSION['forgot_code'] = $forgotCode;
        sendCode($email, 'Forgot password verification code', $forgotCode);
        header('Location: ../../?forgotpassword&resended');
        exit();
    }
}

// Verify Forgot Password Code
if (isset($_GET['verify'])) {
    $user_code = $_POST['verification_code'] ?? null;
    $code = $_SESSION['forgot_code'] ?? null;

    if ($code && $code == $user_code) {
        $_SESSION['auth_temp'] = true;
        header('Location: ../../?changepassword');
        exit();
    } else {
        $_SESSION['error'] = [
            'msg' => empty($user_code) ? 'Enter 6-digit code' : 'Incorrect verification code',
            'field' => 'email_verify'
        ];
        header('Location: ../../?forgotpassword');
        exit();
    }
}

// Change Password
if (isset($_GET['changepassword'])) {
    if (empty($_POST['password'])) {
        $_SESSION['error'] = ['msg' => "Enter your new password", 'field' => 'password'];
        header('Location: ../../?forgotpassword');
        exit();
    } else {
        resetPassword($_SESSION['forgot_email'], $_POST['password']);
        // Clear forgot session variables for security
        unset($_SESSION['forgot_email'], $_SESSION['forgot_code'], $_SESSION['auth_temp']);
        header('Location: ../../?reseted');
        exit();
    }
}

// Update Profile
if (isset($_GET['updateprofile'])) {
    $profilePicData = isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK ? $_FILES['profile_pic'] : null;

    $response = validateUpdateForm($_POST, $profilePicData);

    if ($response['status']) {
        $updateResponse = updateProfile($_POST, $profilePicData);
        if ($updateResponse['status']) {
            header("Location: ../../?edit_profile");
            exit();
        } else {
            echo "Something went wrong: " . $updateResponse['msg'];
        }
    } else {
        $_SESSION['error'] = $response;
        header("Location: ../../?edit_profile");
        exit();
    }
}

// Add Post (if applicable)
if (isset($_GET['addpost'])) {
    $image = isset($_FILES['post_img']) && $_FILES['post_img']['size'] > 0 ? $_FILES['post_img'] : null;

    if ($image) {
        $response = validatePostImage($image);
        if (!$response['status']) {
            $_SESSION['error'] = $response;
            header("location:../../");
            exit();
        }
    }

    $result = createPost($_POST['post_text'], $image, $_POST['post_address'], $_POST['post_pincode']);

    if ($result === true) {
        header("Location: ../../");
        exit();
    } else {
        echo $result;
    }
}

// Add Comment
if (isset($_GET['addComment'])) {
    if (!empty($_POST['post_text']) && !empty($_POST['post_id'])) {
        $post_id = $_POST['post_id'];
        $result = addComment($post_id, $_POST['post_text']);

        if ($result === true) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            echo $result;
            exit;
        }
    } else {
        showError('post_text');
        exit;
    }
}

// Search Users By Pincode
if (isset($_GET['SearchUsers'])) {
    if (!empty($_POST['pincode'])) {
        $pincode = trim($_POST['pincode']);
        $result = searchUsersByPincode($pincode);

        if ($result !== false) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            echo "No users found for this pincode.";
            exit;
        }
    } else {
        showError('pincode');
        exit;
    }
}

// AJAX Search Users
if (isset($_GET['action']) && $_GET['action'] == "search_users") {
    $pincode = $_GET['pincode'] ?? '';
    echo json_encode(getUsersByPincode($pincode));
    exit;
}

// Add Message
if (isset($_GET['addMessage'])) {
    if (!empty($_POST['message_text']) && !empty($_POST['receiver_id'])) {
        $receiver_id = $_POST['receiver_id'];
        $result = addMessage($receiver_id, $_POST['message_text']);

        if ($result === true) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            echo $result;
            exit;
        }
    } else {
        showError('message_text');
        exit;
    }
}


//table data fetching
// $action = $_POST['action'] ?? '';

// if ($action === 'get_table_data') {
//     $table = $_POST['table'] ?? '';

//     if (!$table) {
//         echo json_encode(['status' => 'error', 'message' => 'No table specified']);
//         exit;
//     }

//     $data = getTableData($table);

//     if (!$data) {
//         echo json_encode(['status' => 'error', 'message' => 'Error loading table data']);
//         exit;
//     }

//     echo json_encode([
//         'status' => 'success',
//         'columns' => $data['columns'],
//         'rows' => $data['rows']
//     ]);
//     exit;
// }

// echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
// exit;


$action = $_POST['action'] ?? '';
if ($action === 'get_table_data') {
    $table = $_POST['table'] ?? '';
    if (!$table) {
        echo json_encode(['status' => 'error', 'message' => 'No table specified']);
        exit;
    }
    $data = getTableData($table);
    if (!$data) {
        echo json_encode(['status' => 'error', 'message' => 'Error loading table data']);
        exit;
    }
    echo json_encode([
        'status' => 'success',
        'columns' => $data['columns'],
        'rows' => $data['rows']
    ]);
    exit;
} elseif ($action === 'edit_row') {
    $table = $_POST['table'] ?? '';
    $rowIndex = $_POST['row_index'] ?? '';
    $columnName = $_POST['column_name'] ?? '';
    $newValue = $_POST['new_value'] ?? '';
    if (!$table || !$rowIndex || !$columnName || !$newValue) {
        echo json_encode(['status' => 'error', 'message' => 'Missing data for edit']);
        exit;
    }
    if (editRow($table, $rowIndex, $columnName, $newValue)) {
        echo json_encode(['status' => 'success', 'message' => 'Row updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating row']);
    }
    exit;
} elseif ($action === 'delete_row') {
    $table = $_POST['table'] ?? '';
    $id = $_POST['id'] ?? ''; // <-- this must be the actual database ID

    if (!$table || !$id) {
        echo json_encode(['status' => 'error', 'message' => 'Missing data for delete']);
        exit;
    }

    if (deleteRow($table, $id)) {
        echo json_encode(['status' => 'success', 'message' => 'Row deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error deleting row']);
    }

    exit;
}


//add product
if (isset($_GET['action']) && $_GET['action'] === 'addproduct') {
    header('Content-Type: application/json');

    $response = validateProductForm($_POST, $_FILES);

    if ($response['status']) {
        if (createProduct($_POST, $_FILES)) {
            echo json_encode(['status' => 'success']);
            exit();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add product.']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Validation failed.', 'errors' => $response['messages']]);
        exit();
    }
}


echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
exit;


?>