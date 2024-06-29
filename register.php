<?php
include 'config.php';

$data = json_decode(file_get_contents("php://input"));

if ($data) {
    error_log(print_r($data, true)); // Debug line

    $firstname = $data->firstname ?? null;
    $lastname = $data->lastname ?? null;
    $dob = $data->dob ?? null;
    $email = $data->email ?? null;
    $phone = $data->phone ?? null;
    $password = isset($data->password) ? password_hash($data->password, PASSWORD_DEFAULT) : null;

    if ($firstname && $lastname && $dob && $email && $phone && $password) {
        $sql = "INSERT INTO users (firstname, lastname, dob, email, phone, password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $firstname, $lastname, $dob, $email, $phone, $password);

        if ($stmt->execute()) {
            echo json_encode(["message" => "User registered successfully"]);
        } else {
            echo json_encode(["message" => "Error: " . $stmt->error]);
        }
    } else {
        echo json_encode(["message" => "Missing required fields"]);
    }
} else {
    echo json_encode(["message" => "Invalid input"]);
}

$conn->close();
?>
