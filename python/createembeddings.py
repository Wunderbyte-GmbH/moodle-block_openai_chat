import sys
import json
from embed_text import create_emb
import os
import glob

data = f"{sys.argv[1]}"
jsonobject = json.loads(data)

dir_path = os.path.join(os.getcwd(), 'documents')
dir_full_path = os.path.join(dir_path, '*.txt')

answer = create_emb(data, jsonobject["apikey"], jsonobject['pathtoembeddings'])

print(answer)