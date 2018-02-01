<?php
require_once('authenticate.php'); /* for security purposes */
include 'mtgRedirects.php';
include 'mysql.connect.php';
/*
 * peepAction.php
 * ======================================================
 * this uses pageHead.txt, pageTop.txt & pageBottom.txt
 */

require_once("classPage.php");

$page = new Page();
print $page->getTop();

/*####################################################
 * START MAIN PROCESSING
 * ###################################################
 */

$Action = $_GET["Action"];
$Destination = $_GET["Destination"];
$Origin = $_GET["Origin"];
$ID = $_GET["ID"];

switch ("$Action"){     
    case "Edit":
        showForm("Edit","","", $PID);
        break;
    case "TraineeList":
        $TID = $_GET["TID"];
        showPeepsToTrain($TID);
        break;
    case "TeamList":
        $TID = $_GET["TID"];
        $TeamTitle = $_GET["TeamTitle"];
        showPeepsToDraft($TID, $TeamTitle);
        break;
    case "TeamCandidates":
        $TID = $_GET["TID"];
        $TeamTitle = $_GET["TeamTitle"];
        showDraftList($TID, $TeamTitle);
        break;
    case "NewTrainee":
        /*============================================
         * Need to display blank form to add new person
         * for adding to training
         *============================================
         */
        $TID = $_GET["TID"];
        showForm("NewTrainee", "", "", $TID);
        break;
    case "NewPeep":
        /*============================================
         * Need to display blank form to add new person
         * for adding to system
         *============================================
         */
        showForm("NewPeep", "", "", $TID);
        break;
    case "New":
        /*============================================
         * Need to display blank form to add new person
         * for adding to system
         *============================================
         */
        showForm("New", $_GET['Origin'], $_GET['Dest'], $_GET['$TID']);
        break;
    case "ShowAll":
        /*==================================================
         * show all active and non-active people in system
         * =================================================
         */
        showAllPeople();
        break;
    
    default:
        showPeopleList();
        break;
}


/* =============================
 * GENERIC HTML TO FOLLOW:
 * =============================
 */
/* print <<<EOF
<div id="mainContent">

<p>This is where content would go, should there be any.</p>
</div> <!-- end main content -->

EOF;
 * 
 */
print ("</center>");
print $page->getBottom();



function showPeopleList() {
   include 'mysql.connect.php';
   echo "<center><h1>CR Personnel</h1>";

   if($mysqli->errno > 0){
       printf("Mysql error number generated: %d", $mysqli->errno);
       exit();
   }
   $query = "SELECT * FROM people WHERE Active = '1' order by FName";
   $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
   //echo "<div style='text-align:right; padding-right: 20px;'><a href='people.php?Action=NewPeep'>NEW ENTRY</a></div>";
   echo "<div style='text-align:right; padding-right: 20px;'><a href='people.php?Action=ShowAll'><img src='images/btnShowAll.gif'/></a></div>";
   echo "<table>";
   while(list($ID, $FName, $LName) = $result->fetch_row()){
           echo "<tr><td>";
           echo "<a href='people.php?Action=Edit&PID=" . $ID . "'><img src='images/btnEdit.gif'></img></a></td>";
           echo "<td>" . $FName . " " . $LName . "</td></tr>";
   }
   echo "</table>";
   
}
function showAllPeople() {
   include 'mysql.connect.php';
   echo "<center><h1>CR Personnel</h1>";

   if($mysqli->errno > 0){
       printf("Mysql error number generated: %d", $mysqli->errno);
       exit();
   }
   $query = "SELECT ID, FName, LName, Active FROM people order by FName";
   $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
   echo "<div style='text-align:right; padding-right: 20px;'><a href='people.php?Action=NewPeep'>NEW ENTRY</a></div>";
   //echo "<div style='text-align:right; padding-right: 20px;'><a href='people.php?Action=ShowAll'><img src='images/btnShowAll.gif'/></a></div>";
   echo "<table>";
   while(list($ID, $FName, $LName, $Active) = $result->fetch_row()){
           echo "<tr><td valign='bottom'>";
           echo "<a href='people.php?Action=Edit&PID=" . $ID . "'><img src='images/btnEdit.gif'></img></a></td>";
           echo "<td valign='bottom'>" . $FName . " " . $LName . " ";
           if ($Active == '0'){
               echo "<a href=peepAction.php?Action=Activate&ID=" . $ID . "'><img src='images/btnInActive.gif' height='20' valign='bottom'/></a>";
           }
                   
                   
                   "</td></tr>";
   }
   echo "</table>";
   
}

function showForm($action, $origin, $destination, $ID){
    /*##########################################################
     * showForm function
     * #########################################################
     */
    /* echo "showForm(" . $action . ", " . $origin . ", " . $destination . ")"; */
    include 'mysql.connect.php';
    $PID = $_GET["PID"];
   if($mysqli->errno > 0){
       printf("Mysql error number generated: %d", $mysqli->errno);
       exit();
   }
    switch ($action){
        case "New":
            switch ($_GET['Origin']){
                case "grpForm":
                    $dest = "peepAction.php?Action=AddPerson&Origin=grpForm&GID=" . $_GET['GID'] . "&MID=" . $_GET['MID'];
                    break;
                case "trnForm":
                    $dest = "peepAction.php?Action=AddPerson&Origin=trnForm&ID=" . $_GET['ID'];
                    break;
                case "mtgForm_Default":
                    $dest = "peepAction.php?Action=AddPerson&Origin=mtgForm";
                    break;
                case "mtgForm_Edit":
                    $dest = "peepAction.php?Action=AddPerson&Origin=mtgForm&ID=" . $_GET['MID'];
                    break;
                default:
                    $dest = "peepAction.php?Action=AddPerson&Origin=" . $origin;
                    break;
            }
            
            break;
        case "Edit":
            
            $dest = "peepAction.php?Action=Update&PID=" . $PID;
            /*---------------------------------------------------
             * get the peep info
             * --------------------------------------------------
             */
            if($mysqli->errno > 0){
                printf("Mysql error number generated: %d", $mysqli->errno);
                exit();
            }
            
            $query = "SELECT * FROM people WHERE ID =" . $PID;
            //$result = $mysqli->query($query);
            //echo "<br/>query: " . $query . "<br/>";
            $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
            list($ID, $FName, $LName, $Address, $City, $State, $Zipcode, $Phone1, 
                    $Phone2, $Email1, $Email2, 
                    $RecoveryArea, $RecoverySince, $CRSince,
                    $SpiritualGifts, $AreasServed, $JoyAreas, $ReasonsToServe,
                    $FellowshipTeam, $PrayerTeam, $NewcomersTeam,
                    $GreetingTeam, $SpecialEventsTeam, $ResourceTeam,
                    $SmallGroupTeam, $StepStudyTeam, $TransportationTeam,
                    $WorshipTeam, $LandingTeam, $CelebrationPlaceTeam,
                    $SolidRockTeam, $MealTeam, $CRImen, $CRIwomen,
                    $Active, $Notes) = $result->fetch_row(); 
            /*---------------------------------------------------
             * see if there is any training attended
             ====================================================*/
            /*
             $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
             
            if($mysqli->errno > 0){
                printf("Mysql error number generated: %d", $mysqli->errno);
                exit();
            }
            $query = "SELECT training.tDate training.tTitle";
            $query = $query . " FROM training INNER JOIN ";
            $query = $query . " trainees ON training.ID = trainees.TID";
            $query = $query . " WHERE trainees.PID='" . $ID . "'";
            
            $education = array();
            $result = $mysqli->query($query);
            while ($row = $result->fetch_array(MYSQLI_ASSOC))
            {
                $education[] = array($row['eDate'], $row['eTitle']);
            } 
             * 
             */
            break;
        
        case "NewTrainee":
            /*---------------------------------------------------
             * 
             *---------------------------------------------------
             */
            $dest = "peepAction.php?Action=AddPerson&TID=" . $ID;
            break;
        case ("NewPeep"):
            /*---------------------------------------------------
             * 
             *---------------------------------------------------
             */
            $dest = "peepAction.php?Action=AddPerson";
            break;
        default:
            $dest = "people.php";
            break; 
    }
    
    
    echo "<form id='mtgForm' action='" . $dest . "' method='post'>";
    echo "<center><h2>CR Personnel Form</h2></center>";
    echo "<center>";
    echo "<table border='0'>";
    echo "<tr><td align='right'>First Name:</td><td><input type='text' name='peepFName' size='15' value='" . htmlspecialchars($FName,ENT_QUOTES) . "'/></td></tr>";
    echo "<tr><td align='right'>Last Name:</td><td><input type='text' name='peepLName' size='15' value='" . htmlspecialchars($LName,ENT_QUOTES) . "'/></td></tr>";     
    echo "<tr><td align='right'>Address:</td><td><input type='text' name='peepAddress' size='25' value='" . htmlspecialchars($Address,ENT_QUOTES) . "'/></td></tr>";     
    echo "<tr><td align='right'>City:</td><td><input type='text' name='peepCity' size='15' value='" . htmlspecialchars($City,ENT_QUOTES) . "'/> ";
    echo "<tr><td align='right'>State:</td><td><input type='text' name='peepState' size='2' value='" . htmlspecialchars($State,ENT_QUOTES) . "'/>";     
    echo "<tr><td align='right'>Zipcode:</td><td><input type='text' name='peepZipcode' size='25' value='" . htmlspecialchars($Zipcode,ENT_QUOTES) . "'/></td></tr>";       
    echo "<tr><td align='right'>Phone 1:</td><td><input type='text' name='peepPhone1' size='15' value='"  . htmlspecialchars($Phone1,ENT_QUOTES) . "'/></td></tr>";
    echo "<tr><td align='right'>Phone 2:</td><td><input type='text' name='peepPhone2' size='15' value='"  . htmlspecialchars($Phone2,ENT_QUOTES) . "'/></td></tr>";
    echo "<tr><td align='right'>Email 1:</td><td><input type='text' name='peepEmail1' size='40' value='"  . htmlspecialchars($Email1,ENT_QUOTES) . "'/></td></tr>";
    echo "<tr><td align='right'>Email 2:</td><td><input type='text' name='peepEmail2' size='40' value='"  . htmlspecialchars($Email2,ENT_QUOTES) . "'/></td></tr>";
    
    echo "<tr><td align='right'>Spiritual Gifts:</td><td><textarea name='peepSpiritualGifts' cols='40' rows='2'>" . htmlspecialchars($SpiritualGifts,ENT_QUOTES) . "</textarea></td></tr>";
    echo "<tr><td align='right'>Recovery Area:</td><td><textarea name='peepRecoveryArea' cols='40' rows='2'>" . htmlspecialchars($RecoveryArea,ENT_QUOTES) . "</textarea></td></tr>";
    echo "<tr><td align='right'>Recovery Since:</td><td><input type='text' name='peepRecoverySince' size='15' value='" . htmlspecialchars($RecoverySince,ENT_QUOTES) . "'/></td></tr>";
    echo "<tr><td align='right'>CR Since:</td><td><input type='text' name='peepCRSince' size='15' value='" . htmlspecialchars($CRSince,ENT_QUOTES) . "'/></td></tr>";
    echo "<tr><td align='right'>Areas Served:</td><td><textarea name='peepAreasServed' cols='40' rows='4'>" . htmlspecialchars($AreasServed,ENT_QUOTES) . "</textarea></td></tr>";
    echo "<tr><td align='right'>Joy Areas:</td><td><textarea name='peepJoyAreas' cols='40' rows='4'>" . htmlspecialchars($JoyAreas,ENT_QUOTES) . "</textarea></td></tr>";
    echo "<tr><td align='right'>Reasons To Serve:</td><td><textarea name='peepReasonsToServe' cols='40' rows='5'>" . htmlspecialchars($ReasonsToServe,ENT_QUOTES) . "</textarea></td></tr>";
    echo "</table>";
    echo "<table border='0'><tr><td colspan='3'></td></tr>";   // opens the section
    echo "<tr><td valign='top'><table border='3'><tr><td>";             // border around interests
    echo "<table border='0'>";                     //table formatting interests
    echo "<tr><td colspan='2' align='center'><strong>Areas of Interest</strong></td></tr>";
    echo "<tr><td colspan='2'><tr><td><hr/></td></tr>";
    if ($FellowshipTeam == "0"){
        echo "<tr><td align='right'>Fellowship Team (setup, snacks,...):</td><td><input type='checkbox' name='peepFellowshipTeam'></td></tr>";
    }else{
        echo "<tr><td align='right'>Fellowship Team (setup, snacks, etc.):</td><td><input type='checkbox' name='peepFellowshipTeam' checked></td></tr>";
    }
    if ($PrayerTeam == "0"){
        echo "<tr><td align='right'>Prayer Team:</td><td><input type='checkbox' name='peepPrayerTeam'></td></tr>";
    }else{
        echo "<tr><td align='right'>Prayer Team:</td><td><input type='checkbox' name='peepPrayerTeam' checked></td></tr>";
    }
    if ($NewcomersTeam == "0"){
        echo "<tr><td align='right'>Newcomers Team:</td><td><input type='checkbox' name='peepNewcomersTeam'></td></tr>";
    }else{
        echo "<tr><td align='right'>Newcomers Team:</td><td><input type='checkbox' name='peepNewcomersTeam' checked></td></tr>";
    }
    if ($GreetingTeam == "0"){
        echo "<tr><td align='right'>Greeting Team:</td><td><input type='checkbox' name='peepGreetingTeam'></td></tr>";
    }else{
        echo "<tr><td align='right'>Greeting Team:</td><td><input type='checkbox' name='peepGreetingTeam' checked></td></tr>";
    }
    if ($SpecialEventsTeam == "0"){
        echo "<tr><td align='right'>Special Events Team:</td><td><input type='checkbox' name='peepSpecialEventsTeam'></td></tr>";
    }else{
        echo "<tr><td align='right'>Special Events Team:</td><td><input type='checkbox' name='peepSpecialEventsTeam' checked></td></tr>";
    }
    if ($ResourceTeam == "0"){
        echo "<tr><td align='right'>Resource Team:</td><td><input type='checkbox' name='peepResourceTeam'></td></tr>";
    }else{
        echo "<tr><td align='right'>Resource Team:</td><td><input type='checkbox' name='peepResourceTeam' checked></td></tr>";
    }
    if ($SmallGroupTeam == "0"){
        echo "<tr><td align='right'>Small Group Team:</td><td><input type='checkbox' name='peepSmallGroupTeam'></td></tr>";
    }else{
        echo "<tr><td align='right'>Small Group Team:</td><td><input type='checkbox' name='peepSmallGroupTeam' checked></td></tr>";
    }
    if ($StepStudyTeam == "0"){
        echo "<tr><td align='right'>Step Study Team:</td><td><input type='checkbox' name='peepStepStudyTeam'></td></tr>";
    }else{
        echo "<tr><td align='right'>Step Study Team:</td><td><input type='checkbox' name='peepStepStudyTeam' checked></td></tr>";
    } 
    if ($TransportationTeam == "0"){
        echo "<tr><td align='right'>Transportation Team:</td><td><input type='checkbox' name='peepTransportationTeam'></td></tr>";
    }else{
        echo "<tr><td align='right'>Transportation Team:</td><td><input type='checkbox' name='peepTransportationTeam' checked></td></tr>";
    }
    if ($WorshipTeam == "0"){
        echo "<tr><td align='right'>Worship Team:</td><td><input type='checkbox' name='peepWorshipTeam'></td></tr>";
    }else{
        echo "<tr><td align='right'>Worship Team:</td><td><input type='checkbox' name='peepWorshipTeam' checked></td></tr>";
    }
    if ($LandingTeam == "0"){
        echo "<tr><td align='right'>Landing Team:</td><td><input type='checkbox' name='peepLandingTeam'></td></tr>";
    }else{
        echo "<tr><td align='right'>Landing Team:</td><td><input type='checkbox' name='peepLandingTeam' checked></td></tr>";
    }
    if ($CelebrationPlaceTeam == "0"){
        echo "<tr><td align='right'>Celebration Place Team:</td><td><input type='checkbox' name='peepCelebrationPlaceTeam'></td></tr>";
    }else{
        echo "<tr><td align='right'>Celebration Place Team:</td><td><input type='checkbox' name='peepCelebrationPlaceTeam' checked></td></tr>";
    }
    if ($SolidRockTeam == "0"){
        echo "<tr><td align='right'>Solid Rock Team:</td><td><input type='checkbox' name='peepSolidRockTeam'></td></tr>";
    }else{
        echo "<tr><td align='right'>Solid Rock Team:</td><td><input type='checkbox' name='peepSolidRockTeam' checked></td></tr>";
    }
    if ($MealTeam == "0"){
        echo "<tr><td align='right'>Meal Team:</td><td><input type='checkbox' name='peepMealTeam'></td></tr>";
    }else{
        echo "<tr><td align='right'>Meal Team:</td><td><input type='checkbox' name='peepMealTeam' checked></td></tr>";
    }
    if ($CRImen == "0"){
        echo "<tr><td align='right'>CRI: Men:</td><td><input type='checkbox' name='peepCRImen'></td></tr>";
    }else{
        echo "<tr><td align='right'>CRI: Men:</td><td><input type='checkbox' name='peepCRImen' checked></td></tr>";
    }
    if ($CRIwomen == "0"){
        echo "<tr><td align='right'>CRI: Women:</td><td><input type='checkbox' name='peepCRIwomen'></td></tr>";
    }else{
        echo "<tr><td align='right'>CRI: Women:</td><td><input type='checkbox' name='peepCRIwomen' checked></td></tr>";
    }
    echo "</table>";          //closes table formatting interests
    echo "</td></tr></table>"; //closes table around interests
    

    // now create the table on the right with the classes attended listed.
    echo "</td><td></td><td valign='top'><table border='4'><tr><td>";          //table around education
    
    //data section start
    if($mysqli->errno > 0){
        printf("Mysql error number generated: %d", $mysqli->errno);
        exit();
    }
    $query = "SELECT training.tDate, training.tTitle";
            $query = $query . " FROM training INNER JOIN ";
            $query = $query . " trainees ON training.ID = trainees.TID";
            $query = $query . " WHERE trainees.PID='" . $ID . "'";
            $query = $query . " ORDER BY tDate DESC";
    
    //echo $query;
    
    
    //$query = "SELECT * FROM people WHERE Active = '1' order by FName";
    $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
    echo "<table><tr><td align='center'>CR Educational Classes</td></tr>";
    echo "<tr><td><hr/><td></tr>";
    while(list($tDate, $tTitle) = $result->fetch_row()){
            echo "<tr><td>" . $tDate . " " . $tTitle . "</td></tr>";
    }
    echo "</table>";
    //data section stop
    
    
    
    //echo "<table border='0'><tr><td colspan='3'>EDUCATION</td></tr>"; //formatting education data
    //echo "<tr><td colspan='3'><hr/></td></tr>";
    //echo "<tr><td> 3/26/2014</td><td> - </td><td>Pathway to Leadership</td></tr>";
    //echo "</table>";           //closes education formatting
    echo "</td></tr></table>"; //closes the border around education
    echo "</td></tr></table>";           //closes the section table
    
    echo "<br/><table>"; //new table at bottom for notes
    echo "<tr><td align='right'>Notes:</td><td><textarea name='peepNotes' cols='40' rows='5'>" . htmlspecialchars($Notes,ENT_QUOTES) . "</textarea></td></tr>";
    if ($Active == "0"){
        echo "<tr><td align='right'>Active:</td><td><input type='checkbox' name='peepActive'></td></tr>";
    }else{
        echo "<tr><td align='right'>Active:</td><td><input type='checkbox' name='peepActive' checked></td></tr>";
    }
    echo "<tr><td></td><td><input type='submit' value='Ok' size='10'/></td></tr>";
    echo "</table></center>";
    
}

function showPeepsToDraft($TID, $TeamTitle){
    /************************************************
     * displays a list to add to a team
     * **********************************************
     */
       include 'mysql.connect.php';
    echo "<center><h1>Add members to " . $TeamTitle . "</h1>";

   if($mysqli->errno > 0){
       printf("Mysql error number generated: %d", $mysqli->errno);
       exit();
   }
   $query = "SELECT * FROM people order by FName";
   $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
   echo "<div style='text-align:right; padding-right: 20px;'><a href='people.php?Action=NewTrainee&TID=" . $TID . "'>NEW ENTRY</a></div>";
   echo "<table>";
   while(list($ID, $FName, $LName) = $result->fetch_row()){
           echo "<tr><td>";
           echo "<a href='teamAction.php?Action=AddMember&TID=" . $TID . "&PID=". $ID . "'><img src='images/plusbutton.gif'></img></a></td>";
           echo "<td>" . $FName . " " . $LName . "</td></tr>";
   }
   echo "</table>";
}

function showDraftList($TID, $TeamTitle){
    /************************************************
     * only shows people that can be added to team
     * **********************************************
     */
    include 'mysql.connect.php';
    echo "<center><h1>Add members to " . $TeamTitle . "</h1>";

    if($mysqli->errno > 0){
        printf("Mysql error number generated: %d", $mysqli->errno);
        exit();
    }
    //get array of members on the team already
    $query = "SELECT PID FROM team_members WHERE TID = " . $TID;    
    $members = array();
    $team = array();
    $result = $mysqli->query($query);
    while ($row = $result->fetch_array(MYSQLI_ASSOC))
    {
        $members[] = array($row['PID']);

    }
    $MemberCnt = $result->num_rows;
   
   // now skip the people aleady on the team 
   $query = "SELECT * FROM people";
   if ($MemberCnt > 0) {
       //if there are members of the team, omit them in SQL
       $query = $query . " WHERE";
       for($cnt=0;$cnt<$MemberCnt;$cnt++){
           $query = $query . " ID <> " . $members[$cnt][0];
           $testValue = $MemberCnt - 1;
           if ($cnt < $testValue){
                   $query = $query . " AND ";
           }
           
       }
   
   }
   
   $query = $query . " order by FName";

   
   $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
   echo "<div style='text-align:right; padding-right: 20px;'><a href='people.php?Action=NewTrainee&TID=" . $TID . "'>NEW ENTRY</a></div>";
   echo "<table>";
   while(list($ID, $FName, $LName) = $result->fetch_row()){
           echo "<tr><td>";
           echo "<a href='teamAction.php?Action=AddMember&TID=" . $TID . "&PID=". $ID . "&TeamTitle=" . $TeamTitle . "'><img src='images/plusbutton.gif'></img></a></td>";
           echo "<td>" . $FName . " " . $LName . "</td></tr>";
   }
   echo "</table>";
   
    
}


function showPeepsToTrain($TID){
    /************************************************
     * displays a list to add to a training session
     * **********************************************
     */
       include 'mysql.connect.php';
    echo "<center><h1>CR Personnel</h1>";

   if($mysqli->errno > 0){
       printf("Mysql error number generated: %d", $mysqli->errno);
       exit();
   }
   $query = "SELECT * FROM people order by FName";
   $result = $mysqli->query($query, MYSQLI_STORE_RESULT);
   echo "<div style='text-align:right; padding-right: 20px;'><a href='people.php?Action=NewTrainee&TID=" . $TID . "'>NEW ENTRY</a></div>";
   echo "<table>";
   while(list($ID, $FName, $LName) = $result->fetch_row()){
           echo "<tr><td>";
           echo "<a href='ldrAction.php?Action=AddTrainee&TID=" . $TID . "&PID=". $ID . "'><img src='images/plusbutton.gif'></img></a></td>";
           echo "<td>" . $FName . " " . $LName . "</td></tr>";
   }
   echo "</table>";
}

function testSQL($sql){
    /* 
     * this function executes the sql passed in 
     */
   echo "SQL: " . $sql;
}
function executeSQL($sql,$destination){
    /* 
     * this function executes the sql passed in 
     */
   
    $con=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    // Check connection
    if (mysqli_connect_errno($con))
    {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    mysqli_query($con,$sql);

    mysqli_close($con);
    
    destination(307, $destination);
    
}
?>
