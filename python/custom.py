#!/usr/bin/env python3

import sys
import json
import logging
from answer import chat

logging.basicConfig(stream=sys.stderr, level=logging.DEBUG)
logging.debug(f"sys.argv[1:]")

data = f"{sys.argv[1]}"
jsonobject = json.loads(data)

# Pass on the message.

message = jsonobject["sourceoftruth"] + jsonobject["prompt"] + jsonobject["historystring"] + jsonobject["message"] +" \n" + jsonobject["assistentname"]

x = chat(message, jsonobject["apikey"], jsonobject["pathtoembeddings"], jsonobject["sourceoftruth"])

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