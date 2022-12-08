<?php
include 'partials/base.php';
include 'partials/authCheck.php';
require 'includes/classes/FormSanitizer.php';
require 'includes/classes/ExistingCourses.php';
protectTeacherProperty();

$existingCourses = new ExistingCourses($connection);

// Querying teacher's courses
$username = $_SESSION['userLoggedIn'];
$teacherCourses = $existingCourses->getTeachersCourses($username);

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
    foreach ($teacherCourses as $course){
        if ($course['courseCode'] == $currentCourseCode){
            $currentCourseTitle = $course['courseName'];
        }
    }
}

// Dealing with modal form
if (isset($_POST['createNewCourseBtn'])){
    // Sanitize
    $courseName = FormSanitizer::sanitizeString($_POST['courseName']);
    $courseTags = FormSanitizer::sanitizeString($_POST['courseTags']);
    $courseVisibility = $_POST['courseVisibility'];
    // Send data to db
    $username = $_SESSION['userLoggedIn'];
    $result = $existingCourses->registerCourse($username, $courseName, $courseTags, $courseVisibility);
    header("Location: /soen_proj/teacherDashboard.php");
}

startblock('main');
?>
<div class="dashboard">
    <div class="side-menu">
        <div class="content">
            <?php if(!$teacherCourses) : ?>
                <div class="chosen-course">
                    <h6>Create Course</h6>
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
                        echo "<a href='/soen_proj/teacherAssessments.php?courseCode={$currentCourseCode}'><span>Assessments</span></a>";
                    ?>
                </div>
                <div class="course">
                    <a href=""><span>Grades</span></a>
                </div>
                <div class="course">
                    <a href="/soen_proj/teacherGradesPage.php"><span>Schedule</span></a>
                </div>
            <?php endif;?>
        </div>
        <div class="courses">
            <h6>My Courses</h6>
            <?php
            if ($teacherCourses){
                foreach($teacherCourses as $course){
                    echo "<div class='course'>
                                    <a href='/soen_proj/teacherDashboard.php?courseCode={$course['courseCode']}'><span>{$course['courseName']}</span></a>
                              </div>";
                }
            }
            ?>
            <button class="addContentBtn" id="createNewCourse" onclick="createNewCourse();"></button>
        </div>
    </div>
    <?php startblock('dashboardMain'); ?>

    <?php endblock(); ?>
</div>
<!--                Modal                -->
<div id="newCourseModal" class="modal">
    <div class="modal-content">
        <span id="closeCreateNewCourseModal" class="close">&times;</span>
        <div class="modalContainer">
            <div class="modalBox">
                <div>
                    <h1>Create New Course</h1>
                </div>
                <!--                <div class="alertDiv">-->
                <!--                    --><?php //echo  $account->getError(Constants::$incorrectCredentials); ?>
                <!--                </div>-->
                <form action="" method="POST">
                    <div>
                        <p>Name of the Course</p>
                        <input type="text" name='courseName' placeholder="Course Name"
                               value="" required>
                        <p>Course Tags</p>
                        <input type="text" name='courseTags' placeholder='Course Tags'
                               value="" required>
                        <p>Course Visibility</p>
                        <input type="radio" id="publicCourse" name="courseVisibility" value="0">
                        <label for="publicCourse">Public Course</label><br>
                        <input type="radio" id="privateCourse" name="courseVisibility" value="1">
                        <label for="privateCourse">Private Course</label>
                        <input type="submit" name="createNewCourseBtn" value="Create New Course">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endblock(); ?>


