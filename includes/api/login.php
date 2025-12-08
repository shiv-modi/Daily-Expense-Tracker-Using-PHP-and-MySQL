<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

include_once('../database.php');
include_once('../jwt.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $email = $input['email'] ?? $_POST['email'] ?? '';
    $password = $input['password'] ?? $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Email and password are required']);
        exit;
    }
    
    $email = $db->real_escape_string($email);
    
    $query = "SELECT id, name, email, password FROM users WHERE email = '$email'";
    $result = $db->query($query);
    
    if ($result && $result->num_rows == 1) {
        $user = $result->fetch_object();
        
        if (password_verify($password, $user->password)) {
            session_start();
            $_SESSION['detsuid'] = $user->id;
            
            $payload = [
                'user_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name
            ];
            
            $access_token = JWT::encode($payload);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Login successful',
                'access_token' => $access_token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
        }
    } else {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
?>
