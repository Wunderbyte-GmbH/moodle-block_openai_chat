# chatgpt.py
import os
import sys

import constants
from langchain.document_loaders import TextLoader
from langchain.indexes import VectorstoreIndexCreator

os.environ["OPENAI_API_KEY"] = constants.APIKEY


def chatgpt(query):
    try:
        loader = TextLoader('data.txt')
        index = VectorstoreIndexCreator().from_loaders([loader])

        return index.query(query)

    except Exception as e:
        return "An error occurred:", e
