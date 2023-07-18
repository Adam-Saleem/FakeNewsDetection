from newspaper import Article
import csv
import os
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

print('All To Lower')
title = title.lower()
authors = authors.lower()
source = source.lower()
text = text.lower()

print('Summarising The Text')
summary_text = summeryOneText.summary(text)

print('Normalize Title and Text')
title = normalization.normalize(title)
text = normalization.normalize(summary_text)


def save_to_csv(title, authors, source, summary):
    data = [[title, authors, source, summary]]
    csv_file = os.path.join(script_dir, 'testData.csv')
    with open(csv_file, 'w'):
        pass
    with open(csv_file, 'w', newline='', encoding='utf-8') as file:
        writer = csv.writer(file)
        writer.writerow(['title', 'authors', 'source', 'summary'])  # Write header row
        writer.writerows(data)  # Write the data rows


print('Saving All As CSV File')
save_to_csv(title, authors, source, text)

print('Bayesian Network Start')
result = dynamicBayesian_Vectors.bayesin()
print(result)
