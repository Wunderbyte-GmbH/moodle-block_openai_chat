#!/usr/bin/env python3

import sys
import json
import logging
from answer import chat

logging.basicConfig(stream=sys.stderr, level=logging.ERROR)
logging.debug(f"sys.argv[1:]")

data = f"{sys.argv[1]}"
jsonobject = json.loads(data)

# Pass on the message.

response = chat(jsonobject)
print(response)

exit(0)