import string

import nltk
import spacy
from nltk.corpus import stopwords
from nltk.tokenize import word_tokenize
from nltk.stem import WordNetLemmatizer
import os

script_dir = os.path.dirname(os.path.abspath(__file__))

text_file_path = os.path.join(script_dir, 'textNeedNormalize.txt')

with open(text_file_path, 'r') as f:
    text = f.read()

nlp = spacy.load('en_core_web_sm')
lemmatizer = WordNetLemmatizer()

text = text.lower()


def remove_non_ascii(a_str):
    ascii_chars = set(string.printable)
    return ''.join(filter(lambda x: x in ascii_chars, a_str))


text = remove_non_ascii(text)
tokens = word_tokenize(text)

text_without_sw = [word for word in tokens if not word in stopwords.words('english')]


def get_lemma(word):
    return lemmatizer.lemmatize(word)


text = [get_lemma(word) for word in text_without_sw]

newText = ''
for i in text:
    newText = newText + " " + i

print(newText)
