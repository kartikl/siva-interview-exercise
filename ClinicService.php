<?php

class Patient
{
    public $id;
    public $name;
    public $latitude;
    public $longitude;
    public $age;
    public $acceptedOffers;
    public $canceledOffers;
    public $averageReplyTime;
    public $distanceToClinic;
    public $score;

    public function __construct($id, $name, $latitude, $longitude, $age, $acceptedOffers, $canceledOffers, $averageReplyTime)
    {
        $this->id = $id;
        $this->name = $name;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->age = $age;
        $this->acceptedOffers = $acceptedOffers;
        $this->canceledOffers = $canceledOffers;
        $this->averageReplyTime = $averageReplyTime;
    }

    public function calculateDistanceToClinic($hospitalLatitude, $hospitalLongitude)
    {
        $this->distanceToClinic = sqrt(pow(($this->longitude - $hospitalLongitude), 2) + pow(($this->latitude - $hospitalLatitude), 2));
    }

    public function calculateScore()
    {
        $this->score = ($this->age * 0.1)
            - ($this->distanceToClinic * 0.1)
            + ($this->acceptedOffers * 0.3)
            - ($this->canceledOffers * 0.3)
            - ($this->averageReplyTime * 0.2);
    }
}

class Hospital
{
    public $latitude;
    public $longitude;

    public function __construct($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }
}

class ClinicService
{
    private $patients = [];
    private $hospital;

    public function __construct($hospitalLatitude, $hospitalLongitude)
    {
        $this->hospital = new Hospital($hospitalLatitude, $hospitalLongitude);
    }

    public function loadPatients($jsonFilePath)
    {
        $jsonData = file_get_contents($jsonFilePath);
        $patientsData = json_decode($jsonData, true);

        foreach ($patientsData as $data) {
            $patient = new Patient(
                $data['id'],
                $data['name'],
                $data['location']['latitude'],
                $data['location']['longitude'],
                $data['age'],
                $data['acceptedOffers'],
                $data['canceledOffers'],
                $data['averageReplyTime']
            );
            $patient->calculateDistanceToClinic($this->hospital->latitude, $this->hospital->longitude);
            $patient->calculateScore();
            $this->patients[] = $patient;
        }
    }

    public function normalizeScores()
    {
        $maxScore = max(array_map(function ($patient) {
            return $patient->score;
        }, $this->patients));
        $minScore = min(array_map(function ($patient) {
            return $patient->score;
        }, $this->patients));

        foreach ($this->patients as $patient) {
            $patient->score = (($patient->score - $minScore) / ($maxScore - $minScore)) * 9 + 1;
        }
    }

    public function getTopNPatients($N)
    {
        usort($this->patients, function ($a, $b) {
            return $b->score <=> $a->score;
        });

        return array_slice($this->patients, 0, $N);
    }
}
