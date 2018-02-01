<?php
require_once('authenticate.php'); /* for security purposes */
include 'mysql.connect.php';
require_once 'HTML/Table.php';
/*
 * training.php
 * ======================================================
 * this uses pageHead.txt, pageTop.txt & pageBottom.txt
 */

require_once("classPage.php");

$page = new Page();
print $page->getTop();
echo "<center><h1>Leadership Meetings</h1>";

if($mysqli->errno > 0){
    printf("Mysql error number generated: %d", $mysqli->errno);
    exit();
}


$query = "SELECT training.ID, training.tDate, training.tTitle, training.tLocation, training.tInstructor1,
    training.tInstructor2, training.tNotes FROM training ORDER BY training.tDate";

$meetings = array();

$result = $mysqli->query($query);

while ($row = $result->fetch_array(MYSQLI_ASSOC))
{
    $meetings[] = array($row['ID'], $row['tDate'], $row['tTitle'], $row['tLocation'], $row['tInstructor1'], $row['tInstructor2'], $row['tNotes']);
    
}

for($cnt=0;$cnt<$result->num_rows;$cnt++){
    $mtg[$cnt][0] = "&nbsp;<a href='ldrForm.php?ID=" . $meetings[$cnt][0] . "'>" . $meetings[$cnt][1] . "  </a>&nbsp;"; /* Date */
    $mtg[$cnt][1] = "&nbsp;&nbsp;" . $meetings[$cnt][2] . "&nbsp;&nbsp;"; /* Title */
    $mtg[$cnt][2] = "&nbsp;&nbsp;" . $meetings[$cnt][3] . "&nbsp;"; /* Location */
}

// create an array of table attributes
$attributes = array('border' => '1', 'id' => 'trainingdata', 'align' => 'center', 'text-align' => 'center');

//create the table object
$table = new HTML_Table($attributes);

//set the headers
$table->setHeaderContents(0,0, "Date");
$table->setHeaderContents(0,1, "Title");
$table->setHeaderContents(0,2, "Location");

//cycle through the array to produce the table data

for($rownum = 0; $rownum < count($mtg); $rownum++){
    for($colnum = 0; $colnum < 3; $colnum++){
        $table->setCellContents($rownum+1, $colnum, $mtg[$rownum][$colnum]);
    }
}
$table->altRowAttributes(1,null, array("class"=>"alt"));

/* add a link to enter a new meeting record */
echo "<div style='text-align:right; padding-right: 20px;'><a href='ldrForm.php?Action=New'>NEW ENTRY</a></div>";

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
