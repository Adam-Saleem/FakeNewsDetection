import pandas as pd
import numpy as np
import nltk
from nltk.tokenize import sent_tokenize
import re

from nltk.corpus import stopwords
stop_words = stopwords.words('english')

def remove_stopwords(sen):
    sen_new = " ".join([i for i in sen if i not in stop_words])
    return sen_new

def textToVector(value, word_embeddings):

    value = str(value)
    # Modify the value as needed
    sentences = sent_tokenize(value)

    # remove punctuations, numbers and special characters
    clean_sentences = pd.Series(sentences).str.replace("[^a-zA-Z]", " ")

    # make alphabets lowercase
    clean_sentences = [s.lower() for s in clean_sentences]

    # remove stopwords from the sentences
    clean_sentences = [remove_stopwords(r.split()) for r in clean_sentences]

    vector = np.zeros((100,))
    for i in clean_sentences:
        if len(i) != 0:
            vector += sum([word_embeddings.get(w, np.zeros((100,))) for w in i.split()])/(len(i.split())+0.001)

    return vector

