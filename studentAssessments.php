<?php
include 'partials/sideMenuStudentDashboard.php';
include 'includes/classes/CourseAssessments.php';
include 'includes/classes/AssessmentGrading.php';
$assessmentsConnection = new CourseAssessments($connection);
$assessmentGrading = new AssessmentGrading($connection);
$username = $_SESSION['userLoggedIn'];
$courseAssessments = $assessmentsConnection->getCourseAssessments($currentCourseCode);
$completedAssessments = $assessmentGrading->getListOfCompletedAssessments($username);
startblock('dashboardMain');
?>
<!--        <div class="dashboard">         inherited from side menu partial    -->
<div class="assessmentsMain">
    <div class="listOfAllAssessments">
        <div class="title">
            <?php
            echo "<h1>Course Assessments For {$currentCourseTitle}</h1>";
            ?>
        </div>
        <?php
        if($courseAssessments){
            $i = 1;
            foreach($courseAssessments as $assessment){
                echo "<div class='singleAssessment'>";
                if (in_array($assessment['assessmentId'], $completedAssessments)){
                    $assessmentGrades = $assessmentGrading->getCompletedAssessment($username, $assessment['assessmentId']);
                    echo "<p>
                            <span class='assessmentTitleSpan'>{$i}. {$assessment['assessmentName']} &nbsp;&nbsp;&nbsp;&nbsp;<strong>Completed</strong></span>
                            <span class='assessmentStats'>Number of Questions: {$assessment['questionCount']}</span>
                            <span class='assessmentStats'> | </span>
                            <span class='assessmentStats'>Marks: <strong>{$assessmentGrades['earnedMarks']}</strong>/{$assessment['totalMarks']}</span>
                            <span class='assessmentStats'> | </span>
                            <span class='assessmentStats'> Weight: {$assessment['weight']}%</span></p>";
                }else{
                    echo "<a href='/soen_proj/assessmentTest.php?courseCode={$currentCourseCode}&assessmentId={$assessment['assessmentId']}'>
                            <span class='assessmentTitleSpan'>{$i}. {$assessment['assessmentName']}</span>
                            <span class='assessmentStats'>Number of Questions: {$assessment['questionCount']}</span>
                            <span class='assessmentStats'> | </span>
                            <span class='assessmentStats'>Marks: {$assessment['totalMarks']}</span>
                            <span class='assessmentStats'> | </span>
                            <span class='assessmentStats'> Weight: {$assessment['weight']}%</span></a>";
                }
                echo "</div>";
                $i++;
            }
        }else{
            echo "<div class='singleAssessment'>";
            echo "<h4>Your teacher has not created any assessments for this course yet.</h4>";
            echo "</div>";
        }
        ?>
    </div>
</div>
<!-- </div>      END OF div.dashboard      -->
<?php
endblock();
?>


