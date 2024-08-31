<?php

require '../vendor/autoload.php';

class ClinicServiceTest extends PHPUnit\Framework\TestCase
{
    public function testLoadPatients()
    {
        $clinicService = new ClinicService(64.4811, 33.2338);
        $clinicService->loadPatients('../patients.json');
        $this->assertNotEmpty($clinicService->getTopNPatients(10));
    }

    public function testScoreCalculation()
    {
        $patient = new Patient('id', 'Test', 46.7110, -63.1150, 46, 49, 92, 2598);
        $patient->calculateDistanceToClinic(64.4811, 33.2338);
        $patient->calculateScore();
        $this->assertNotNull($patient->score);
    }
}
