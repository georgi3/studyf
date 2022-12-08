<?php
include 'partials/base.php';
include_once 'partials/authCheck.php';
require_once 'includes/classes/CourseAssessments.php';
include 'includes/classes/AssessmentGrading.php';

protectStudentProperty();

$assessmentGrading = new AssessmentGrading($connection);
$username = $_SESSION['userLoggedIn'];
$assessmentId = $_GET['assessmentId'];
$courseCode = $_GET['courseCode'];
$assessmentGrading->checkForCompletion($username, $assessmentId, $courseCode);
$assessmentsConnection = new CourseAssessments($connection);
$courseAssessment = $assessmentsConnection->getCourseAssessment($assessmentId);
$filePath = "/Applications/XAMPP/xamppfiles/htdocs/soen_proj/data/assessment{$assessmentId}.json";
$openedFile = file_get_contents($filePath);
$arrayAssessmentData = json_decode($openedFile, true);

if (isset($_POST['submitTest'])){
    header("Location: /soen_proj/studentAssessments.php");
}

startblock('main');
?>
<div class="assessmentTemplate">
    <form id="" action="" method="POST" onsubmit="event.preventDefault(); submittedAssessment();">
        <div class="templateContent">
            <div class="totalMarks">
                <!-- To be fetched from db  -->
                <p>Total Marks: <span id="totalAllowedMarks"><?php echo $courseAssessment[5]; ?></span></p>
<!--                <input type="hidden" id="numberOfQuestion" value="--><?php //echo$courseAssessment[4]; ?><!--">-->
                <input type="hidden" id="username" value="<?php echo $_SESSION['userLoggedIn']; ?>">
            </div>
            <div class="templateHeader">
                <?php
                if (isset($arrayAssessmentData['assessmentTitle'])){
                    echo "<h1 class='assessmentTitle' id='assessmentTitle'>{$arrayAssessmentData['assessmentTitle']}</h1>";
                }else{
                    echo "<h1 class='assessmentTitle' id='assessmentTitle' contenteditable='true'>Title is not set</h1>";
                }
                ?>
            </div>
            <div class="templateBody">
                <div class='questionsContainer'>
                    <?php
                    if (isset($arrayAssessmentData['assessmentQuestions'])){
                        $questions = $arrayAssessmentData['assessmentQuestions'];
                        foreach($questions as $question){
                            echo "
                                 <div class='questionContainer'>
                                     <div class='questionHeader'>
                                         <div class='questionMarkInputDiv'>
                                             <h2>Question {$question['questionNumber']} Marks: <span class='mcq-marks'>{$question['questionMarks']}</span></h2>
                                         </div>
                                         <div class='questionInput'>
                                             <p id='q{$question['questionNumber']}'>{$question['question']}</p>
                                         </div>
                                     </div>
                                     <div class='questionAnswers'>
                                     ";
                            $answerLetters = ['a', 'b', 'c', 'd', 'e', 'f'];
                            $i = 0;
                            foreach($question['answers'] as $answer){
                                $questionID = "q{$question['questionNumber']}a_{$answerLetters[$i]}";
                                echo "
                                         <div class='questionAnswer'>
                                             <input type='radio' id='q{$question['questionNumber']}_{$answerLetters[$i]}' name='q{$question['questionNumber']}mcq' value='{$questionID}' class='mcq-answer' required>
                                             <p id='{$questionID}'>{$answer['answer']}</p>
                                         </div>
                                     ";
                                $i++;
                            }
                            echo "
                                     </div>
                                 </div>
                                ";
                        }
                    }else{
                        echo "Your teacher has not created this assignment yet";
                    }
                    ?>
                </div>
            </div>
            <div class="templateFooter">
                <input type="submit" name="submitTest" class="saveAssessmentBtn" value="Submit Assessment">
            </div>
        </div>
    </form>
</div>
<?php
endblock();
?>
