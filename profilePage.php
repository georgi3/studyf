<?php
include 'partials/base.php';
include_once 'partials/authCheck.php';
require_once 'includes/classes/UserAccounts.php';

if ($_SESSION['userIsTeacher']){
    protectTeacherProperty();
}else{
    protectStudentProperty();
}
$account = new UserAccounts($connection);
$username = $_SESSION['userLoggedIn'];
$userInfo = $account->getUserInfo($username);
startblock('main');
?>
    <div class="profileDiv">
        <div class="headerWrapper">
            <div class="columnsContainer">
                <div class="leftColumn">
                    <div class="profileAvatar">
                        <img src="assets/imgs/loginAvatar1.png" alt="Current user's name"/>
                    </div>
                    <?php
                    echo "<h1>{$userInfo[2]} {$userInfo[3]}</h1>";
                    if ($_SESSION['userIsTeacher']){
                        echo "<p>Teacher</p>";
                    }else{
                        echo "<p>Student</p>";
                    }
                    echo "<div class='content'>
                        <p><br><br>{$userInfo[4]}<br><br></p>
                        <p> Department of Computer Science at Concordia University.</p>
                    </div>";
                    ?>
                </div>
                <div class="rightColumn">
                    <nav>
                        <ul id="ulMenuProfile">
                            <li><a class="aOfProfile" href="teacherDashboard.php">Courses</a></li>
                            <li><a class="aOfProfile" href="">Schedule</a></li>
                            <li><a class="aOfProfile" href="">Messages</a></li>
                            <li><a class="aOfProfile" href="">Settings</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
<?php
endblock();
?>