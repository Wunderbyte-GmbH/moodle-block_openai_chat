# test.py

import os
import logging

import constants
from langchain.document_loaders import TextLoader
from langchain.indexes import VectorstoreIndexCreator

os.environ["OPENAI_API_KEY"] = constants.APIKEY

def testen(query):
    try:
        loader = TextLoader('data.txt')
        index = VectorstoreIndexCreator().from_loaders([loader])

        return index.query(query)

    except Exception as e:
        return "An error occurred:", e 