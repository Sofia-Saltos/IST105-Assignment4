<?php
// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $a = $_POST['a'];
    $b = $_POST['b'];
    $c = $_POST['c'];

    // Validate input (ensure they are numeric)
    if (!is_numeric($a) || !is_numeric($b) || !is_numeric($c)) {
        echo "<p>Error: All inputs must be numeric.</p>";
    } else {
        // Prepare the command to call the Python script
        $command = escapeshellcmd("python3 calculate.py $a $b $c");
        // Execute the Python script and capture the output
        $output = shell_exec($command);
        // Display the result
        echo "<h2>Result:</h2>";
        echo $output;
    }
} else {
    echo "<p>No data submitted. Please go back to the form.</p>";
}
?>
