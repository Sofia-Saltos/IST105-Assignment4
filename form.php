<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORM PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #fff;
        }
        
        .form-container {
            background-color: #ffd1dc; 
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        
        input[type="number"] {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        button {
            background-color: #8b4513; 
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
        }
        
        button:hover {
            background-color: #693410;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form action="calculate.php" method="POST">
            <div class="form-group">
                <label for="a">Enter value for a:</label>
                <input type="number" id="a" name="a" required step="any">
            </div>
            
            <div class="form-group">
                <label for="b">Enter value for b:</label>
                <input type="number" id="b" name="b" required step="any">
            </div>
            
            <div class="form-group">
                <label for="c">Enter value for c:</label>
                <input type="number" id="c" name="c" required step="any">
            </div>
            
            <button type="submit">Calculate</button>
        </form>
    </div>
</body>
</html>