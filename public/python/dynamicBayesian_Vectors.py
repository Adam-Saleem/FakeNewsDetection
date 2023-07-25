from pgmpy.models import BayesianNetwork
from pgmpy.estimators import BayesianEstimator
from pgmpy.inference import VariableElimination
import pandas as pd
import numpy as np
from toVector import textToVector
from _CustomVectorSimilarity import myCosine_similarity
import json
import os

script_dir = os.path.dirname(os.path.abspath(__file__))


def bayesin(testFileName):
    model = BayesianNetwork([('authors', 'source'), ('authors', 'class'),
                             ('source', 'class'), ('title', 'class'), ('summary', 'class')])
    # Load the training data
    training_file_path = os.path.join(script_dir, 'training.csv')
    data = pd.read_csv(training_file_path)

    english_word_embeddings_path = os.path.join(script_dir, 'english_word_embeddings.npy')
    english_char_embeddings_path = os.path.join(script_dir, 'english_char_embeddings.npy')

    word_embeddings = np.load(english_word_embeddings_path, allow_pickle=True).item()
    char_embeddings = np.load(english_char_embeddings_path, allow_pickle=True).item()

    # make all authors into vectors
    def convert_authors_to_vector(authors, word_embeddings, char_embeddings):
        if isinstance(authors, str):
            authors = authors.split(',')  # Split authors by comma
            author_vectors = []
            for author in authors:
                if (author == ""):
                    continue
                author_vector = textToVector(
                    author.strip(), word_embeddings=word_embeddings, char_embeddings=char_embeddings)
                author_vectors.append(author_vector)
            return author_vectors
        return []

    # Convert string features (author, title, source, summary) into vectors
    # This step assumes you have already converted the string features into vectors in the training_data.csv file
    # Replace the column names and vector conversion code with your specific implementation
    # Extract word vectors
    data['authors'] = data['authors'].apply(
        lambda authors: convert_authors_to_vector(
            authors, word_embeddings=word_embeddings, char_embeddings=char_embeddings)
    )
    data['title'] = data['title'].apply(
        lambda title: textToVector(
            title, word_embeddings=word_embeddings, char_embeddings=char_embeddings)
    )
    data['source'] = data['source'].apply(
        lambda source: textToVector(
            source, word_embeddings=word_embeddings, char_embeddings=char_embeddings)
    )
    data['summary'] = data['summary'].apply(
        lambda summary: textToVector(
            summary, word_embeddings=word_embeddings, char_embeddings=char_embeddings)
    )

    # Define a similarity threshold for evidence
    similarity_threshold = 0.7

    testData_file_path = os.path.join(script_dir, testFileName)
    data2 = pd.read_csv(testData_file_path)

    # # Convert the evidence vectors
    evidence_author = data2['authors'].apply(
        lambda authors: convert_authors_to_vector(
            authors, word_embeddings=word_embeddings, char_embeddings=char_embeddings)
    ).values[0]
    evidence_title = data2['title'].apply(
        lambda title: textToVector(
            title, word_embeddings=word_embeddings, char_embeddings=char_embeddings)
    ).values[0]
    evidence_source = data2['source'].apply(
        lambda source: textToVector(
            source, word_embeddings=word_embeddings, char_embeddings=char_embeddings)
    ).values[0]
    evidence_summary = data2['summary'].apply(
        lambda summary: textToVector(
            summary, word_embeddings=word_embeddings, char_embeddings=char_embeddings)
    ).values[0]

    if evidence_author:
        evidence_author = evidence_author[0]
    else:
        evidence_author

    data['authors'] = data.apply(
        lambda row: 'A' if any(myCosine_similarity(
            author, evidence_author) >= similarity_threshold for author in row['authors']) else 'B',
        axis=1
    )
    data['source'] = data.apply(
        lambda row: 'A' if myCosine_similarity(
            row['source'], evidence_source) >= similarity_threshold else 'B',
        axis=1
    )
    data['title'] = data.apply(
        lambda row: 'A' if myCosine_similarity(
            row['title'], evidence_title) >= similarity_threshold else 'B',
        axis=1
    )
    data['summary'] = data.apply(
        lambda row: 'A' if myCosine_similarity(
            row['summary'], evidence_summary) >= similarity_threshold else 'B',
        axis=1
    )

    # Determine the unique categories or levels in the 'authors' and 'source' columns
    unique_authors = data['authors'].unique()
    unique_source = data['source'].unique()
    unique_title = data['title'].unique()
    unique_summary = data['summary'].unique()

    # Set the evidence values to match the unique categories or levels
    evidence_author = 'A' if 'A' in unique_authors else 'B'
    evidence_source = 'A' if 'A' in unique_source else 'B'
    evidence_title = 'A' if 'A' in unique_title else 'B'
    evidence_summary = 'A' if 'A' in unique_summary else 'B'

    # Use the EM algorithm to refine the parameters of the model
    num_categories_author = len(data['authors'].unique())
    num_categories_title = len(data['title'].unique())
    num_categories_source = len(data['source'].unique())
    num_categories_summary = len(data['summary'].unique())

    num_columns = 0
    if num_categories_author > 1:
        num_columns += 1
    if num_categories_title > 1:
        num_columns += 1
    if num_categories_source > 1:
        num_columns += 1
    if num_categories_summary > 1:
        num_columns += 1

    num_categories_class = 2 ** num_columns

    be = BayesianEstimator(model, data)
    cpd_author = be.estimate_cpd(
        'authors', prior_type='dirichlet', pseudo_counts=np.ones((num_categories_author, 1)))
    cpd_source = be.estimate_cpd(
        'source', prior_type='dirichlet', pseudo_counts=np.ones((num_categories_source, num_categories_author)))
    cpd_title = be.estimate_cpd(
        'title', prior_type='dirichlet', pseudo_counts=np.ones((num_categories_title, 1)))
    cpd_summary = be.estimate_cpd(
        'summary', prior_type='dirichlet', pseudo_counts=np.ones((num_categories_summary, 1)))
    cpd_class = be.estimate_cpd(
        'class', prior_type='dirichlet', pseudo_counts=np.ones((2, num_categories_class)))

    model.add_cpds(cpd_author, cpd_title, cpd_source, cpd_summary, cpd_class)

    # Create an inference engine to perform inference on the network
    inference = VariableElimination(model)

    # Make predictions for new data using the learned model and filtered evidence
    query = inference.query(['class'], evidence={
        'authors': evidence_author, 'title': evidence_title,
        'source': evidence_source, 'summary': evidence_summary}, joint=False)
    result_array = query['class'].values

    result_list = result_array.tolist()

    result_json = json.dumps(result_list)
    return result_json


# bayesin('testFile_3157d695-8456-425b-80b9-2918f4c29da4.csv')
