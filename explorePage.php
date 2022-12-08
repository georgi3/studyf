<?php
include 'partials/base.php';
include_once 'partials/authCheck.php';
require_once 'includes/classes/ExistingCourses.php';
require_once 'includes/classes/EnrolledCourses.php';
require_once 'includes/classes/FormSanitizer.php';
protectStudentProperty();

$coursesConnection = new ExistingCourses($connection);
$courseEnrolment = new EnrolledCourses($connection);
$autocompleteOptions = $coursesConnection->getCoursesTags();

if (isset($_POST['searchCourses'])){
    // Sanitize
    $queryString = FormSanitizer::sanitizeString($_POST['courseSearchBar']);
    $searchedCourses = $coursesConnection->findSearchedCourses($queryString);
    $usersEnrolledCourses = $courseEnrolment->enrolledCourses($_SESSION['userLoggedIn']);
}
if (isset($_POST['EnrollInCourse'])){
    $courseCode = $_POST['courseID'];
    $courseEnrolment->enrollInCourse($_SESSION['userLoggedIn'],$courseCode);
    header("Location: /soen_proj/studentDashboard.php");
}
//$autocompleteOptions = array_values($autocompleteOptions);
startblock('main');
?>
<?php
foreach ($autocompleteOptions as $option){
    echo "<option class='autocompleteOptions' style='visibility: collapse; display: none;'>{$option}</option>";
}
?>
<div class="explorePage">
    <div class="searchBarContainer">
        <div class="searchBar">
            <h3>Explore all possible courses here</h3>
            <form action="" method="POST" autocomplete="off">
                <div class="searchBarAutoComplete">
                    <input type="text" id="courseSearchBar" name="courseSearchBar" placeholder="Enter topic of interest or Course Code">
                    <button type="submit" name="searchCourses"><i class="fa fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>
    <?php
    if(isset($_POST['searchCourses'])){
        echo "<div class='searchResults'>";
    }
    ?>
    <?php
    if(isset($_POST['searchCourses'])){
        echo "<h1>Search Results for {$queryString}</h1>";
        echo "<div class='suggestedCourses'>";
//        echo gettype($usersEnrolledCourses), "{$usersEnrolledCourses}";
//        $i = 0;
        foreach ($searchedCourses as $course){
            $filePath = "/Applications/XAMPP/xamppfiles/htdocs/soen_proj/data/{$course[4]}.json";
            $openedFile = file_get_contents($filePath);
            $courseJSON = json_decode($openedFile, true);
            echo "
                              <div class='suggestedCourse'>
                                  <div class='courseTitle'>
                                      <h3>{$course[2]}</h3>
                                  </div>
                                  <div class='courseImage'>
                                      <img src='assets/imgs/jsIMG.png' alt='Course Img'>
                                  </div>
                                  <div class='courseTags'>
                                  ";
            foreach (explode(',', $course[3]) as $courseTag){
                echo "
                                      <p>{$courseTag}</p>
                                  ";
            }
            echo "
                                  </div>
                                  <div class='courseDesc'>
                                      <p>{$courseJSON['courseDesc']}</p>
                                  </div>  
                              ";
            if (in_array($course[0], $usersEnrolledCourses)){
                echo "
                              <div class='alreadyEnrolled'>Already Enrolled</div>   
                ";
            } else{
                echo "
                              <form action='' method='POST'>
                                  <input type='hidden' name='courseID' value='{$course[4]}'>
                                  <input type='submit' class='enrollInCourse' name='EnrollInCourse' value='Enroll' />
                               </form>
                ";
            }
            echo "</div>";
        }
    }
    ?>
    <?php
    if(isset($_POST['searchCourses'])){
        echo "</div>";
    }
    ?>
    </div>
    <div class="suggestedCoursesContainer">
        <h1>Explore Courses Here</h1>
        <div class="suggestedCourses">
            <div class="suggestedCourse">
                <div class="courseTitle">
                    <h3>Course Name</h3>
                </div>
                <div class="courseImage">
                    <img src="assets/imgs/jsIMG.png" alt="Course Img">
                </div>
                <div class="courseTags">
                    <p>Web</p>
                    <p>React</p>
                    <p>JS</p>
                    <p>HTML</p>
                    <p>CSS</p>
                </div>
                <div class="courseDesc">
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        Lorem Ipsum has been the industry's standard dummy text
                        ever since the 1500s, when an unknown printer took a galley of type and scrambled
                        it to make a type specimen book. It has survived not only five centuries, but also
                        the leap into electronic typesetting, remaining essentially
                        unchanged. It was popularised in the 1960s with the release of Letraset sheets
                        containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus
                        PageMaker including versions of Lorem Ipsum.</p>
                </div>
            </div>
            <div class="suggestedCourse">
                <div class="courseTitle">
                    <h3>Course Name</h3>
                </div>
                <div class="courseImage">
                    <img src="assets/imgs/jsIMG.png" alt="Course Img">
                </div>
                <div class="courseTags">
                    <p>Web</p>
                    <p>React</p>
                    <p>JS</p>
                    <p>HTML</p>
                    <p>CSS</p>
                </div>
                <div class="courseDesc">
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        Lorem Ipsum has been the industry's standard dummy text
                        ever since the 1500s, when an unknown printer took a galley of type and scrambled
                        unchanged. It was popularised in the 1960s with the release of Letraset sheets
                        containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus
                        PageMaker including versions of Lorem Ipsum.</p>
                </div>
            </div>
            <div class="suggestedCourse">
                <div class="courseTitle">
                    <h3>Course Name</h3>
                </div>
                <div class="courseImage">
                    <img src="assets/imgs/jsIMG.png" alt="Course Img">
                </div>
                <div class="courseTags">
                    <p>Web</p>
                    <p>React</p>
                    <p>JS</p>
                    <p>HTML</p>
                    <p>CSS</p>
                </div>
                <div class="courseDesc">
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        Lorem Ipsum has been the industry's standard dummy text
                        ever since the 1500s, when an unknown printer took a galley of type and scrambled
                        it to make a type specimen book. It has survived not only five centuries, but also
                        the leap into electronic typesetting, remaining essentially
                        unchanged. It was popularised in the 1960s with the release of Letraset sheets
                        containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus
                        PageMaker including versions of Lorem Ipsum.</p>
                </div>
            </div>
            <div class="suggestedCourse">
                <div class="courseTitle">
                    <h3>Course Name</h3>
                </div>
                <div class="courseImage">
                    <img src="assets/imgs/jsIMG.png" alt="Course Img">
                </div>
                <div class="courseTags">
                    <p>Web</p>
                    <p>React</p>
                    <p>JS</p>
                    <p>HTML</p>
                    <p>CSS</p>
                </div>
                <div class="courseDesc">
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        Lorem Ipsum has been the industry's standard dummy text
                        containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus
                        PageMaker including versions of Lorem Ipsum.</p>
                </div>
            </div>
            <div class="suggestedCourse">
                <div class="courseTitle">
                    <h3>Course Name</h3>
                </div>
                <div class="courseImage">
                    <img src="assets/imgs/jsIMG.png" alt="Course Img">
                </div>
                <div class="courseTags">
                    <p>Web</p>
                    <p>React</p>
                    <p>JS</p>
                    <p>HTML</p>
                    <p>CSS</p>
                </div>
                <div class="courseDesc">
                    <p>
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                    </p>
                </div>
            </div>
            <div class="suggestedCourse">
                <div class="courseTitle">
                    <h3>Course Name</h3>
                </div>
                <div class="courseImage">
                    <img src="assets/imgs/jsIMG.png" alt="Course Img">
                </div>
                <div class="courseTags">
                    <p>Web</p>
                    <p>React</p>
                    <p>JS</p>
                    <p>HTML</p>
                    <p>CSS</p>
                </div>
                <div class="courseDesc">
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        Lorem Ipsum has been the industry's standard dummy text
                        ever since the 1500s, when an unknown printer took a galley of type and scrambled
                        it to make a type specimen book. It has survived not only five centuries, but also
                        PageMaker including versions of Lorem Ipsum.</p>
                </div>
            </div>
            <div class="suggestedCourse">
                <div class="courseTitle">
                    <h3>Course Name</h3>
                </div>
                <div class="courseImage">
                    <img src="assets/imgs/jsIMG.png" alt="Course Img">
                </div>
                <div class="courseTags">
                    <p>Web</p>
                    <p>React</p>
                    <p>JS</p>
                    <p>HTML</p>
                    <p>CSS</p>
                </div>
                <div class="courseDesc">
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                </div>
            </div>
        </div>
    </div>
</div>
<?php
endblock();
?>

