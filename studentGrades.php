<?php
include 'partials/base.php';
include_once 'partials/authCheck.php';

protectStudentProperty();
startblock('main');
?>
    <div class="dashboard">
        <div class="side-menu">
            <div class="content">
                <div class="chosen-course">
                    <h6>SOEN 287 Q 2222</h6>
                    <div class="course">
                        <a href=""><span>Participants</span></a>
                    </div>
                    <div class="course">
                        <a href=""><span>Assessments</span></a>
                    </div>
                    <div class="course">
                        <a href=""><span>Grades</span></a>
                    </div>
                </div>
                <div class="courses">
                    <h6>My Courses</h6>
                    <div class="course">
                        <a href=""><span>COMM 226 Q 2222</span></a>
                    </div>
                    <div class="course">
                        <a href=""><span>COMM 217 Q 2222</span></a>
                    </div>
                    <div class="course active">
                        <a  href=""><span>SOEN 287 Q 2222</span></a>
                    </div>
                    <div class="course">
                        <a href=""><span>COMM 220 Q 2222</span></a>
                    </div>
                    <div class="course">
                        <a href=""><span>COMP 248 Q 2222</span></a>
                    </div>
                    <div class="course">
                        <a href=""><span>Course 6</span></a>
                    </div>
                    <div class="course">
                        <a href=""><span>Course 7</span></a>
                    </div>
                    <div class="course">
                        <a href=""><span>Course 8</span></a>
                    </div>
                </div>
            </div>
        </div>
        <div class = "ReportTitle">
            <h4> Student Report </h4>
            <h4> Course Name </h4>
        </div>
        <div class="ReportTitle">
            <table class="a">
                <tr>
                    <th><b>Assessment</b></th>
                    <th><b>Weight</b></th>
                    <th><b>Grade</b></th>
                    <th><b>Range</b></th>
                    <th><b>Feedback</b></th>
                </tr>
            </table><hr style="text-align:left; margin-left:10px; margin-right:10px;"><br><br>

        </div>




        <div class="reportTotal">
            <p class = reportTotaltext> <b> Course Total : </p>
        </div>
    </div>
<?php
endblock();
?>