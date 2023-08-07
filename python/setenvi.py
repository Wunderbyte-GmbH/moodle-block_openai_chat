import sys
import os

if len(sys.argv) < 2:
    print("Error: API key not provided.")
    sys.exit(1)

os.environ["OPENAI_API_KEY"] = sys.argv[1]

print(os.environ["OPENAI_API_KEY"])