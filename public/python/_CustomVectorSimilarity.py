import numpy as np

def myCosine_similarity(vector1, vector2):
    if np.all(vector1 == 0) or np.all(vector2 == 0):
        # Handle zero vectors
        return 0.0

    # Normalize vectors
    norm_vector1 = np.linalg.norm(vector1)
    norm_vector2 = np.linalg.norm(vector2)
    if norm_vector1 == 0.0 or norm_vector2 == 0.0:
        # Handle edge case where one of the vectors is a zero vector
        return 0.0
    vector1 = vector1 / norm_vector1
    vector2 = vector2 / norm_vector2

    dot_product = np.dot(vector1, vector2)
    similarity = dot_product
    return similarity