<?php
require_once('authenticate.php'); /* used for security purposes */
include 'mysql.connect.php';
require_once 'HTML/Table.php';
/*
 * meetings.php
 * ======================================================
 * this uses pageHead.txt, pageTop.txt & pageBottom.txt
 */

require_once("classPage.php");

$page = new Page();
print $page->getTop();
$past = $_GET["PAST"];
if ($past){
    echo "<center><h1>Past Meetings</h1>";
}else{
    echo "<center><h1>Future Meetings</h1>";
}


if($mysqli->errno > 0){
    printf("Mysql error number generated: %d", $mysqli->errno);
    exit();
}
$tmpToday = date("Y-m-d");
if ($past){
    
    $query = "SELECT meetings.ID, meetings.MtgDate, meetings.MtgType, meetings.MtgTitle,
    meetings.MtgAttendance, people.FName, people.LName 
    FROM meetings 
    INNER JOIN people ON meetings.MtgPresenter = people.ID 
    
    WHERE meetings.MtgDate <= '" . $tmpToday . "' ORDER BY meetings.MtgDate DESC";
}else{
    $query = "SELECT meetings.ID, meetings.MtgDate, meetings.MtgType, meetings.MtgTitle,
    meetings.MtgAttendance, people.FName, people.LName, worshipLeader.FName As WFName, worshipLeader.LName as WLName
    FROM meetings 

    INNER JOIN people ON meetings.MtgPresenter = people.ID 

    INNER JOIN people worshipLeader ON (meetings.MtgWorship = worshipLeader.ID)

    WHERE meetings.MtgDate >= '". $tmpToday . ' ORDER BY meetings.MtgDate ASC
}
//SELECT meetings.ID, meetings.MtgDate, meetings.MtgType, meetings.MtgTitle,
    //meetings.MtgAttendance, people.FName, people.LName, worshipLeader.FName As WFName, worshipLeader.LName as WLName
    //FROM meetings 

    //INNER JOIN people ON meetings.MtgPresenter = people.ID 

    //INNER JOIN people worshipLeader ON (meetings.MtgWorship = worshipLeader.ID)

    //WHERE meetings.MtgDate >= '2017-10-11' ORDER BY meetings.MtgDate ASC
 /** 
 * 
 * 
 */



$meetings = array();

$result = $mysqli->query($query);

while ($row = $result->fetch_array(MYSQLI_ASSOC))
{
    //$meetings[] = array($row['ID'], $row['MtgDate'], $row['MtgType'], $row['MtgTitle'], $row['FName'], $row['LName'], $row['MtgAttendance']);
    $meetings[] = array($row['ID'], $row['MtgDate'], $row['MtgType'], $row['MtgTitle'], $row['FName'], $row['LName'], $row['MtgAttendance'],
        $row['WFName'], $row['WLName']);
}

for($cnt=0;$cnt<$result->num_rows;$cnt++){
    $mtg[$cnt][0] = "&nbsp;<a href='mtgForm.php?ID=" . $meetings[$cnt][0] . "'>&nbsp;" . $meetings[$cnt][1] . "</a>&nbsp;"; /* Date */
    $mtg[$cnt][1] = "&nbsp;" . $meetings[$cnt][6] . "&nbsp;"; /* Attendance */
    $mtg[$cnt][2] = "&nbsp;" . $meetings[$cnt][2] . "&nbsp;"; /* Type */
    $mtg[$cnt][3] = "&nbsp;" . $meetings[$cnt][3] . "&nbsp;"; /* Title */
    $mtg[$cnt][4] = "&nbsp;" . $meetings[$cnt][4] . " " . $meetings[$cnt][5] . "&nbsp"; /* name */
    $mtg[$cnt][5] = "&nbsp;" . $meetings[$cnt][7] . " " . $meetings[$cnt][8] . "&nbsp"; /* worship leader */
}

// create an array of table attributes
$attributes = array('border' => '1', 'id' => 'trainingdata', 'align' => 'center', 'text-align' => 'center');

//create the table object
$table = new HTML_Table($attributes);

//set the headers
$table->setHeaderContents(0,0, "Date");
$table->setHeaderContents(0,1, "#");
$table->setHeaderContents(0,2, "Type");
$table->setHeaderContents(0,3, "Title");
$table->setHeaderContents(0,4, "Name");
$table->setHeaderContents(0,5, "Worship");

//cycle through the array to produce the table data

for($rownum = 0; $rownum < count($mtg); $rownum++){
    for($colnum = 0; $colnum < 5; $colnum++){
        $table->setCellContents($rownum+1, $colnum, $mtg[$rownum][$colnum]);
    }
}
$table->altRowAttributes(1,null, array("class"=>"alt"));

/* add a link to enter a new meeting record */
echo "<div style='text-align:right; padding-right: 20px;'><a href='mtgForm.php'>NEW ENTRY</a><br/>";
/* add link to old or new meetings */
if ($past){
    echo "<a href='meetings.php'>mtg plans</a>";
}else{
    echo "<a href='meetings.php?PAST=1'>mtg history</a>";
}
echo "</div>";
//output the data
echo "<div>";
echo $table->toHTML();
/**** print the records returned  */
printf("There were %d meetings found", $result->num_rows);
echo "</div>";

/* display the bottom of the page */
print $page->getBottom();

$result->free();
?>
