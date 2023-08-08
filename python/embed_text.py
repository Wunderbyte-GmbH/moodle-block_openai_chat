import openai
import csv
import json
from PyPDF2 import PdfReader

def create_emb(data, apikey, pathtoembeddings):

    jsonobject = json.loads(data)

    text_array = []
    openai.api_key = apikey
    embeddings_filename = pathtoembeddings

    # Loop through all .txt files in the /training-data folder
    for file in jsonobject['textfilepaths']:
        # Read the data from each file and push to the array
        # The dump method is used to convert spacings into newline characters \n
        with open(file, 'r') as f:
            text = f.read().replace('\n', '')
            text_array.append(text)

     # Loop through all .txt files in the /training-data folder
    for file in jsonobject['pdffilepaths']:
        # Read the data from each file and push to the array
        reader = PdfReader(file)
        for page in reader.pages:
            text = page.extract_text()
            text_array.append(text)


    # This array is used to store the embeddings
    embedding_array = []

    if openai.api_key is None or openai.api_key == "YOUR_OPENAI_KEY_HERE":
        print("Invalid API key")
        exit()

    # Loop through each element of the array
    for text in text_array:
        # Pass the text to the embeddings API which will return a vector and
        # store in the response variable.
        response = openai.Embedding.create(
            input=text,
            model="text-embedding-ada-002"
        )

        # Extract the embedding from the response object
        embedding = response['data'][0]["embedding"]

        # Create a Python dictionary containing the vector and the original text
        embedding_dict = {'embedding': embedding, 'text': text}
        # Store the dictionary in a list.
        embedding_array.append(embedding_dict)

    with open(embeddings_filename, 'w', newline='') as f:
        # This sets the headers
        fieldnames = ['embedding', 'text']
        writer = csv.DictWriter(f, fieldnames=fieldnames)
        writer.writeheader()

        for obj in embedding_array:
            # The embedding vector will be stored as a string to avoid comma
            # separated issues between the values in the CSV
            writer.writerow({'embedding': str(obj['embedding']), 'text': obj['text']})

    return "Embeddings saved"