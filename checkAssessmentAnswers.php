<?php
include_once 'includes/config.php';
require_once 'includes/config.php';
require_once 'includes/classes/AssessmentGrading.php';

$gradeAssessments = new AssessmentGrading($connection);

// write assessment to data to file
$newCourseAssessment = trim(file_get_contents('php://input'));
$jsonString = json_decode($newCourseAssessment, JSON_PRETTY_PRINT);
if (gettype($jsonString) == 'array'){
    $assessmentId = $jsonString['assessmentId'];
    // Write to data file
    $filePath = "/Applications/XAMPP/xamppfiles/htdocs/soen_proj/data/assessmentAnswers{$assessmentId}.json";
    $openedFile = fopen($filePath, 'w+');
    fwrite($openedFile, $newCourseAssessment);
    fclose($openedFile);
//    header("Location: /soen_proj/studentDashboard.php"); // does not work
}
$gradeAssessments->postGrades($assessmentId);


