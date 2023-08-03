import openai
import csv
import os

def create_emb(query):  
    text_array = []
    openai.api_key = os.environ.get('apikey')
    embeddings_filename = "embeddings.csv"

    text = query
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

    print("Embeddings saved to:", embeddings_filename)