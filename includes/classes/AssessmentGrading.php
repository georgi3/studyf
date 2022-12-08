<?php

class AssessmentGrading{
    private $conn;

    public function __construct($connection){
        $this->conn = $connection;
    }

    public function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);
        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }

    private function getUserID($username){
        $query = $this->conn->prepare("SELECT userId FROM users WHERE username=:username;");
        $query->bindValue(":username", $username);
        $query->execute();
        $result = $query->fetch();
        return $result['userId'];
    }

    private function analyzeAssessment($submittedAnswers, $correctAnswers){
        $earnedMarks = 0;

        for ($i = 0; $i <= count($submittedAnswers); $i++) {
            $submittedAnswerInfo = $submittedAnswers[$i];
            $correctAnswersInfo = $correctAnswers[$i];
            if ($correctAnswersInfo['correctAnswer'] == $submittedAnswerInfo['chosenAnswer']){
                $earnedMarks += $submittedAnswerInfo['questionMarks'];
            }
        }
        return $earnedMarks;
    }

    public function postGrades($assessmentId){
        $answersPath = "/Applications/XAMPP/xamppfiles/htdocs/soen_proj/data/assessment{$assessmentId}.json";
        $openedAnswers = file_get_contents($answersPath);
        $answers = json_decode($openedAnswers, true);
        $correctAnswers = $answers['assessmentQuestions'];

        $submissionPath = "/Applications/XAMPP/xamppfiles/htdocs/soen_proj/data/assessmentAnswers{$assessmentId}.json";
        $openedSubmission = file_get_contents($submissionPath);
        $submission = json_decode($openedSubmission, true);
        $username = $submission['username'];
        $assessmentId = $submission['assessmentId'];
        $submittedAnswers = $submission['assessmentQuestions'];

        $earnedMarks = $this->analyzeAssessment($submittedAnswers, $correctAnswers);
        $this->uploadGrades($username, $assessmentId, $earnedMarks);
}

    private function uploadGrades($username, $assessmentId, $earnedMarks){
        $userId = $this->getUserID($username);
        $query = $this->conn->prepare("INSERT INTO GradedAssessment
                                            (userId, assessmentId, earnedMarks)
                                            VALUES (:userId, :assessmentId, :earnedMarks)
                                            ;");
        $query->bindValue(":userId", $userId);
        $query->bindValue(":assessmentId", $assessmentId);
        $query->bindValue(":earnedMarks", $earnedMarks);
        //         Debugging query
//        $query->execute();
//        $this->debug_to_console($query->errorInfo());
//        var_dump($query->errorInfo());
        return $query->execute();
    }

    public function getListOfCompletedAssessments($username){
        $userId = $this->getUserID($username);
        $query = $this->conn->prepare("SELECT assessmentId FROM GradedAssessment
                                         WHERE userId=:userId;");
        $query->bindValue(":userId", $userId);
        $query->execute();
        $completedAssessments = array();

        foreach($query->fetchAll() as $assmnt){
            array_push($completedAssessments, $assmnt['assessmentId']);
        }
        return $completedAssessments;
    }
    public function getCompletedAssessment($username, $assessmentId){
        $userId = $this->getUserID($username);
        $query = $this->conn->prepare("SELECT * FROM GradedAssessment
                                         WHERE assessmentId=:assessmentId AND userId=:userId;");
        $query->bindValue(":userId", $userId);
        $query->bindValue(":assessmentId", $assessmentId);
        $query->execute();
        //         Debugging query
//        $query->execute();
//        $this->debug_to_console($query->errorInfo());
//        var_dump($query->errorInfo());
        return $query->fetch();
    }
    // for register & login
    // if user is authenticated block access to register & login
    public function redirectIfAuthenticated(){
        if (isset($_SESSION['userLoggedIn'])){
            if ($_SESSION['userIsTeacher']){

                header('Location: /soen_proj/teacherDashboard.php');
            } else{
                header('Location: /soen_proj/studentDashboard.php');
            }
        }
    }

    public function checkForCompletion($username, $assessmentId, $courseCode){
        $userId = $this->getUserID($username);
        $query = $this->conn->prepare("SELECT * FROM GradedAssessment
                                         WHERE assessmentId=:assessmentId AND userId=:userId;");
        $query->bindValue(":userId", $userId);
        $query->bindValue(":assessmentId", $assessmentId);
        $query->execute();
        if($query->rowCount() != 0){
            header("Location: /soen_proj/studentAssessments.php?courseCode={$courseCode}");
        }
    }

}