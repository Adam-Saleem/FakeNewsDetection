import csv
import os
import dynamicBayesian_Vectors
import summeryOneText
import normalization

# title = sys.argv[1]
# authors = sys.argv[2]
# source = sys.argv[3]
# textFile = sys.argv[4]

title = 'Inter Miami unveil Lionel Messi, sign Sergio Busquets'
authors = 'Al Jazeera'
source = 'NEWS AGENCIES'
# textFile = sys.argv[4]

script_dir = os.path.dirname(os.path.abspath(__file__))
text_file_path = os.path.join(script_dir, '../temp/text_64b67ea30057a.txt')

with open(text_file_path, 'r', encoding="utf-8") as f:
    text = f.read()

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
