<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $a = $_POST['a'];
    $b = $_POST['b'];
    $c = $_POST['c'];

    if (!is_numeric($a) || !is_numeric($b) || !is_numeric($c)) {
        echo "<p>Error: All inputs must be numeric.</p>";
    } else {
        $data = json_encode(array("a" => $a, "b" => $b, "c" => $c));
        $command = "python3 calculate.py";
        $result = shell_exec("echo " . escapeshellarg($data) . " | " . $command);

        if ($result === null) {
            echo "<p>Error: Failed to execute Python script.</p>";
        } else {
            $output = json_decode($result, true);
            echo "<h2>Result:</h2>";
            if (isset($output['result'])) {
                echo htmlspecialchars($output['result']);
            } elseif (isset($output['error'])) {
                echo "<p>Error: " . htmlspecialchars($output['error']) . "</p>";
            } elseif (isset($output['message'])) {
                echo "<p>Message: " . htmlspecialchars($output['message']) . "</p>";
            } else {
                echo "<p>An unexpected error occurred.</p>";
            }
        }
    }
} else {
    echo "<p>No data submitted. Please go back to the form.</p>";
}
?>
