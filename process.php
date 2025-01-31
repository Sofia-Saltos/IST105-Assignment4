<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $a = $_POST['a'];
    $b = $_POST['b'];
    $c = $_POST['c'];

    // Validate input (ensure they are numeric)
    if (!is_numeric($a) || !is_numeric($b) || !is_numeric($c)) {
        echo "<p>Error: All inputs must be numeric.</p>";
    } else {
        // Prepare the data to be sent to the Python script
        $data = json_encode(array("a" => $a, "b" => $b, "c" => $c));
        
        // Execute the Python script and capture the output
        $command = escapeshellcmd("python3 calculate.py");
        $result = shell_exec("echo " . escapeshellarg($data) . " | " . $command);
        
        // Decode the result from the Python script
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
} else {
    echo "<p>No data submitted. Please go back to the form.</p>";
}
?>

