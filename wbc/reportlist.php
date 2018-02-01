<?php
require_once('authenticate.php'); /* for security purposes */
/*
 * reportlist.php
 * ======================================================
 * this uses pageHead.txt, pageTop.txt & pageBottom.txt
 */

require_once("classPage.php");

$page = new Page();
print $page->getTop();
print <<<EOF

<div id="mainContent" style="padding:15px;"><center>
<h2>Report Listing</h2>
This is the listing of current reports, grouped in categories: To get specific 
information not provided in the reports below, contact Dano<br/><br/>
<hr/>
<h1>People's Interests</h1>
<table border='2'><tr><td>
<table id='reporttable'>
<tr>
    <td id='reportcell'><a style='text-decoration:none;' href='reports.php?Report=FellowshipInterest'>Fellowship</td>
    <td id='reportcell'><a style='text-decoration:none;' href='reports.php?Report=PrayerInterest'>Prayer</td>
    <td id='reportcell'><a style='text-decoration:none;' href='reports.php?Report=NewcomersInterest'>Newcomers (101)</td>
</tr>
<tr>
    <td id='reportcell'><a style='text-decoration:none;' href='reports.php?Report=GreetingInterest'>Greeting</td>
    <td id='reportcell'><a style='text-decoration:none;' href='reports.php?Report=SpecialEventsInterest'>Special Events</td>
    <td id='reportcell'><a style='text-decoration:none;' href='reports.php?Report=ResourceInterest'>Resources</td>
<tr>
    <td id='reportcell'><a style='text-decoration:none;' href='reports.php?Report=SmallGroupInterest'>Open Share</td>
    <td id='reportcell'><a style='text-decoration:none;' href='reports.php?Report=StepStudyInterest'>Step-Study</td>
    <td id='reportcell'><a style='text-decoration:none;' href='reports.php?Report=TransportationInterest'>Transportation</td>
<tr>
    <td id='reportcell'><a style='text-decoration:none;' href='reports.php?Report=WorshipInterest'>Worship</td>
    <td id='reportcell'><a style='text-decoration:none;' href='reports.php?Report=LandingInterest'>The Landing</td>
    <td id='reportcell'><a style='text-decoration:none;' href='reports.php?Report=CelebrationPlaceInterest'>Celebration Place</td>
</tr>
<tr>
    <td id='reportcell'><a style='text-decoration:none;' href='reports.php?Report=SolidRockInterest'>Solid Rock</td>
    <td id='reportcell'><a style='text-decoration:none;' href='reports.php?Report=MealInterest'>Meal</td>
    <td></td>
</tr>
</table>
</td></tr></table>
<hr/>
<h1>The Numbers...</h1>
<table border='2'><tr><td>
<table id='reporttable'>
<tr>
    <td id='reportcell'><a style='text-decoration:none;' href='reports.php?Report=MealCnt'>Meal</td>
    <td id='reportcell'><a style='text-decoration:none;' href='reports.php?Report=NurseryCnt'>Nursery</td>
    <td id='reportcell'><a style='text-decoration:none;' href='reports.php?Report=101Cnt'>Newcomers (101)</td>
</tr>
<tr>
    <td id='reportcell'><a style='text-decoration:none;' href='reports.php?Report=CSCnt'>Celebration Place</td>
    <td id='reportcell'><a style='text-decoration:none;' href='reports.php?Report=LandingCnt'>The Landing</td>
    <td id='reportcell'></td>
</tr>
</table>
</td></tr></table>
</center></div>

EOF;
print $page->getBottom();
?>