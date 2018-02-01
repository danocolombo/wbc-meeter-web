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
    case "peepEdit":
        /***************************************************
         * new version of the people form
         ***************************************************/
        peepViewer("Edit","","", $PID);
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

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//~~~~~~~~~~                     ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//          |     peepViewer     |
//~~~~~~~~~~                     ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
function peepViewer($action, $origin, $destination, $ID){    
    include 'mysql.connect.php';
    $PID = $_GET["PID"];
    if($mysqli->errno > 0){
        printf("Mysql error number generated: %d", $mysqli->errno);
        exit();
    }
    switch ($action){
        case "peepNew":
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
        case "peepEdit":
            
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
    /*-----------------------------------------------------'
     * new form layout
     ------------------------------------------------------*/
    
    echo "<!doctype html>";
    echo "<html><head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
    echo "<title>WBC CR Servant Info</title>";
    echo "<link href='peepStyle.css' rel='stylesheet' type='text/css'>";

    //The following script tag downloads a font from the Adobe Edge Web Fonts server for use within the web page. We recommend that you do not modify it.-->
    echo "<script>var __adobewebfontsappname__='dreamweaver'</script><script src='http://use.edgefonts.net/montserrat:n4:default;source-sans-pro:n2:default.js' type='text/javascript'></script>";
    echo "</head>";

    echo "<body>";
    echo "<form id='mtgForm' action='" . $dest . "' method='post'>";
    //echo "<div id='personal-info' align='center'>";
    //echo "<h2>CR Personnel Form</h2>";
    //echo "First Name:<input type='text' name='peepFName' size='15' value='" . htmlspecialchars($FName,ENT_QUOTES) . "'/> <br/>";
    //echo "Last Name:<input type='text' name='peepLName' size='15' value='" . htmlspecialchars($LName,ENT_QUOTES) . "'/><br/>";
    //echo "<br/></div>";
    echo "<header>";
    echo "<div class='profileLogo'>"; 
    // Profile logo. Add a img tag in place of <span>. -->
    //echo "<p class='logoPlaceholder'>image file here</p>";
    echo "</div>";
    echo "<div class='profilePhoto'>"; 
    //<!-- Profile photo --> 
    echo "<img src='images/servants/bgordy.png' width='150' alt='Bubba'> </div>";
    //<!-- Identity details -->
    echo "<section class='profileHeader'>";
    echo "<h1>" . $FName . " " . $LName . "</h1>";
    echo "<h3>REALLY AWESOME WEB DESIGNER</h3>";
    echo "<hr>";
    echo "<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in.</p>";
    echo "</section>";
    // <!-- Links to Social network accounts -->
    echo "<aside class='socialNetworkNavBar'>";
    echo "<div class='socialNetworkNav'>"; 
    //<!-- Add a Anchor tag with nested img tag here --> 
    echo "<img src='AboutPageAssets/images/social.png' alt='sample'> </div>";
    echo "<div class='socialNetworkNav'>";
    //<!-- Add a Anchor tag with nested img tag here --> 
    echo "<img src='AboutPageAssets/images/social.png'  alt='sample'> </div>";
    echo "<div class='socialNetworkNav'>"; 
    //<!-- Add a Anchor tag with nested img tag here --> 
    echo "<img src='AboutPageAssets/images/social.png'  alt='sample'> </div>";
    echo "<div class='socialNetworkNav'>";
    //<!-- Add a Anchor tag with nested img tag here --> 
    echo "<img src='AboutPageAssets/images/social.png'  alt='sample'> </div>";
    echo "</aside>";
    echo "</header>";
    //<!-- content -->
    echo "<section class='mainContent'> ";
    //<!-- Contact details -->
    echo "<section class='section1'>";
    echo "<h2 class='sectionTitle'>Content Holder 1</h2>";
    echo "<hr class='sectionTitleRule'>";
    echo "<hr class='sectionTitleRule2'>";
    echo "<div class='section1Content'>";
    echo "<p><span>Email :</span> johndoe@email.com</p>";
    echo "<p><span>Website :</span> johndoe.com</p>";
    echo "<p><span>Phone :</span> (123)456 - 789000</p>";
    echo "<p><span>Address :</span> Anytown, Anycountry</p>";
    echo "</div></section>";
    //<!-- Previous experience details -->
    echo "<section class='section2'>";
    echo "<h2 class='sectionTitle'>Content Holder 2</h2>";
    echo "<hr class='sectionTitleRule'>";
    echo "<hr class='sectionTitleRule2'>";
    //<!-- First Title & company details  -->
    echo "<article class='section2Content'>";
    echo "<h2 class='sectionContentTitle'>Title & Company</h2>";
    echo "<h3 class='sectionContentSubTitle'>Position / Date - Year</h3>";
    echo "<p class='sectionContent'> Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. </p>";
    echo "</article>";
    //<!-- Second Title & company details  -->
    echo "<article class='section2Content'>";
    echo "<h2 class='sectionContentTitle'> Title & Company</h2>";
    echo "<h3 class='sectionContentSubTitle'>Position / Date - Year</h3>";
    echo "<p class='sectionContent'> Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. Nam liber tempor cum soluta nobis eleifend option congue nihil imperdiet doming id quod mazim placerat facer possim assum. </p>";
    echo "</article>";
    //<!-- Replicate the above Div block to add more title and company details --> 
    echo "</section>";
    //<!-- Links to expore your past projects and download your CV -->
    echo "<aside class='externalResourcesNav'>";
    echo "<div class='externalResources'> <a href='#' title='Download CV Link'>DOWNLOAD CV</a> </div>";
    echo "<span class='stretch'></span>";
    echo "<div class='externalResources'><a href='#' title='Behance Link'>BEHANCE</a> </div>";
    echo "<span class='stretch'></span>";
    echo "<div class='externalResources'><a href='#' title='Github Link'>GITHUB</a> </div>";
    echo "</aside>";
    echo "</section>";
    echo "</form>";
    //echo "<footer>";
    //echo "<hr>";
    //echo "<p class='footerDisclaimer'>2014  Copyrights - <span>All Rights Reserved</span></p>";
    //echo "<p class='footerNote'>John Doe - <span>Email me</span></p>";
    //echo "</footer>";
    //</body>
    //</html>








}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//~~~~~~~~~~~~~~~~~~   END peepViewer() function ~~~~~~~~~~~~~~~~~~~~
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

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
   if ($_SESSION["username"] == 'dano'){
       // give the ability to get to admin settings
       echo "<p align='right'><a href='adMain.php'><img src='images\icon-gear.png'/></a></p>";
   }
   
}
function showAllPeople() {
   include 'mysql.connect.php';
   echo "<center><h1>CR Personnel</h1>";

   if($mysqli->errno > 0){
       printf("Mysql error number generated: %d", $mysqli->errno);
       exit();
   }
   $query = "SELECT * FROM people order by FName";
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
    /*-----------------------------------------------------'
     * new form layout
     ------------------------------------------------------*/
    echo "<form id='mtgForm' action='" . $dest . "' method='post'>";
    echo "<div id='personal-info' align='center'>";
    echo "<h2>CR Personnel Form</h2>";
    echo "First Name:<input type='text' name='peepFName' size='15' value='" . htmlspecialchars($FName,ENT_QUOTES) . "'/> <br/>";
    echo "Last Name:<input type='text' name='peepLName' size='15' value='" . htmlspecialchars($LName,ENT_QUOTES) . "'/><br/>";
    echo "<br/></div>";
    
    /*---------- collapse contact info ----------*/
    /***** 
    echo "<button data-toggle='collapse' data-target='#contact-info'>Collapsible</button>";
    echo "<div id='contact-info' class='collapse'>";
    echo "Address:<input type='text' name='peepAddress' size='25' value='" . htmlspecialchars($Address,ENT_QUOTES) . "'/><br/>";     
    echo "City:<input type='text' name='peepCity' size='15' value='" . htmlspecialchars($City,ENT_QUOTES) . "'/><br/> ";
    echo "State:<input type='text' name='peepState' size='2' value='" . htmlspecialchars($State,ENT_QUOTES) . "'/><br/>";     
    echo "Zipcode:<input type='text' name='peepZipcode' size='25' value='" . htmlspecialchars($Zipcode,ENT_QUOTES) . "'/><br/>";       
    echo "Phone 1:<input type='text' name='peepPhone1' size='15' value='"  . htmlspecialchars($Phone1,ENT_QUOTES) . "'/><br/>";
    echo "Phone 2:<input type='text' name='peepPhone2' size='15' value='"  . htmlspecialchars($Phone2,ENT_QUOTES) . "'/><br/>";
    echo "Email 1:<input type='text' name='peepEmail1' size='40' value='"  . htmlspecialchars($Email1,ENT_QUOTES) . "'/><br/>";
    echo "Email 2:<input type='text' name='peepEmail2' size='40' value='"  . htmlspecialchars($Email2,ENT_QUOTES) . "'/><br/>";
    echo "<br/></div>";
    ****/
    
    /*****
    echo "<button data-toggle='collapse' data-target='#contact-interests'>Areas of Interest</button>";
    echo "<div id='contact-interests' class='collapse'>";   
    echo "Spiritual Gifts:<textarea name='peepSpiritualGifts' cols='40' rows='2'>" . htmlspecialchars($SpiritualGifts,ENT_QUOTES) . "</textarea><br/>";
    echo "Recovery Area:<textarea name='peepRecoveryArea' cols='40' rows='2'>" . htmlspecialchars($RecoveryArea,ENT_QUOTES) . "</textarea><br/>";
    echo "Recovery Since:<input type='text' name='peepRecoverySince' size='15' value='" . htmlspecialchars($RecoverySince,ENT_QUOTES) . "'/><br/>";
    echo "CR Since:<input type='text' name='peepCRSince' size='15' value='" . htmlspecialchars($CRSince,ENT_QUOTES) . "'/><br/>";
    echo "Areas Served:<textarea name='peepAreasServed' cols='40' rows='4'>" . htmlspecialchars($AreasServed,ENT_QUOTES) . "</textarea><br/>";
    echo "Joy Areas:<textarea name='peepJoyAreas' cols='40' rows='4'>" . htmlspecialchars($JoyAreas,ENT_QUOTES) . "</textarea><br/>";
    echo "Reasons To Serve:<textarea name='peepReasonsToServe' cols='40' rows='5'>" . htmlspecialchars($ReasonsToServe,ENT_QUOTES) . "</textarea><br/>";
    echo "<br/></div>";
    *******/
    
    /*******
    echo "<button data-toggle='collapse' data-target='#contact-teams'>Teams</button>";
    echo "<div id='contact-teams' class='collapse'>";     
    echo "<strong>Areas of Interest</strong><br/>";

    echo "this will be the loop through the teams based on team member<br/>";
    
    /********************
    if ($FellowshipTeam == "0"){
        echo "Fellowship Team (setup, snacks,...):<input type='checkbox' name='peepFellowshipTeam'><br/>";
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
    *****/
    
    // now create the table on the right with the classes attended listed.
    //echo "</td><td></td><td valign='top'><table border='4'><tr><td>";          //table around education
    
    //data section start
    /***
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
    
    echo "<center>";
    echo "<button data-toggle='collapse' data-target='#contact-participation'>Participation</button>";
    echo "<div id='contact-participation' class='collapse'>";
    
    echo "<H2>CR Participation</H2>";
    
    while(list($tDate, $tTitle) = $result->fetch_row()){
            
            //echo "&nbsp;&nbsp;" . $tDate . " " . $tTitle . "<br/>";
    }
    
    echo "<br/></div>";
    echo "</center>";
    ***/
    //data section stop  
    //echo "<table border='0'><tr><td colspan='3'>EDUCATION</td></tr>"; //formatting education data
    //echo "<tr><td colspan='3'><hr/></td></tr>";
    //echo "<tr><td> 3/26/2014</td><td> - </td><td>Pathway to Leadership</td></tr>";
    //echo "</table>";           //closes education formatting
    //echo "</td></tr></table>"; //closes the border around education
    //echo "</td></tr></table>";           //closes the section table   
    //echo "<br/><table>"; //new table at bottom for notes
    
    echo "<center><table><tr><td valign='top'>Notes</td>";
    echo "<td><textarea name='peepNotes' cols='40' rows='5'>" . htmlspecialchars($Notes,ENT_QUOTES) . "</textarea>";
    echo "</td></tr>";
    echo "<tr><td style='padding:10pt;' colspan='2' align='center'>";
    if ($Active == "0"){
        echo "Active:<input type='checkbox' name='peepActive'>";
    }else{
        echo "Active:<input type='checkbox' name='peepActive' checked>";
    }
    echo "</td></tr>";
    echo "<tr><td colspan='2' align='center'><input type='submit' value='Ok' size='10'/>";
    echo "</td></tr></table><br/></center>";
    
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
