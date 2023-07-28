#!/usr/bin/env python3

import sys
import json
import logging
from test import testen

logging.basicConfig(stream=sys.stderr, level=logging.DEBUG)
logging.debug(f"""CLI Arguments: {sys.argv[1:]}""")
x = testen(str(sys.argv[1]))

id = "JUST_A_TEST_ID"
text = x
prompt_tokens = 123
completion_tokens = 234
total_tokens = 357
response = {
  "id": id,
  "object": "text_completion",
  "created": 1690278796,
  "model": "custom",
  "choices": [
    {
      "text": text,
      "index": 0,
      "logprobs": None,
      "finish_reason": "stop"
    }
  ],
  "usage": {
    "prompt_tokens": prompt_tokens,
    "completion_tokens": completion_tokens,
    "total_tokens": total_tokens
  }
}

#print(x)
print(json.dumps(response))
exit(0)