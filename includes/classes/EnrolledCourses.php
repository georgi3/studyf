<?php

class EnrolledCourses{
    private $conn;
    private $errorArr = array();

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
    private function getCourseID($courseCode){
        $query = $this->conn->prepare("SELECT courseId FROM ExistingCourses WHERE courseCode=:courseCode;");
        $query->bindValue(":courseCode", $courseCode);
        $query->execute();
        $result = $query->fetch();
        return $result['courseId'];
    }

    public function enrollInCourse($username, $courseCode){
        $userId = $this->getUserID($username);
        $courseId = $this->getCourseID($courseCode);
        $query = $this->conn->prepare("INSERT INTO EnrolledCourses
                                            (userId, courseId)
                                            VALUES (:userId, :courseId)
                                            ;");
        $query->bindValue(":userId", $userId);
        $query->bindValue(":courseId", $courseId);
        //         Debugging query
//        $query->execute();
//        $this->debug_to_console($query->errorInfo());
//        var_dump($query->errorInfo());
        return $query->execute();
    }

    public function enrolledCourses($username){
        $userId = $this->getUserID($username);
        $query = $this->conn->prepare("SELECT courseId FROM EnrolledCourses
                                            WHERE userId=:userId;");
        $query->bindValue(":userId", $userId);
        $query->execute();
        $courseIds = array();
        foreach($query->fetchAll() as $course){
            array_push($courseIds, $course['courseId']);
        }
        return $courseIds;
    }
    public function getStudentsCourses($username){
        $userId = $this->getUserID($username);
        $query = $this->conn->prepare("SELECT * FROM `ExistingCourses`
                                        WHERE courseId in
                                              (SELECT courseId FROM EnrolledCourses WHERE userId=:userId);");
        $query->bindValue(":userId", $userId);
        $query->execute();
        return $query->fetchAll();
    }


}