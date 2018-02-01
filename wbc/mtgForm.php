<?php
include 'mysql.connect.php';
define('DEBUG','0');
/*
 * mtgForm.php
 * ==============================================
 * displays for to enter and edit meetings
 */
require_once("classPage.php");

/*-----------------------------------------------
 * display the top of the form
 *---------------------------------------------*/
$page = new Page();
print $page->getTop();
$MID = $_GET["ID"];
if ($MID > 0){
    /*------------------------------------------------
     * this section will get the meeting ID from the
     * url and display it for the user
     ===============================================*/
    //if($mysqli->errno > 0){
    if (mysqli_connect_errno()){    
        die("Database connection failed: " .
        mysqli_connect_error() .
        " (" . mysqli_connect_error() . ")");
    }
    $query = "SELECT * FROM meetings WHERE ID = " . $MID;

    $mtg = array();

    $result = $mysqli->query($query);

    while ($row = $result->fetch_array(MYSQLI_ASSOC))
    {
        $mtg[] = array($row['ID'], $row['MtgDate'], $row['MtgType'],
            $row['MtgTitle'], $row['MtgPresenter'], $row['MtgAttendance'], 
            $row['MtgNotes'],
            $row['NurseryCnt'], $row['ChildrenCnt'], $row['YouthCnt'],
            $row['DinnerCnt'], $row['Donations'],
            $row['MtgWorship'], $row['MtgMeal'],);

    }
    /* start the form */
    printf ("<form id='mtgForm' action='mtgAction.php?Action=Update&ID=%s' method='post'>",$mtg[0][0]);
    
    echo "<center><h2>EDIT MEETING</h2></center>";
    echo "<center>";
    echo "<table border='0'>";
    echo "<tr><td colspan='2' align='right' border='1'>" . $mtg[0][0] . "</td></tr>";
    
    //echo "<tr><td align='right'>Date:</td><td><input type='text' name='mtgDate' size='15' 
    //    value='" . $mtg[0][1] . "' />";
    //echo " (YYYY-MM-DD)</td></tr>";
    echo "<tr><td align='right'>Date:&nbsp;</td><td>"
        . "&nbsp;<input type='text' name='mtgDate' id='date' size='10' value='" . $mtg[0][1] . "' required />
        <a href='javascript:viewcalendar()' style='text-decoration:none'>
        <img src='images/cal-icon.gif' vspace='0' align='ABSBOTTOM'/></a></td></tr>";
    
    echo "<tr><td align='right'>Type:&nbsp;</td><td>";
        if($mtg[0][2] == "Lesson"){
            echo "<input type='radio' id='mtgType' name='mtgType' value='Lesson' checked='checked'>&nbsp;&nbsp;Lesson</input>&nbsp;&nbsp;
                <input type='radio' id='mtgType' name='mtgType' value='Testimony'>&nbsp;&nbsp;Testiomony</input></td></tr>";
        }else{
            echo "<input type='radio' id='mtgType' name='mtgType' value='Lesson'>Lesson</input>&nbsp;&nbsp;
                <input type='radio' id='mtgType' name='mtgType' value='Testimony' checked='checked'>Testiomony</input></td></tr>";
        }
             
        
    echo "<tr><td align='right'>Title:&nbsp;</td><td><input type='text' id='mtgTitle' name='mtgTitle' size='40' value='" .$mtg[0][3] . "'/>" 
            . "<br/><span id='titlehint' class='hint'></span></td></tr>";
    echo "<tr><td align='right'>Host:&nbsp;</td>";
    echo "<td align='left'><select id='mtgPresenter' name='mtgPresenter'>";
    /*==========================================================
     * need to load array with people names to put in dropdown
     =========================================================*/
    if($mysqli->errno > 0){
        printf("Mysql error number generated: %d", $mysqli->errno);
        exit();
    }
    $query = "SELECT ID, FName, LName FROM people WHERE Active = '1' ORDER BY FName";
    $peeps = array();
    $result = $mysqli->query($query);
    while ($row = $result->fetch_array(MYSQLI_ASSOC))
    {
        $peeps[] = array($row['ID'], $row['FName'], $row['LName'], );

    }
    for($cnt=0;$cnt<$result->num_rows;$cnt++){
        $peep[$cnt][0] = $peeps[$cnt][0]; /* ID */
        // if there is a last name, concatenate
        if (sizeof($peeps[$cnt][2] >0)){
            $peep[$cnt][1] = $peeps[$cnt][1] . " " . $peeps[$cnt][2]; /* name */
        }else{
            $peep[$cnt][1] = $peeps[$cnt][1]; /* first name */
        }
    }    
    
    for($cnt=0;$cnt<$result->num_rows;$cnt++){
        echo "<option value='" . $peep[$cnt][0] . "'";
        if($mtg[0][4] == $peep[$cnt][0]){
            echo " selected = 'selected'";
        }
        echo ">" . $peep[$cnt][1] . "</option>";
    }
    echo "</select>&nbsp;&nbsp;<a href='people.php?Action=New&Origin=mtgForm_Edit&MID=" . $MID . "'><img valign='bottom' src='images/plusbutton.gif'></img></a></td></tr>";
    // --------------------------------------------------
    // get the number of people that attended open-share
    // --------------------------------------------------  
    // SELECT SUM(Attendance) AS Cnt FROM groups WHERE MtgID=$MID AND Gender < 2
    // 
    $sql = "SELECT SUM(Attendance) AS Cnt FROM groups WHERE MtgID=" . $MID . " AND Gender < 2";
    $result = $mysqli->query($sql);
    if ($result->num_rows>0){
        //output data for row returned
        $rw = $result->fetch_assoc();
        $OpenShareCnt = $rw["Cnt"];
        
    }
    //Worship Info
    // need to get the list of people that are on the worship team
    // 
    // ===============================================================
    /*==========================================================
     * need to load array with people names to put in dropdown
     =========================================================*/
    $query = "SELECT ID, FName, LName FROM people WHERE Active = '1' AND 
        WorshipTeam = '1' ORDER BY FName";
    $weeps = array();
    $result = $mysqli->query($query);
    while ($row = $result->fetch_array(MYSQLI_ASSOC))
    {
        $weeps[] = array($row['ID'], $row['FName'], $row['LName'], );

    }
    for($cnt=0;$cnt<$result->num_rows;$cnt++){
        $weep[$cnt][0] = $weeps[$cnt][0]; /* ID */
        if (sizeof($weeps[$cnt][2] >0)){
            $weep[$cnt][1] = $weeps[$cnt][1] . " " . $weeps[$cnt][2]; /* name */
        }else{
            $weep[$cnt][1] = $weeps[$cnt][1]; /* first name */
        }
    } 
    // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    // DEBUG DEBUG DEBUG DEBUG DEBUG DEBUG v DEBUG DEBUG DEBUG DEBUG DEBUG
    // DEBUG DEBUG v DEBUG DEBUG DEBUG DEBUG DEBUG DEBUG DEBUG DEBUG DEBUG
    // DEBUG DEBUG DEBUG DEBUG DEBUG DEBUG DEBUG DEBUG DEBUG DEBUG DEBUG
    // DEBUG
    //echo "<tr><td colspan='2'>";
    //for($cnt=0;$cnt<$result->num_rows;$cnt++){
    //    echo "cnt:". $cnt . " - MtgWorship;" . $weeps[$cnt][0] . " " . $weeps[$cnt][1] . "<br/>";
    //}
    //echo "\$mtg[0][12]:" . $mtg[0][12] . "<br/>";
    //echo "</td></tr>";
    // DEBUG DEBUG DEBUG DEBUG DEBUG DEBUG v DEBUG DEBUG DEBUG DEBUG DEBUG
    // DEBUG DEBUG v DEBUG DEBUG DEBUG DEBUG DEBUG DEBUG DEBUG DEBUG DEBUG
    // DEBUG DEBUG DEBUG DEBUG DEBUG DEBUG DEBUG DEBUGe DEBUG DEBUG DEBUG
    // @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    echo "<tr><td align='right'>Worship:&nbsp;</td>";
    echo "<td align='left'><select id='mtgWorship' name='mtgWorship'>";
    $otherFlag = false;
    //// load the selection box...
    for($cnt=0;$cnt<$result->num_rows;$cnt++){
        echo "<option value='" . $weeps[$cnt][0] . "'";
        if($mtg[0][12] == $weeps[$cnt][0]){
            echo " selected = 'selected'";
        }
        echo ">" . $weeps[$cnt][1] . " " . $weeps[$cnt][2] . "</option>";
        
    }
    // if we did not get otherListed, slide it in
    /****
    if($otherFlag == false){
        if ($mtg[0][12] == 0){
            //default entry, convert it to other
            echo "<option value='7' selected='selected'>other (video, etc.)</option>";
        }else{
            echo "<option value='7'>other (video, etc.)</option>";
        }
    }
     **/
    if($mtg[0][12] == '7'){
        echo "<option value='7' selected ='selected'>other (video, etc.)</option>";
    }else{
        echo "<option value='7'>other (video, etc.)</option>";
    }
    
    echo "</select>";
    echo "</td></tr>";
    // ===============================================================
    // End Worship selection
    
    //==============================
    // ATTENDANCE
    //==============================
    echo "<tr><td align='right'>Attendance:&nbsp;</td><td><select id='mtgAttendance' name='mtgAttendance'>";
    for ($cnt=0;$cnt<125;$cnt++){
        echo "<option value='" . $cnt . "'";
        if ($cnt == $mtg[0][5]){
            echo " selected = 'selected'";
        }        
        echo ">" . $cnt . "</option>";
        
    }
    echo "</select>";
    if ($OpenShareCnt > 0 ){
        $pct = $OpenShareCnt/$mtg[0][5]*100;
        echo "&nbsp; &nbsp; &nbsp; Open-Share: " . $OpenShareCnt . "&nbsp;&nbsp;<u>". number_format((float)$pct, 0, '.', '') . "%</u>";
    }
    echo "</td></tr>";
    // DONATIONS
    echo "<tr><td align='right'>Donations:&nbsp;</td>"
        . "<td><input type='text' id='mtgDonations' name='mtgDonations' size='6' align='right' value='"
            . money_format('%=*(.2n', $mtg[0][11]) . "' />";
    
    echo "</td></tr>";
    // MEAL INFO
    echo "<tr><td align='right'>Meal:&nbsp;</td>"
        . "<td><input type='text' id='mtgMeal' name='mtgMeal' size='30' align='right' value='"
            . $mtg[0][13] . "' />";
    echo "</td></tr>";
    // DINNER COUNT
    echo "<tr><td align='right'>Dinner:&nbsp;</td><td><select id='mtgDinnerCnt' name='mtgDinnerCnt'>";
    for ($cnt=0;$cnt<125;$cnt++){
        echo "<option value='" . $cnt . "'";
        if ($cnt == $mtg[0][10]){
            echo " selected = 'selected'";
        }        
        echo ">" . $cnt . "</option>";
        
    }
    echo "</select></td></tr>";
    // NURSERY
    echo "<tr><td align='right'>Nursery:&nbsp;</td><td><select id='mtgNurseryCnt' name='mtgNurseryCnt'>";
    for ($cnt=0;$cnt<16;$cnt++){
        echo "<option value='" . $cnt . "'";
        if ($cnt == $mtg[0][7]){
            echo " selected = 'selected'";
        }        
        echo ">" . $cnt . "</option>";
    }
    echo "</select></td></tr>";
    // CHILDREN
    echo "<tr><td  hidden='true' align='right'>Children:&nbsp;</td><td><select  hidden='true' name='mtgChildrenCnt'>";
    for ($cnt=0;$cnt<16;$cnt++){
        echo "<option value='" . $cnt . "'";
        if ($cnt == $mtg[0][8]){
            echo " selected = 'selected'";
        }        
        echo ">" . $cnt . "</option>";
    }
    echo "</select></td></tr>";
    // YOUTH
    echo "<tr><td align='right'  hidden='true'>Youth:&nbsp;</td><td><select id=mtgYouthCnt' name='mtgYouthCnt'  hidden='true'>";
    for ($cnt=0;$cnt<16;$cnt++){
        echo "<option value='" . $cnt . "'";
        if ($cnt == $mtg[0][9]){
            echo " selected = 'selected'";
        }        
        echo ">" . $cnt . "</option>";
    }
    echo "</select></td></tr>";
    echo "<tr><td align='right' valign='top'>Notes:&nbsp;</td><td><textarea id='mtgNotes' name='mtgNotes' rows='5' cols='40'>" . $mtg[0][6] . "</textarea></td></tr>";
    
    echo "<tr><td></td><td><input type='submit' value='Ok' size='10'/></td></tr>";
    echo "</table>";
    
    /******************************************
     * now display the group information
     * ****************************************
     */
    
    //$query = "SELECT groups.ID, groups.Gender, groups.Title, people.FName, people.LName, groups.Location, groups.Attendance
    //    FROM groups INNER JOIN people ON groups.FacID
    //    = people.ID WHERE groups.MtgID = '" . $MID . "' ORDER BY groups.Gender, groups.Title DESC";
    /* echo "<div>" . $query . "</div>"; */
    
    $query = "SELECT groups.ID, groups.Gender, groups.Title, fac.FName as fFName, fac.LName as fLName, cofac.FName as cFName, cofac.LName as cLName, 
        groups.Location, groups.Attendance
        FROM groups INNER JOIN people fac ON groups.FacID = fac.ID
        INNER JOIN people cofac ON groups.CoFacID = cofac.ID
        WHERE groups.MtgID = '" . $MID . "' ORDER BY groups.Gender, groups.Title DESC";
    
    $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    if($mysqli->errno > 0){
        printf("Mysql error number generated: %d", $mysqli->errno);
        exit();
    }
    $meetings = array();

    $result = $mysqli->query($query);

    while ($row = $result->fetch_array(MYSQLI_ASSOC))
    {
        $meetings[] = array($row['ID'], $row['Gender'], $row['Title'], $row['fFName'], $row['fLName'], $row['cFName'], $row['cLName'], $row['Location'], $row['Attendance']);

    }
    
    echo "<div style='float:right'><a href='grpForm.php?ID=" . $ID . "&MID=" . $MID . "&Action=New'>New Group</a></div>";
    if (sizeof($meetings) > 0) {
        
        echo "<div><br/><table border='1'>";
        echo "<tr><th></th><th>Title</th><th>Facilitator</th><th>Co-Facilitator</th><th>Location</th><th>#</th></tr>";
        for($cnt=0;$cnt<sizeof($meetings);$cnt++){
            echo "<tr><td valign='center' style='padding:5px'><a href='grpForm.php?GID=" . $meetings[$cnt][0] . "&MID=" . $MID . "&Action=Edit'><img src='images/btnEdit.gif'></img></a></td>";
            echo "<td style='padding:5px'>";
            switch ($meetings[$cnt][1]) {
                case "0":
                    echo "Men's ";
                    break;
                case "1":
                    echo "Women's ";
                    break;
                default:
                    echo "";
                    break;
            }
            
            echo $meetings[$cnt][2] . "</td><td style='padding:10px; text-align:center;'>";
            echo $meetings[$cnt][3] . /*" " . $meetings[$cnt][4] .*/ "</td><td style='padding:10px; text-align:center;'>";
            echo $meetings[$cnt][5] . /*" " . $meetings[$cnt][6] .*/ "</td><td>";
            echo $meetings[$cnt][7] . "</td><td align='center' style='left-padding:5px;right-padding:5px;'>" . $meetings[$cnt][8] . "</td>";
            echo "<td width=15px; alight='right'>
                <a href='mtgAction.php?Action=DeleteGroup&MID=". $MID . "&GID=" . $meetings[$cnt][0] . "'><img src='images/minusbutton.gif'></img></a></td></tr>";
        }  
        echo "</table></div>";
    }else{
        /*--------------------------------------------------------------------
        * no groups loaded for this week. Offer ability to copy from last week
        *-------------------------------------------------------------------*/
        /* echo "<div><b><i>cnt:</i></b>" . sizeof($meetings) . "</div>"; */
        /* THERE ARE NO GROUPS TO DISPLAY */
       
        echo "<br/><br/><a href='mtgAction.php?Action=PreLoadGroups&MID=" . $MID. "'><img src='images/btnGetLastWeek.png'></img></a>";
       
    }
    
    
    

    
    
}else{
    /*==========================================================
     * Display a blank form to enter a new meeting
     ==========================================================*/
    printf ("<form id='mtgForm' action='mtgAction.php?Action=New' method='post'>");
    printf ("<center><h2>New Meeting Entry</h2></center>");
    echo "<center>";
    echo "<table border='0'>";
    echo "<tr><td align='right'>Date:</td><td valign='bottom'><input type='text' name='mtgDate' id='date' size='10'/>
        <a href='javascript:viewcalendar()' style='text-decoration:none'>
        <img src='images/cal-icon.gif' vspace='0' align='ABSBOTTOM'/></a></td></tr>";
    
    
    
    echo "<tr><td align='right'>Type:</td><td>
        <input type='radio' id='mtgType' name='mtgType' value='Lesson' checked='checked'>Lesson</input>&nbsp;&nbsp;
        <input type='radio' id='mtgType' name='mtgType' value='Testimony'>Testiomony</input></td></tr>";
    echo "<tr><td align='right'>Title:</td><td><input type='text' id='mtgTitle' name='mtgTitle' size='40' required /></td></tr>";
    /*==========================================================
     * need to load array with people names to put in dropdown
     =========================================================*/
    if($mysqli->errno > 0){
        printf("Mysql error number generated: %d", $mysqli->errno);
        exit();
    }
    $query = "SELECT ID, FName, LName FROM people WHERE Active = '1' ORDER BY FName";
    $peeps = array();
    $result = $mysqli->query($query);
    while ($row = $result->fetch_array(MYSQLI_ASSOC))
    {
        $peeps[] = array($row['ID'], $row['FName'], $row['LName'], );

    }
    for($cnt=0;$cnt<$result->num_rows;$cnt++){
        $peep[$cnt][0] = $peeps[$cnt][0]; /* ID */
        if (sizeof($peeps[$cnt][2] >0)){
            $peep[$cnt][1] = $peeps[$cnt][1] . " " . $peeps[$cnt][2]; /* name */
        }else{
            $peep[$cnt][1] = $peeps[$cnt][1]; /* first name */
        }
    }
    
    /*####################
     * BACK TO THE FORM
     */
    echo "<tr><td align='right'>Host:</td>";
    echo "<td align='left'><select id='mtgPresenter' name='mtgPresenter'>";
    for($cnt=0;$cnt<$result->num_rows;$cnt++){
        echo "<option value='" . $peep[$cnt][0] . "'";
        if($mtg[0][4] == $peep[$cnt][0]){
            echo " selected = 'selected'";
        }
        echo ">" . $peep[$cnt][1] . "</option>";
    }
    echo "</select>&nbsp;&nbsp;<a href='people.php?Action=New&Origin=mtgForm_Default'><img src='images/plusbutton.gif'></img></a></td></tr>";
    //Worship Info
    // need to get the list of people that are on the worship team
    /*==========================================================
     * need to load array with people names to put in dropdown
     =========================================================*/
    $query = "SELECT ID, FName, LName FROM people WHERE Active = '1' AND 
        WorshipTeam = '1' ORDER BY FName";
    $weeps = array();
    $result = $mysqli->query($query);
    while ($row = $result->fetch_array(MYSQLI_ASSOC))
    {
        $weeps[] = array($row['ID'], $row['FName'], $row['LName'], );

    }
    for($cnt=0;$cnt<$result->num_rows;$cnt++){
        $weep[$cnt][0] = $weeps[$cnt][0]; /* ID */
        if (sizeof($weeps[$cnt][2] >0)){
            $weep[$cnt][1] = $peeps[$cnt][1] . " " . $peeps[$cnt][2]; /* name */
        }else{
            $weep[$cnt][1] = $peeps[$cnt][1]; /* first name */
        }
    }    
    echo "<tr><td align='right'>Worship:</td>";
    echo "<td align='left'><select id='mtgWorship' name='mtgWorship'>";
    $otherFlag = false;
    //// load the selection box...
    for($cnt=0;$cnt<$result->num_rows;$cnt++){
        echo "<option value='" . $weep[$cnt][0] . "'";
        if($mtg[0][12] == $weep[$cnt][0]){
            echo " selected = 'selected'";
        }
        echo ">" . $weep[$cnt][1] . "</option>";
    }
    // if we did not get otherListed, slide it in
    if($otherFlag == false){
        if ($mtg[0][12] == 0){
            //default entry, convert it to other
            echo "<option value='7' selected='selected'>other (video, etc.)</option>";
        }else{
            echo "<option value='7'>other (video, etc.)</option>";
        }
    }
    echo "</select>";
    echo "</td></tr>";
    // ===============================================================
    // End Worship selection
        
    echo "<tr><td align='right'>Attendance:</td><td><select id='mtgAttendance' name='mtgAttendance'>";
    for ($cnt=0;$cnt<125;$cnt++){
        echo "<option value='" . $cnt . "'>" . $cnt . "</option>";
    }
    echo "</select></td></tr>";
    // DONATIONS
    echo "<tr><td align='right'>Donations:</td><td>
    	<input type='text' id='mtgDonations' name='mtgDonations' size='6'/></td></tr>";
    // MEAL INFO
    echo "<tr><td align='right>Meal</td></td>
        <input type='text' id='mtgMeal' name='mtgMeal' size='30'/></td></tr>";
    // DINNER ATTENDEE COUNT
    echo "<tr><td align='right'>Dinner:</td><td><select id='mtgDinnerCnt' name='mtgDinnerCnt'>";
    for ($cnt=0;$cnt<125;$cnt++){
        echo "<option value='" . $cnt . "'>" . $cnt . "</option>";
    }
    echo "</select></td></tr>";
    // NURSERY
    echo "<tr><td align='right'>Nursery:</td><td><select id='mtgNurseryCnt' name='mtgNurseryCnt'>";
    for ($cnt=0;$cnt<16;$cnt++){
        echo "<option value='" . $cnt . "'>" . $cnt . "</option>";
    }
    echo "</select></td></tr>";
    // CHILDREN
    echo "<tr><td align='right'  hidden='true'>Children:</td><td><select  hidden='true' name='mtgChildrenCnt'>";
    for ($cnt=0;$cnt<16;$cnt++){
        echo "<option value='" . $cnt . "'>" . $cnt . "</option>";
    }
    echo "</select></td></tr>";
    // YOUTH
    echo "<tr><td align='right'  hidden='true'>Youth:</td><td><select id='mtgYouthCnt' name='mtgYouthCnt' hidden='true'>";
    for ($cnt=0;$cnt<16;$cnt++){
        echo "<option value='" . $cnt . "'>" . $cnt . "</option>";
    }
    echo "</select></td></tr>";
    echo "<tr><td align='right' valign='top'>Notes:</td><td><textarea id='mtgNotes' name='mtgNotes' rows='5' cols='40'></textarea></td></tr>";
    echo "<tr><td></td><td><input type='submit' value='Ok' size='10'/></td></tr>";
    echo "</table>";
}

/* close the database */
mysqli_close($mysqli);

echo "</form>";
/*-----------------------------------------------
 * java script helper
 */
echo "<script src='src/mtgFormHelper.js'></script>";
/*-----------------------------------------------
 * display the bottom of the form
 *---------------------------------------------*/
print $page->getBottom();

?>
