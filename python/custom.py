#!/usr/bin/env python3

import sys
import json
import logging
import chatgpt

logging.basicConfig(stream=sys.stderr, level=logging.DEBUG)
logging.debug(f"""CLI Arguments: {sys.argv[1:]}""")

id = "JUST_A_TEST_ID"
text = chatgpt.chatgpt(sys.argv[1:])
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
#query = "Wie lang kann eine Studienunterbrechung andauern" für chatgpt
# result = chatgpt.chatgpt(query) für chatgpt
# print(result) für chatgpt
# Everything that is printed is returned as output to the calling PHP function für chatgpt
print(response)
exit(0)