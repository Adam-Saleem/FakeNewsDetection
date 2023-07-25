import os
import sys
import SavingFile
import dynamicBayesian_Vectors
import summeryOneText
import normalization

title = sys.argv[1]
authors = sys.argv[2]
source = sys.argv[3]
textFile = sys.argv[4]
print(textFile)
script_dir = os.path.dirname(os.path.abspath(__file__))
text_file_path = os.path.join(script_dir, '..\\temp\\'+textFile)
print(text_file_path)
with open(text_file_path, 'r', encoding="utf-8") as f:
    text = f.read()

title = title.lower()
authors = authors.lower()
source = source.lower()
text = text.lower()

summary_text = summeryOneText.summary(text)

title = normalization.normalize(title)
text = normalization.normalize(summary_text)

testFileName = SavingFile.save_to_csv(title, authors, source, text)

result = dynamicBayesian_Vectors.bayesin(testFileName)
print(result)
