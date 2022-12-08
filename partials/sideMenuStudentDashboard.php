<?php
include 'partials/base.php';
include 'partials/authCheck.php';
//require 'includes/classes/FormSanitizer.php';
require 'includes/classes/ExistingCourses.php';
require 'includes/classes/EnrolledCourses.php';
protectStudentProperty();

$existingCourses = new ExistingCourses($connection);
$enrolledCourses = new EnrolledCourses($connection);

// Querying student's courses
$username = $_SESSION['userLoggedIn'];
$studentCourses = $enrolledCourses->getStudentsCourses($username);

// Parse Saved Data
if (isset($_GET['courseCode'])){
    // parsing json
    $courseCode = $_GET['courseCode'];
    $filePath = "/Applications/XAMPP/xamppfiles/htdocs/soen_proj/data/{$courseCode}.json";
    $openedFile = file_get_contents($filePath);
    $arrayCourseData = json_decode($openedFile, true);
//    $sectionContent = $arrayCourseData['courseContent'];
    $currentCourseCode = $_GET['courseCode'];
    // getting course title from db
    foreach ($studentCourses as $course){
        if ($course['courseCode'] == $currentCourseCode){
            $currentCourseTitle = $course['courseName'];
        }
    }
}
startblock('main');
?>
<!-- Dashboard -->
<div class="dashboard">
    <div class="side-menu">
        <div class="content">
            <?php if(!$studentCourses) : ?>
                <div class="chosen-course">
                    <h6>Add Course</h6>
                </div>
            <?php elseif (!isset($_GET['courseCode'])): ?>
                <div class="chosen-course">
                    <?php
                    echo " <h6>Select Course</h6>";
                    ?>
                </div>
            <?php else : ?>
                <div class="chosen-course">
                    <?php
                    echo " <h6>{$currentCourseTitle}</h6>";
                    ?>
                </div>
                <div class="course">
                    <a href=""><span>Participants</span></a>
                </div>
                <div class="course">
                    <?php
                    echo "<a href='/soen_proj/studentAssessments.php?courseCode={$currentCourseCode}'><span>Assessments</span></a>";
                    ?>
                </div>
                <div class="course">
                    <a href=""><span>Grades</span></a>
                </div>
            <?php endif;?>
        </div>
        <div class="courses">
            <h6>My Courses</h6>
            <?php
            if ($studentCourses){
                foreach($studentCourses as $course){
                    echo "<div class='course'>
                                    <a href='/soen_proj/studentDashboard.php?courseCode={$course['courseCode']}'><span>{$course['courseName']}</span></a>
                              </div>";
                }
            }
            ?>
        </div>
    </div>

    <?php startblock('dashboardMain'); ?>

    <?php endblock(); ?>

</div>
<!-- Dashboard End -->
<?php endblock(); ?>


