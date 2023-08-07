import json
import openai
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


def chat(query, apikey, pathtoembeddings):
    start_chat = True
    while True:
        openai.api_key = apikey
        question = query

        response = openai.Embedding.create(
            model="text-embedding-ada-002",
            input=[question]
        )

        try:
            question_embedding = response['data'][0]["embedding"]
        except Exception as e:
            return (e.message)

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
        index_of_max = similarity_array.index(max(similarity_array))

        # Used to store the original text
        original_text = ""

        # Loop through the CSV and find the text which matches the highest
        # similarity score
        with open(pathtoembeddings) as f:
            reader = csv.DictReader(f)
            for rowno, row in enumerate(reader):
                if rowno == index_of_max:
                    original_text = row['text']

        system_prompt = f"""
If the users question is not answered by the article you will respond with
'I'm sorry I don't know.' Dont try to make something up
"""

        question_prompt = f"""
        [Article]
        {original_text}

        [Question]
        {question}
        """
        try:
            response = openai.ChatCompletion.create(
                model="gpt-4",
                messages=[
                    {
                        "role": "system",
                        "content": system_prompt
                    },
                    {
                        "role": "user",
                        "content": question_prompt
                    }
                ],
                temperature=0.2,
                max_tokens=2000,
            )
        except Exception as e:
            response = openai.ChatCompletion.create(
                model="gpt-3.5-turbo-16k",
                messages=[
                    {
                        "role": "system",
                        "content": system_prompt
                    },
                    {
                        "role": "user",
                        "content": question_prompt
                    }
                ],
                temperature=0.2,
                max_tokens=2000,
            )

        try:
            answer = response['choices'][0]['message']['content']
        except Exception as e:
            return (e.message)

        return("\n\033[32mSupport:\033[0m\n\033[32m{}\033[0m".format(answer.lstrip()))

