<?php

require 'ClinicService.php'; // Assume all classes are in this file

$hospitalLatitude = "64.4811";
$hospitalLongitude = "33.2338";
$clinicService = new ClinicService($hospitalLatitude, $hospitalLongitude);

$clinicService->loadPatients('patients.json');
$clinicService->normalizeScores();

if (isset($_GET['N'])) {
    $N = intval($_GET['N']);
    $topPatients = $clinicService->getTopNPatients($N);

    header('Content-Type: application/json');
    echo json_encode($topPatients);
}
