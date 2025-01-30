#!/usr/bin/env python3
# calculate.py

import sys
import math
from typing import Union, Tuple

def validate_inputs(a: str, b: str, c: str) -> Tuple[bool, Union[str, Tuple[float, float, float]]]:
    """Validate input values and convert them to float if valid."""
    try:
        a_val = float(a)
        b_val = float(b)
        c_val = float(c)
        return True, (a_val, b_val, c_val)
    except ValueError:
        return False, "Error: All inputs must be numeric values."

def perform_calculations(a: float, b: float, c: float) -> Tuple[str, float]:
    """Perform the required calculations and return messages and result."""
    messages = []
    result = 0

    # Check conditions and perform calculations
    if a < 1:
        messages.append("Warning: Input 'a' is too small (less than 1)")
    
    if b == 0:
        messages.append("Note: 'b' is zero and will not affect the result")
    
    if c < 0:
        return "Error: 'c' cannot be negative", 0
    
    # Calculate c^3
    c_cubed = c ** 3
    
    # Calculate based on c^3 value
    if c_cubed > 1000:
        result = math.sqrt(c_cubed) * 10
    else:
        result = math.sqrt(c_cubed) / a
    
    # Add b to final result
    result += b
    
    message = "<br>".join(messages) if messages else "Calculation completed successfully"
    return message, result

def generate_html_output(message: str, result: float = None) -> str:
    """Generate HTML page with calculation results."""
    html = """
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Calculation Results</title>
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
            .result-container {
                background-color: white;
                padding: 2rem;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                max-width: 600px;
                width: 100%;
            }
            .message {
                margin-bottom: 1rem;
                padding: 1rem;
                border-radius: 5px;
                background-color: #f8f9fa;
            }
            .result {
                font-size: 1.2rem;
                font-weight: bold;
                color: #333;
            }
            .error {
                color: #dc3545;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="result-container">
            <div class="message">
                {message}
            </div>
            {result_html}
        </div>
    </body>
    </html>
    """
    
    result_html = f'<div class="result">Final Result: {result:.4f}</div>' if result is not None else ''
    return html.format(message=message, result_html=result_html)

def main():
    # Check if running in CGI environment
    import os
    import cgi
    
    try:
        # Get form data
        form = cgi.FieldStorage()
        a = form.getvalue('a')
        b = form.getvalue('b')
        c = form.getvalue('c')
        
        # Validate inputs
        is_valid, result = validate_inputs(a, b, c)
        
        if not is_valid:
            print("Content-Type: text/html\n")
            print(generate_html_output(result))
            return
        
        # Perform calculations
        a_val, b_val, c_val = result
        message, final_result = perform_calculations(a_val, b_val, c_val)
        
        # Generate and output HTML
        print("Content-Type: text/html\n")
        print(generate_html_output(message, final_result))
        
    except Exception as e:
        print("Content-Type: text/html\n")
        print(generate_html_output(f"An error occurred: {str(e)}"))

if __name__ == "__main__":
    main()