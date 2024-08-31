# prasad-interview-exercise

GOAL: Return the top `N`  patients based on their scores which is proximity to the hospital and other factors defined below

1. Load the patients.json
2. Function to return a list of scored and sorted patients
3. Input: Hospital Latitude and Longitude 
      "latitude": "64.4811",
      "longitude": "33.2338"
4. Scoring Rules 
      - Latitude and Longitude of patient. Use this to calculate the distance from the hospital. Store this in the patient object 
      - Distance to Clinic formula: distanceToClinic = 
      ``` 
       sqrt(
                (
                    (patient_Longitude-hospital_Longitude)**2
                ) 
                +
                (
                    (patient_Latitude-hospitalLatitude)**2
                )
            )
      ```
      - normalizeScores = Generate a score for each patient to be normalized 1-10 based on the likelihood to come to the clinic 
        -  Score formula = 
          - score = patient['age']*.1 
          - score -= patient['distanceToClinic']*.1 
          - score += patient['acceptedOffers']*.3 
          - score -= patient['canceledOffers']*.3 
          - score -= patient['replyTime']*.2 
      - Find high score and low score 
      - Normalized Score =  (((patient_score - low_score)/(high_score-low_score)) * 9) +1 
5. Return the top 10 normalized scores' patients
6. Write appropriate tests
7. Write a REST API which will take in a value of `N` and return the top `N` scores.

