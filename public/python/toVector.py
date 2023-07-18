import pandas as pd
import numpy as np
from nltk.tokenize import sent_tokenize
from nltk.corpus import stopwords

def remove_stopwords(sentence):
    stop_words = set(stopwords.words('english'))
    return [word for word in sentence if word.lower() not in stop_words]

def textToVector(value, word_embeddings, char_embeddings):
    value = str(value)
    # Modify the value as needed
    sentences = sent_tokenize(value)

    # remove punctuations, numbers and special characters
    clean_sentences = pd.Series(sentences).str.replace(
        "[^a-zA-Z]", " ", regex=True)

    # make alphabets lowercase
    clean_sentences = [s.lower() for s in clean_sentences]

    # remove stopwords from the sentences
    clean_sentences = [remove_stopwords(r.split()) for r in clean_sentences]

    vector = np.zeros((300,))
    for i in clean_sentences:
        if len(i) != 0:
            word_sum = np.zeros((300,))
            word_count = 0
            for w in i:
                if w in word_embeddings:
                    word_sum += word_embeddings[w]
                    word_count += 1
                else:
                    char_sum = np.zeros((300,))
                    char_count = 0
                    for c in w:
                        if c in char_embeddings:
                            char_sum += char_embeddings[c]
                            char_count += 1
                    if char_count > 0:
                        word_sum += char_sum / (char_count + 0.001)
                        word_count += 1

            if word_count > 0:
                vector += word_sum / (word_count + 0.001)

    return vector
