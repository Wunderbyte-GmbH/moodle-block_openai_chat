# test.py

# Overwrite the default sqlite3 libraries with the ones from the pysqlite3
# package
__import__('pysqlite3')
import sys
sys.modules['sqlite3'] = sys.modules.pop('pysqlite3')

import os
import logging

import constants
from langchain.document_loaders import TextLoader
from langchain.indexes import VectorstoreIndexCreator


os.environ["OPENAI_API_KEY"] = constants.APIKEY

def testen(query):
    try:
        # The current working directory seems to be set to openai_chat/api/
        loader = TextLoader(f"""{os.getcwd()}/../python/data.txt""")
        index = VectorstoreIndexCreator().from_loaders([loader])

        return index.query(query)

    except Exception as e:
        return "An error occurred:", e 