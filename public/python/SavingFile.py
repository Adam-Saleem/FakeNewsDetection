import csv
import os
import csv
import uuid

script_dir = os.path.dirname(os.path.abspath(__file__))

def save_to_csv(title, authors, source, summary):
    data = [[title, authors, source, summary]]
    filename = create_csv_with_unique_id()
    csv_file = os.path.join(script_dir, filename)
    with open(csv_file, 'w'):
        pass
    with open(csv_file, 'w', newline='', encoding='utf-8') as file:
        writer = csv.writer(file)
        writer.writerow(['title', 'authors', 'source', 'summary'])  # Write header row
        writer.writerows(data)  # Write the data rows
    return filename

def create_csv_with_unique_id():
    # Generate a unique ID
    unique_id = str(uuid.uuid4())

    # Define the filename for the CSV file
    filename = f'testFile_{unique_id}.csv'

    return filename



