<?php
include 'partials/sideMenuStudentDashboard.php';
//$existingCourses = new ExistingCourses($connection);

startblock('dashboardMain');
?>
<!--        <div class="dashboard">         inherited from side menu partial    -->
<div class="main-section">
    <?php if (!isset($_GET['courseCode'])): ?>
        <div class="emptyHeading">
            <h1 id='courseTitle'>Select the course you would like to edit</h1>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="">Home</a>
                </li>
                <li class="breadcrumb-item">
                    &nbsp;/ My courses /&nbsp;
                </li>
            </ul>
        </div>
    <?php else : ?>
        <div class="heading">
            <?php
//            echo "<input type='hidden' value='{$currentCourseCode}' id='courseCodeInput'>";
            echo "<h1 id='courseTitle'>{$currentCourseTitle}</h1>";
            ?>
            <ul class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/soen_proj/studentDashboard.php">Home</a>
                </li>
                <li class="breadcrumb-item">
                    &nbsp;/ My courses /&nbsp;
                </li>
                <li class="breadcrumb-item">
                    <?php
                    echo "<a href='/soen_proj/studentDashboard.php?courseCode={$currentCourseCode}'>{$currentCourseTitle}</a>"
                    ?>
                </li>
            </ul>
            <h2>Description</h2>
            <?php
            if (isset($arrayCourseData['courseDesc'])){
                echo "<div class='custom-textarea studentDashboard' id='courseDescription'>", $arrayCourseData['courseDesc'], "</div>";
            } else{
                echo "<div class='custom-textarea studentDashboard' id='courseDescription'>Your teacher has not created content for this section yet.</div>";
            }
            ?>
        </div>
        <div class="course-content">
            <?php
            if (isset($arrayCourseData['courseContent'])){
                $sectionContent = $arrayCourseData['courseContent'];
                $i = 0;
                foreach ($sectionContent as $section){
                    echo "<div class='section' id='contentSection{$i}'>";
                    if (isset($section['sectionTitle'])){
                        echo "<h1 class='titleInput studentDashboard' id='titleInputID{$i}'>{$section['sectionTitle']}</h1>";
                    } else{
                        echo "<h1 class='titleInput studentDashboard' id='titleInputID{$i}'>Your teacher has not created content for this section yet.</h1>";
                    }
                    if (isset($section['sectionText'])){
                        echo "<div class='custom-textarea studentDashboard' id='textInputID{$i}'>{$section['sectionText']}</div>";
                    } else{
                        echo "<div class='custom-textarea studentDashboard' id='textInputID{$i}'>Your teacher has not created content for this section yet.</div>";
                    }
                    echo "<div class='sectionLinksArea' id='sectionLinkArea{$i}'>";
                    echo "<div class='sectionLink studentDashboard'>";
                    if (isset($section['sectionLinkHT']) && isset($section['sectionLinkURL'])){
                        echo "<a href='{$section['sectionLinkURL']}' class='linkInput' id='linkInputURL{$i}'>{$section['sectionLinkHT']}</a>";
                    } else{
//                        echo "<input type='text' placeholder='Link Text..' class='linkInput studentDashboard' id='linkInputHT{$i}'>";
//                        echo "<input type='url' placeholder='https://example.com' class='linkInput studentDashboard' id='linkInputURL{$i}'>";
                    }
                    echo "</div>";
                    echo "</div>";
                    echo "<div class='sectionFileArea'>";
//                    echo "<label for='fileInputID{$i}' class='custom-file-upload'>Upload File</label>";
//                    echo "<input type='file' id='fileInputID{$i}' name='' onchange='displaySelectedFileName(this);' multiple/>";
                    echo "</div>";
                    echo "</div>";
                    $i++;
                }
            } else{
                echo "<div class='section' id='contentSection0'>
                            <h1 class='titleInput studentDashboard' id='titleInputID0'>Your teacher has not created content for this section yet.</h1>
                            <div class='custom-textarea studentDashboard' id='textInputID0'>Your teacher has not created content for this section yet.</div>
                            <div class='sectionLinksArea' id='sectionLinkArea0'>
                                <div class='sectionLink'>
                                </div>
                            </div>   
                            <div class='sectionFileArea'>";
echo                    "</div>
                      </div>";
            }
            ?>
        </div>
    <?php endif;?>
</div>
<!--                </div>      END OF div.dashboard      -->
<?php
endblock();
?>

