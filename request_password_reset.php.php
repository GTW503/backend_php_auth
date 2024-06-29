<?php
include 'config.php';
include 'send_mail.php';

$data = json_decode(file_get_contents("php://input"));

$email = $data->email;

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $otp_code = rand(100000, 999999);
    
    $sql = "INSERT INTO otp_codes (user_id, otp_code) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user['id'], $otp_code);
    
    if ($stmt->execute()) {
        sendMail($email, "Your OTP Code", "Your OTP code is: $otp_code");
        echo json_encode(["message" => "OTP sent"]);
    } else {
        echo json_encode(["message" => "Error: " . $stmt->error]);
    }
} else {
    echo json_encode(["message" => "User not found"]);
}

$conn->close();
?>
