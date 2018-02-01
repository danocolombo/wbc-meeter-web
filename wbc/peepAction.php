<?php
include 'mtgRedirects.php';
include 'mysql.connect.php';
/*
 * peepAction.php
 */

$Action = $_GET['Action'];
$PID = $_GET['PID'];

switch ($Action){
    case "Update":
        /*********************************
         * this is updating the ID
         * *****************************/
         
        updatePeepRecord($PID);
        exit;
        
    case "Add":
        /*********************************
         * add person to database
         * *******************************
         */
        addPerson();
        exit;
    
    case "AddPerson":
        /*************************************
         * add a new peep to database
         * ***********************************
         */
        addPerson();
        exit;
        
    case "RemoveTrainee":
        /*********************************
         * add person to training list
         * *******************************
         */
        $TID = $_GET["TID"];  // Training ID
        $PID = $_GET["PID"];  // People ID
        dropTraineeFromClass($TID, $PID);
        exit;
    default:
        echo "not sure what to do with " . $Action;
        exit;
}
function updatePeepRecord(){
    //======================================
    // updatePeepRecord update the ID passed
    // in and send back to people.php
    //======================================
    /* need the following $link command to use the escape_string function */
    $link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)
            OR die(mysql_error());
    
    $PID = $_GET["PID"];
    $FName = $_POST["peepFName"];
    $LName = $_POST["peepLName"];
    $Phone1 = $_POST["peepPhone1"];
    $Phone2 = $_POST["peepPhone2"];
    $Email1 = $_POST["peepEmail1"];
    $Email2 = $_POST["peepEmail2"];
    $Address = $_POST["peepAddress"];
    $City = $_POST["peepCity"];
    $State = $_POST["peepState"];
    $Zipcode = $_POST["peepZipcode"];
    $SpiritualGifts = $_POST["peepSpiritualGifts"];
    $RecoveryArea = $_POST["peepRecoveryArea"];
    $RecoverySince = $_POST["peepRecoverySince"];
    $CRSince = $_POST["peepCRSince"];
    $AreasServed = $_POST["peepAreasServed"];
    $JoyAreas = $_POST["peepJoyAreas"];
    $ReasonsToServe = $_POST["peepReasonsToServe"];
    $FellowshipTeam = $_POST["peepFellowshipTeam"];
    $PrayerTeam = $_POST["peepPrayerTeam"];
    $NewcomersTeam = $_POST["peepNewcomersTeam"];
    $GreetingTeam = $_POST["peepGreetingTeam"];
    $SpecialEventsTeam = $_POST["peepSpecialEventsTeam"];
    $ResourceTeam = $_POST["peepResourceTeam"];
    $SmallGroupTeam = $_POST["peepSmallGroupTeam"];
    $StepStudyTeam = $_POST["peepStepStudyTeam"];
    $TransportationTeam = $_POST["peepTransportationTeam"];
    $WorshipTeam = $_POST["peepWorshipTeam"];
    $LandingTeam = $_POST["peepLandingTeam"];
    $CelebrationPlaceTeam = $_POST["peepCelebrationPlaceTeam"];
    $SolidRockTeam = $_POST["peepSolidRockTeam"];
    $MealTeam = $_POST["peepMealTeam"];
    $CRImen = $_POST["peepCRImen"];
    $CRIwomen = $_POST["peepCRIwomen"];
    
    $Notes = $_POST["peepNotes"];
    $Active = $_POST["peepActive"];

    $sql = "UPDATE people SET FName='" . mysql_real_escape_string($FName) . "',";
    $sql = $sql . " LName='" . mysql_real_escape_string($LName) .  "',";
    $sql = $sql . " Phone1='" . mysql_real_escape_string($Phone1) . "',";
    $sql = $sql . " Phone2='" . mysql_real_escape_string($Phone2) . "',";
    $sql = $sql . " Email1='" . mysql_real_escape_string($Email1) . "',";
    $sql = $sql . " Email2='" . mysql_real_escape_string($Email2) . "',";
    $sql = $sql . " Address='" . mysql_real_escape_string($Address) . "',";
    $sql = $sql . " City='" . mysql_real_escape_string($City) . "',";
    $sql = $sql . " State='" . mysql_real_escape_string($State) . "',";
    $sql = $sql . " Zipcode='" . mysql_real_escape_string($Zipcode) . "',";   
    if ($Active == "on"){
        $sql = $sql . " Active='1', Notes='";
    }else{
        $sql = $sql . " Active='0', Notes='";
    }
    $sql = $sql . $Notes . "' WHERE ID ='" . $PID . "'";
    /* --------------------------------------------------
     * going to run multiple queries because of possible
     * SQL length challenges
     * --------------------------------------------------
     */
    $con=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    // Check connection
    if (mysqli_connect_errno($con))
    {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    mysqli_query($con,$sql);
    mysqli_close($con);
    
    /* ---------------------------------------------------
     * now build another SQL statement
     * ---------------------------------------------------
     */
    $sql = "UPDATE people SET SpiritualGifts='" . mysql_real_escape_string($SpiritualGifts) . "',";
    $sql = $sql . " RecoveryArea='" . mysql_real_escape_string($RecoveryArea) .  "',";
    $sql = $sql . " RecoverySince='" . mysql_real_escape_string($RecoverySince) . "',";
    $sql = $sql . " CRSince='" . mysql_real_escape_string($CRSince) . "',";
    $sql = $sql . " AreasServed='" . mysql_real_escape_string($AreasServed) . "',";
    $sql = $sql . " JoyAreas='" . mysql_real_escape_string($JoyAreas) . "',";
    $sql = $sql . " ReasonsToServe='" . mysql_real_escape_string($ReasonsToServe) . "'";
    $sql = $sql . " WHERE ID ='" . $PID . "'";
    $con=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    // Check connection
    if (mysqli_connect_errno($con))
    {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    mysqli_query($con,$sql);
    mysqli_close($con);
    
    /* ---------------------------------------------------
     * now build another SQL statement
     * ---------------------------------------------------
     */
    $sql = "UPDATE people SET";
    if ($FellowshipTeam == "on"){
        $sql = $sql . " FellowshipTeam='1', ";
    }else{
        $sql = $sql . " FellowshipTeam='0', ";
    }
    if ($PrayerTeam == "on"){
        $sql = $sql . " PrayerTeam='1', ";
    }else{
        $sql = $sql . " PrayerTeam='0', ";
    }
    if ($NewcomersTeam == "on"){
        $sql = $sql . " NewcomersTeam='1', ";
    }else{
        $sql = $sql . " NewcomersTeam='0', ";
    }
    if ($GreetingTeam == "on"){
        $sql = $sql . " GreetingTeam='1', ";
    }else{
        $sql = $sql . " GreetingTeam='0', ";
    }
    if ($SpecialEventsTeam == "on"){
        $sql = $sql . " SpecialEventsTeam='1', ";
    }else{
        $sql = $sql . " SpecialEventsTeam='0', ";
    }
    if ($ResourceTeam == "on"){
        $sql = $sql . " ResourceTeam='1', ";
    }else{
        $sql = $sql . " ResourceTeam='0', ";
    }
    if ($SmallGroupTeam == "on"){
        $sql = $sql . " SmallGroupTeam='1', ";
    }else{
        $sql = $sql . " SmallGroupTeam='0', ";
    }
    if ($StepStudyTeam == "on"){
        $sql = $sql . " StepStudyTeam='1', ";
    }else{
        $sql = $sql . " StepStudyTeam='0', ";
    }
    if ($TransportationTeam == "on"){
        $sql = $sql . " TransportationTeam='1', ";
    }else{
        $sql = $sql . " TransportationTeam='0', ";
    }
    if ($WorshipTeam == "on"){
        $sql = $sql . " WorshipTeam='1', ";
    }else{
        $sql = $sql . " WorshipTeam='0', ";
    }
    if ($LandingTeam == "on"){
        $sql = $sql . " LandingTeam='1', ";
    }else{
        $sql = $sql . " LandingTeam='0', ";
    }
    if ($CelebrationPlaceTeam == "on"){
        $sql = $sql . " CelebrationPlaceTeam='1', ";
    }else{
        $sql = $sql . " CelebrationPlaceTeam='0', ";
    }
    if ($SolidRockTeam == "on"){
        $sql = $sql . " SolidRockTeam='1', ";
    }else{
        $sql = $sql . " SolidRockTeam='0', ";
    }
    if ($MealTeam == "on"){
        $sql = $sql . " MealTeam='1',";
    }else{
        $sql = $sql . " MealTeam='0',";
    }
    if ($CRImen == "on"){
        $sql = $sql . " CRImen='1',";
    }else{
        $sql = $sql . " CRImen='0',";
    }
    if ($CRIwomen == "on"){
        $sql = $sql . " CRIwomen='1'";
    }else{
        $sql = $sql . " CRIwomen='0'";
    }
    $sql = $sql . " WHERE ID ='" . $PID . "'";
    
    /* ------------------------------------
     * now head out of here....
     * ------------------------------------
     */
    $tmp = "people.php";
    executeSQL($sql, $tmp);
    //testSQL($sql);
}

function addPerson(){
    //======================================
    // addPerson to DB from people.php
    //======================================
    /* need the following $link command to use the escape_string function */
    $link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)
            OR die(mysql_error());
    $TID = $_GET["TID"];
    $PID = $_GET["PID"];
    $FName = $_POST["peepFName"];
    $LName = $_POST["peepLName"];
    $Phone1 = $_POST["peepPhone1"];
    $Phone2 = $_POST["peepPhone2"];
    $Email1 = $_POST["peepEmail1"];
    $Email2 = $_POST["peepEmail2"];
    $Address = $_POST["peepAddress"];
    $City = $_POST["peepCity"];
    $State = $_POST["peepState"];
    $Zipcode = $_POST["peepZipcode"];
    $SpiritualGifts = $_POST["peepSpiritualGifts"];
    $RecoveryArea = $_POST["peepRecoveryArea"];
    $RecoverySince = $_POST["peepRecoverySince"];
    $CRSince = $_POST["peepCRSince"];
    $AreasServed = $_POST["peepAreasServed"];
    $JoyAreas = $_POST["peepJoyAreas"];
    $ReasonsToServe = $_POST['peepReasonsToServe'];
    $FellowshipTeam = $_POST["peepFellowshipTeam"];
    $PrayerTeam = $_POST["peepPrayerTeam"];
    $NewcomersTeam = $_POST["peepNewcomersTeam"];
    $GreetingTeam = $_POST["peepGreetingTeam"];
    $SpecialEventsTeam = $_POST["peepSpecialEventsTeam"];
    $ResourceTeam = $_POST["peepResourceTeam"];
    $SmallGroupTeam = $_POST["peepSmallGroupTeam"];
    $StepStudyTeam = $_POST["peepStepStudyTeam"];
    $TransportationTeam = $_POST["peepTransportationTeam"];
    $WorshipTeam = $_POST["peepWorshipTeam"];
    $LandingTeam = $_POST["peepLandingTeam"];
    $CelebrationPlaceTeam = $_POST["peepCelebrationPlaceTeam"];
    $SolidRockTeam = $_POST["peepSolidRockTeam"];
    $MealTeam = $_POST["peepMealTeam"];
    $CRImen = $_POST["peepCRImen"];
    $CRIwomen = $_POST["peepCRIwomen"];
    $Notes = $_POST["peepNotes"];
    $Active = $_POST["peepActive"];

    $sql = "INSERT INTO people (FName, LName, Phone1, Phone2, Email1, Email2,";
    $sql = $sql . " Address, City, State, Zipcode,";
    $sql = $sql . " Active, Notes) VALUES (";
    $sql = $sql . "'" . mysql_real_escape_string($FName) . "',";
    $sql = $sql . "'" . mysql_real_escape_string($LName) . "',";
    $sql = $sql . "'" . mysql_real_escape_string($Phone1) . "',";
    $sql = $sql . "'" . mysql_real_escape_string($Phone2) . "',";
    $sql = $sql . "'" . mysql_real_escape_string($Email1) . "',";
    $sql = $sql . "'" . mysql_real_escape_string($Email2) . "',";
    $sql = $sql . "'" . mysql_real_escape_string($Address) . "',";
    $sql = $sql . "'" . mysql_real_escape_string($City) . "',";
    $sql = $sql . "'" . mysql_real_escape_string($State) . "',";
    $sql = $sql . "'" . mysql_real_escape_string($Zipcode) . "',";
    if ($Active == "on"){
        $sql = $sql . " Active='1', Notes='";
    }else{
        $sql = $sql . " Active='0', Notes='";
    }
    $sql = $sql . $Notes . "')";
    // -------------------------------
    // determine where to go...
    // -------------------------------
    switch ($_GET['Origin']){
        case "grpForm":
            // send them back to the grpForm where they came from
            $tmp = "grpForm.php?Action=Edit&GID=" . $_GET['GID'] . "&MID=" . $_GET['MID'];
            break;
        case "ldrForm":
            // send them back to the trnForm where they came from
            $tmp = "ldrForm.php?Action=Edit&ID=" . $_GET['ID'];
            break;
        case "mtgForm":
            if ($_GET['ID'] > 0){
                $tmp = "mtgForm.php?ID=" . $_GET['ID'];
            }else{
                $tmp = "mtgForm.php";
            }
            break;
        default:
            if ($TID > 0){
                $tmp = "people.php?Action=TraineeList&TID=" . $TID;
            }else{
                $tmp = "people.php";
            }
            break;
    }
    
    
    executeSQL($sql, $tmp);
    //testSQL($sql);
}




function addPeepToDatebase(){
    
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


function testSQL($sql){
    /* 
     * this function executes the sql passed in 
     */
   echo "SQL: " . $sql;
}

function bookTraineeInClass($TID, $PID){
    $link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)
            OR die(mysql_error());
     
    
    $sql = "INSERT INTO trainees (TID, PID) VALUES ('";
    $sql = $sql . $TID . "', '";
    $sql = $sql . $PID . "')";
    
    $tmp = "ldrForm.php?ID=" . $TID;
    executeSQL($sql, $tmp);
    // testSQL($sql);
}

function dropTraineeFromClass($TID, $PID){
    $link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD)
            OR die(mysql_error());
     
    
    $sql = "DELETE FROM trainees WHERE TID='" . $TID . "' AND PID='" . $PID . "'";
    
    $tmp = "ldrForm.php?ID=" . $TID;
    executeSQL($sql, $tmp);
    // testSQL($sql);
}

?>
