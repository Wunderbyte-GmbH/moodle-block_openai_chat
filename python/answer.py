import json
from openai import OpenAI
import csv
import os
from dotenv import load_dotenv

load_dotenv()

company_name = "Dreamboats.ai"

def calculate_similarity(vec1, vec2):
    # Calculates the cosine similarity between two vectors.
    dot_product = sum([vec1[i] * vec2[i] for i in range(len(vec1))])
    magnitude1 = sum([vec1[i] ** 2 for i in range(len(vec1))]) ** 0.5
    magnitude2 = sum([vec2[i] ** 2 for i in range(len(vec2))]) ** 0.5
    return dot_product / (magnitude1 * magnitude2)


def chat(jsonobject):

    pathtoembeddings = jsonobject["pathtoembeddings"]
    apikey = jsonobject["apikey"]
    query = jsonobject["message"]
    prompt = jsonobject["prompt"]
    historystring = jsonobject["historystring"]
    assistentname = jsonobject["assistentname"]
    sourceoftruthenforcement = jsonobject['sourceoftruthenforcement']
    sourceoftruth = jsonobject['sourceoftruth']
    temperature = jsonobject['temperature']
    maxtokens = jsonobject['maxtokens']

    messages = jsonobject['messages']

    start_chat = True
    client = OpenAI(api_key=apikey)
    while True:
        question = f"""{query} """

        response = client.embeddings.create(
            model="text-embedding-ada-002",
            input=[question]
        )

        response=json.loads(response.json())

        try:
            question_embedding = response['data'][0]['embedding']
        except Exception as e:
            return (e.args)

        # Store the similarity scores as the code loops through the CSV
        similarity_array = []

        # Loop through the CSV and calculate the cosine-similarity between
        # the question vector and each text embedding
        with open(pathtoembeddings) as f:
            reader = csv.DictReader(f)
            for row in reader:
                # Extract the embedding from the column and parse it back into a list
                text_embedding = json.loads(row['embedding'])

                # Add the similarity score to the array
                similarity_array.append(calculate_similarity(question_embedding, text_embedding))

        # Return the index of the highest similarity score
        index_of_max1 = similarity_array.index(max(similarity_array))
        del similarity_array[index_of_max1]
        index_of_max2 = similarity_array.index(max(similarity_array))

        # Used to store the original text
        original_text = ""
        alltext = ""
        # Loop through the CSV and find the text which matches the highest
        # similarity score
        with open(pathtoembeddings) as f:
            reader = csv.DictReader(f)
            for rowno, row in enumerate(reader):
                #alltext += row['text']
                if rowno == index_of_max1:
                    original_text += row['text']
                if rowno == index_of_max2:
                    original_text += " " + row['text']

        system_prompt = f"""
        {sourceoftruthenforcement}
        {sourceoftruth}
        {original_text}
        """

        # We always transmit the whole history.
        # Here we append the latest query of the user.
        messages.append(
            {
                 "role": "user",
                 "content": query
            }
        )

        # We always prepend the system message

        messages.insert(0, {
                "role": "system",
                "content": system_prompt
            }
        )

        try:
            print("gpt-4")
            response = client.chat.completions.create(
                model="gpt-4-1106-preview",
                messages= messages,
                temperature= temperature,
                max_tokens= maxtokens,
            )
        except Exception as e:
            print("gpt-3.5-turbo-1106")
            response = client.chat.completions.create(
                model="gpt-3.5-turbo-16k",
                messages= messages,
                temperature= temperature,
                max_tokens= maxtokens,
            )

        response = response.json()
        response = json.loads(response)

        try:
            answer = response["choices"][0]["message"]["content"]

        except Exception as e:
            response = {
                "id": 'error',
                "object": "text_completion",
                "created": 1690278796,
                "model": "custom",
                "choices": [
                    {
                    "text": e.args,
                    "index": 0,
                    "logprobs": None,
                    "finish_reason": "stop"
                    }
                ],
                "usage": {
                    "prompt_tokens": 1,
                    "completion_tokens": 1,
                    "total_tokens": 1
                }
            }

        response['inputmessages'] = messages
        # response.similarity_array = json.dumps(similarity_array)
        # response.text_embedding = json.dumps(text_embedding)
        response['index_of_max1'] = index_of_max1
        response['index_of_max2'] = index_of_max2
        response['historystring'] = historystring
        #response.alltext = alltext
        return json.dumps(response)
