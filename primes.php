<?php
function is_prime($num) {
    if ($num < 2) return false;
    for ($i = 2; $i <= sqrt($num); $i++) {
        if ($num % $i == 0) return false;
    }
    return true;
}

$data = json_decode(file_get_contents("php://input"));
$number = $data->number;
$primes = [];

for ($i = 2; $i < $number; $i++) {
    if (is_prime($i)) {
        $primes[] = $i;
    }
}

echo json_encode($primes);
?>
