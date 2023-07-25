from newspaper import Article
import os
import SavingFile
import dynamicBayesian_Vectors
import normalization
import summeryOneText
import sys

url = sys.argv[1]
art = Article(url)
art.download()
art.parse()

title = art.title
authors = art.authors
source = art.source_url
text = art.text

script_dir = os.path.dirname(os.path.abspath(__file__))

title = title.lower()
authors = [author.lower() for author in authors]
source = source.lower()
text = text.lower()

summary_text = summeryOneText.summary(text)

title = normalization.normalize(title)
text = normalization.normalize(summary_text)

testFileName = SavingFile.save_to_csv(title, authors, source, text)
result = dynamicBayesian_Vectors.bayesin(testFileName)
os.remove(os.path.join(script_dir, testFileName))
print(result)
