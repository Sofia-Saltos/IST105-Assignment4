<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment Form</title>
    <style>
        body {
            background-color: beige;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .form-container {
            background-color: #FFC0CB; /* Baby pink */
            width: 300px;
            padding: 20px;
            margin: 50px auto;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: brown;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #8B4513; /* Darker brown */
        }
    </style>
</head>
<body>
    <h1>Enter Numerical Values</h1>
    <div class="form-container">
        <form action="process.php" method="post">
            <label for="a">Value for a:</label>
            <input type="number" id="a" name="a" required>

            <label for="b">Value for b:</label>
            <input type="number" id="b" name="b" required>

            <label for="c">Value for c:</label>
            <input type="number" id="c" name="c" required>

            <input type="submit" value="Calculate">
        </form>
    </div>
</body>
</html>
