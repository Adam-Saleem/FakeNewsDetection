from pgmpy.models import BayesianModel
from pgmpy.estimators import MaximumLikelihoodEstimator, BayesianEstimator
from pgmpy.inference import VariableElimination
import pandas as pd
import numpy as np
from sklearn.metrics.pairwise import cosine_similarity
from toVector import textToVector

import os

script_dir = os.path.dirname(os.path.abspath(__file__))

# Define the structure of the Bayesian network
model = BayesianModel(
    [('authors', 'source'), ('authors', 'class'), ('source', 'class'), ('title', 'class'), ('summary', 'class')])

# Load the training data
training_file_path = os.path.join(script_dir, 'training.csv')
data = pd.read_csv(training_file_path)

word_embeddings = {}
glove_file_path = os.path.join(script_dir, 'glove6B100d.txt')
f = open(glove_file_path, encoding='utf-8')
for line in f:
    values = line.split()
    word = values[0]
    coefs = np.asarray(values[1:], dtype='float32')
    word_embeddings[word] = coefs
f.close()

# Convert string features (author, title, source, summary) into vectors
# This step assumes you have already converted the string features into vectors in the training_data.csv file
# Replace the column names and vector conversion code with your specific implementation
# Extract word vectors
data['authors'] = data['authors'].apply(textToVector, word_embeddings=word_embeddings)
data['title'] = data['title'].apply(textToVector, word_embeddings=word_embeddings)
data['source'] = data['source'].apply(textToVector, word_embeddings=word_embeddings)
data['summary'] = data['summary'].apply(textToVector, word_embeddings=word_embeddings)

# Define a similarity threshold for evidence
similarity_threshold = 0.7

# /////////////////testdata
testData_file_path = os.path.join(script_dir, 'testData.csv')
data2 = pd.read_csv(testData_file_path)

# # Convert the evidence vectors
evidence_author = data2['authors'].apply(textToVector, word_embeddings=word_embeddings)
evidence_title = data2['title'].apply(textToVector, word_embeddings=word_embeddings)
evidence_source = data2['source'].apply(textToVector, word_embeddings=word_embeddings)
evidence_summary = data2['summary'].apply(textToVector, word_embeddings=word_embeddings)
print(evidence_title)
print(evidence_author)
print(evidence_source)
print(evidence_summary)
evidence_author = evidence_author[0]
evidence_source = evidence_source[0]
evidence_title = evidence_title[0]
evidence_summary = evidence_summary[0]


# def myCosine_similarity(vector1, vector2):
#     dot_product = np.dot(vector1, vector2)
#     norm_vector1 = np.linalg.norm(vector1)
#     norm_vector2 = np.linalg.norm(vector2)
#     similarity = dot_product / (norm_vector1 * norm_vector2)
#     return similarity
def myCosine_similarity(vector1, vector2):
    if np.all(vector1 == 0) or np.all(vector2 == 0):
        # Handle zero vectors
        return 0.0

    # Normalize vectors
    norm_vector1 = np.linalg.norm(vector1)
    norm_vector2 = np.linalg.norm(vector2)
    if norm_vector1 == 0.0 or norm_vector2 == 0.0:
        # Handle edge case where one of the vectors is a zero vector
        return 0.0
    vector1 = vector1 / norm_vector1
    vector2 = vector2 / norm_vector2

    dot_product = np.dot(vector1, vector2)
    similarity = dot_product
    return similarity


data['authors'] = data.apply(
    lambda row: 'A' if myCosine_similarity(row['authors'], evidence_author) >= similarity_threshold else 'B',
    axis=1
)
data['source'] = data.apply(
    lambda row: 'A' if myCosine_similarity(row['source'], evidence_source) >= similarity_threshold else 'B',
    axis=1
)
data['title'] = data.apply(
    lambda row: 'A' if myCosine_similarity(row['title'], evidence_title) >= similarity_threshold else 'B',
    axis=1
)
data['summary'] = data.apply(
    lambda row: 'A' if myCosine_similarity(row['summary'], evidence_summary) >= similarity_threshold else 'B',
    axis=1
)

# Determine the unique categories or levels in the 'authors' and 'source' columns
unique_authors = data['authors'].unique()
unique_source = data['source'].unique()
unique_title = data['title'].unique()
unique_summary = data['summary'].unique()

# Set the evidence values to match the unique categories or levels
evidence_author = unique_authors[0]  # Choose the first category or level
evidence_source = unique_source[0]  # Choose the first category or level
evidence_title = unique_title[0]  # Choose the first category or level
evidence_summary = unique_summary[0]  # Choose the first category or level

# Use the EM algorithm to refine the parameters of the model
num_categories_author = len(data['authors'].unique())
num_categories_title = len(data['title'].unique())
num_categories_source = len(data['source'].unique())
num_categories_summary = len(data['summary'].unique())

be = BayesianEstimator(model, data)
cpd_author = be.estimate_cpd('authors', prior_type='dirichlet', pseudo_counts=np.ones((num_categories_author, 1)))
cpd_source = be.estimate_cpd('source', prior_type='dirichlet', pseudo_counts=np.ones((num_categories_source, 2)))
cpd_title = be.estimate_cpd('title', prior_type='dirichlet', pseudo_counts=np.ones((num_categories_title, 1)))
cpd_summary = be.estimate_cpd('summary', prior_type='dirichlet', pseudo_counts=np.ones((num_categories_summary, 1)))
cpd_class = be.estimate_cpd('class', prior_type='dirichlet', pseudo_counts=np.ones((2, 8)))

print(cpd_author)
print(cpd_title)
print(cpd_source)
print(cpd_summary)
print(cpd_class)

model.add_cpds(cpd_author, cpd_title, cpd_source, cpd_summary, cpd_class)

# Create an inference engine to perform inference on the network
inference = VariableElimination(model)

# Make predictions for new data using the learned model and filtered evidence
query = inference.query(['class'],
                        evidence={'authors': evidence_author, 'title': evidence_title, 'source': evidence_source,
                                  'summary': evidence_summary}, joint=False)

print(query['class'])
