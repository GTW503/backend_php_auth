<?php
include 'config.php';

$data = json_decode(file_get_contents("php://input"));

$email = $data->email;
$otp_code = $data->otp_code;
$new_password = password_hash($data->new_password, PASSWORD_DEFAULT);

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    $sql = "SELECT * FROM otp_codes WHERE user_id = ? AND otp_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user['id'], $otp_code);
    $stmt->execute();
    $otp_result = $stmt->get_result();
    
    if ($otp_result->num_rows > 0) {
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_password, $user['id']);
        
        if ($stmt->execute()) {
            echo json_encode(["message" => "Password reset successful"]);
        } else {
            echo json_encode(["message" => "Error: " . $stmt->error]);
        }
    } else {
        echo json_encode(["message" => "Invalid OTP code"]);
    }
} else {
    echo json_encode(["message" => "User not found"]);
}

$conn->close();
?>
