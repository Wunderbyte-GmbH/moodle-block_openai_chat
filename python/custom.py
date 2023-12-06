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


response = chat(jsonobject)

#print(x)
response_dict = response.choices[0].message.content
print(json.dumps(response_dict))
#print(json.dumps(response))
exit(0)
