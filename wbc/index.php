<?php

/*
 * Index.php
 * ======================================================
 * this uses pageHead.txt, pageTop.txt & pageBottom.txt
 */
require_once('authenticate.php'); /* this is used for security purposes */
require_once("classPage.php");

$page = new Page();
print $page->getTop();
print <<<EOF
<div id="mainContent" style="padding:15px;"><center>
<img src='images/cr_splash_590x250.jpg'></img><br/><br/>
This web application is designed explicitly for Wynnbrook Baptist Church<br/>
Church Celebrate Recovery ministry. For further information regarding<br/>
this site or its contents please contact <a href='mailto:dano@dcolombo.com'>Dano</a>
</center></div> <!-- end main content -->

EOF;
print $page->getBottom();
?>
