
import sys
import json
import math

def calculate(a, b, c):
    if not (isinstance(a, (int, float)) and isinstance(b, (int, float)) and isinstance(c, (int, float))):
        return {"error": "All inputs must be numeric."}
    if a < 1:
        return {"error": "The input 'a' is too small."}
    if b == 0:
        return {"message": "'b' is 0 and will not affect the result."}
    if c < 0:
        return {"error": "The input 'c' is negative."}
    c_cubed = c ** 3
    if c_cubed > 1000:
        result = math.sqrt(c_cubed) * 10
    else:
        result = math.sqrt(c_cubed) / a
    final_result = result + b
    return {"result": final_result}

if __name__ == "__main__":
    try:
        data = json.loads(sys.stdin.read())
        a = float(data['a'])
        b = float(data['b'])
        c = float(data['c'])
        output = calculate(a, b, c)
    except (ValueError, KeyError, json.JSONDecodeError):
        output = {"error": "Invalid input."}
    print(json.dumps(output))
