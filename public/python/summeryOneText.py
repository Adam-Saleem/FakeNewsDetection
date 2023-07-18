import os
import pandas as pd
import numpy as np
from nltk.tokenize import sent_tokenize
from nltk.corpus import stopwords



def remove_stopwords(sentence):
    stop_words = set(stopwords.words('english'))
    return [word for word in sentence if word.lower() not in stop_words]


def summary(text):
    script_dir = os.path.dirname(os.path.abspath(__file__))
    sentences = sent_tokenize(text)

    # remove punctuations, numbers and special characters
    clean_sentences = pd.Series(sentences).str.replace("[^a-zA-Z]", " ")

    # remove stopwords from the sentences
    clean_sentences = [remove_stopwords(r.split()) for r in clean_sentences]

    # Extract word vectors
    word_embeddings = np.load(os.path.join(script_dir, 'english_word_embeddings.npy'), allow_pickle=True).item()

    sentence_vectors = []
    for sentence in clean_sentences:
        if len(sentence) != 0:
            v = sum([word_embeddings.get(word, np.zeros((300,))) for word in sentence]) / (len(sentence) + 0.001)
        else:
            v = np.zeros((300,))
        sentence_vectors.append(v)

    sim_mat = np.zeros([len(sentences), len(sentences)])

    from sklearn.metrics.pairwise import cosine_similarity
    for i in range(len(sentences)):
        for j in range(len(sentences)):
            if i != j:
                sim_mat[i][j] = cosine_similarity(sentence_vectors[i].reshape(1, 300),
                                                  sentence_vectors[j].reshape(1, 300))[0, 0]

    import networkx as nx

    nx_graph = nx.from_numpy_array(sim_mat)
    scores = nx.pagerank(nx_graph)

    ranked_sentences = sorted(((scores[i], s) for i, s in enumerate(sentences)), reverse=True)
    final_summary = ""
    for i in range(1):
        final_summary = ranked_sentences[i][1]

    return final_summary
