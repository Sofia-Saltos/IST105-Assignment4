<?php

class PythonCalculator {
    private $pythonScript;
    private $errorMessage;
    
    public function __construct($scriptPath = 'calculate.py') {
        $this->pythonScript = $scriptPath;
        $this->errorMessage = '';
    }
    
    public function validate($a, $b, $c): bool {
        if (!isset($a) || !isset($b) || !isset($c)) {
            $this->errorMessage = "All fields are required.";
            return false;
        }
        if (!is_numeric($a) || !is_numeric($b) || !is_numeric($c)) {
            $this->errorMessage = "All inputs must be numeric values.";
            return false;
        }
        return true;
    }
    
    public function calculate($a, $b, $c) {
        $escapedArgs = array_map('escapeshellarg', [$a, $b, $c]);
        putenv("PYTHONIOENCODING=utf-8");

        $command = sprintf('python3 %s 2>&1', $this->pythonScript);
        $descriptorspec = [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "w"]
        ];

        $process = proc_open($command, $descriptorspec, $pipes);

        if (is_resource($process)) {
            $postData = http_build_query(['a' => $a, 'b' => $b, 'c' => $c]);
            fwrite($pipes[0], $postData);
            fclose($pipes[0]);

            $output = stream_get_contents($pipes[1]);
            $errors = stream_get_contents($pipes[2]);

            fclose($pipes[1]);
            fclose($pipes[2]);
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

ob_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $calculator = new PythonCalculator();
    
    $a = $_POST['a'] ?? null;
    $b = $_POST['b'] ?? null;
    $c = $_POST['c'] ?? null;

    if ($calculator->validate($a, $b, $c)) {
        $result = $calculator->calculate($a, $b, $c);
        
        if ($result !== false) {
            echo $result;
            ob_end_flush();
            exit;
        }
    }
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
                background-color: beige;
                margin: 0;
                padding: 20px;
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .error-container {
                background-color: #FFC0CB; /* Baby pink */
                padding: 2rem;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                max-width: 600px;
                width: 100%;
                text-align: center;
            }
            .error-message {
                color: #dc3545;
                font-size: 1.2rem;
                margin-bottom: 1rem;
            }
            .back-button {
                display: inline-block;
                padding: 0.5rem 1rem;
                background-color: brown;
                color: white;
                text-decoration: none;
                border-radius: 5px;
                font-size: 1rem;
                transition: background-color 0.3s ease;
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
} else {
    header("Location: form.php");
    exit;
}
