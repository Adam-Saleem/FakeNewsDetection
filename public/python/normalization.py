import string
from nltk.corpus import stopwords
from nltk.tokenize import sent_tokenize
from nltk.stem import WordNetLemmatizer


def remove_non_ascii(str):
    ascii_chars = set(string.printable)
    return ''.join(filter(lambda x: x in ascii_chars, str))

def remove_stopwords(sentence):
    stop_words = set(stopwords.words('english'))
    return [word for word in sentence if word.lower() not in stop_words]


def get_lemma(word):
    lemmatizer = WordNetLemmatizer()
    return lemmatizer.lemmatize(word)


def normalize(text):
    tokens = sent_tokenize(remove_non_ascii(text))
    text_without_sw = [word for word in tokens if not word in stopwords.words('english')]
    text_normalized = [get_lemma(word) for word in text_without_sw]
    newText = ''
    for i in text_normalized:
        newText = newText + " " + i
    return newText
