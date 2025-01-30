<?php


class PythonCalculator {
    private $pythonScript;
    private $errorMessage;
    
    public function __construct($scriptPath = 'calculate.py') {
        $this->pythonScript = $scriptPath;
        $this->errorMessage = '';
    }
    
    public function validate($a, $b, $c): bool {
        // Check if values are set
        if (!isset($a) || !isset($b) || !isset($c)) {
            $this->errorMessage = "All fields are required.";
            return false;
        }
        
        // Check if values are numeric
        if (!is_numeric($a) || !is_numeric($b) || !is_numeric($c)) {
            $this->errorMessage = "All inputs must be numeric values.";
            return false;
        }
        
        return true;
    }
    
    public function calculate($a, $b, $c) {
        // Escape shell arguments to prevent command injection
        $escapedArgs = array_map('escapeshellarg', [$a, $b, $c]);
        
        // Set environment variables for Python script
        putenv("PYTHONIOENCODING=utf-8");
        
        // Construct the command
        $command = sprintf(
            'python3 %s 2>&1',
            $this->pythonScript
        );
        
        // Create descriptors for process
        $descriptorspec = array(
            0 => array("pipe", "r"),  // stdin
            1 => array("pipe", "w"),  // stdout
            2 => array("pipe", "w")   // stderr
        );
        
        // Open process
        $process = proc_open($command, $descriptorspec, $pipes);
        
        if (is_resource($process)) {
            // Write POST data to stdin
            $postData = http_build_query([
                'a' => $a,
                'b' => $b,
                'c' => $c
            ]);
            fwrite($pipes[0], $postData);
            fclose($pipes[0]);
            
            // Get output and errors
            $output = stream_get_contents($pipes[1]);
            $errors = stream_get_contents($pipes[2]);
            
            // Close pipes
            fclose($pipes[1]);
            fclose($pipes[2]);
            
            // Close process
            $return_value = proc_close($process);
            
            if ($return_value !== 0) {
                $this->errorMessage = "Error executing Python script: " . $errors;
                return false;
            }
            
            return $output;
        }
        
        $this->errorMessage = "Failed to execute Python script";
        return false;
    }
    
    public function getError(): string {
        return $this->errorMessage;
    }
}

// Start output buffering to prevent header issues
ob_start();

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $calculator = new PythonCalculator();
    
    // Get input values
    $a = $_POST['a'] ?? null;
    $b = $_POST['b'] ?? null;
    $c = $_POST['c'] ?? null;
    
    // Validate input
    if ($calculator->validate($a, $b, $c)) {
        // Calculate result
        $result = $calculator->calculate($a, $b, $c);
        
        if ($result !== false) {
            // Python script returns HTML, so we just need to echo it
            echo $result;
            ob_end_flush();
            exit;
        }
    }
    
    // If we get here, there was an error
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #ffd1dc;
                margin: 0;
                padding: 20px;
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .error-container {
                background-color: white;
                padding: 2rem;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                max-width: 600px;
                width: 100%;
            }
            .error-message {
                color: #dc3545;
                margin-bottom: 1rem;
            }
            .back-button {
                display: inline-block;
                padding: 0.5rem 1rem;
                background-color: #8b4513;
                color: white;
                text-decoration: none;
                border-radius: 4px;
                margin-top: 1rem;
            }
            .back-button:hover {
                background-color: #693410;
            }
        </style>
    </head>
    <body>
        <div class="error-container">
            <div class="error-message">
                <?php echo htmlspecialchars($calculator->getError()); ?>
            </div>
            <a href="form.php" class="back-button">Back to Form</a>
        </div>
    </body>
    </html>
    <?php
}
else {
    header("Location: form.php");
    exit;
}
?>