<?php

/*
 * WBC CR Solution page class
 */

class Page
{
    public $type = "default";   //type of WBC CR page
    public $title = "WBC CR Solution";  //browser menu bar
    public $titleExtra = ""; //additional pages with different names
    
    public function getTop() {
        $output = "";
        $output .= $this->_getDocType();
        $output .= $this->_getHtmlOpen();
        $output .= $this->_getHead();
        $output .= file_get_contents("pageTop.txt");
        return $output;
    }  // end function getTop()
    
      protected function _getDocType($doctype = "html5"){
          if($doctype == "html5"){
              $dtd = "<!DOCTYPE html>";
          }
          return $dtd . "\n";
      } //end function _getDocType()
      
      protected function _getHtmlOpen($lang = "en-us"){
          if($lang == "en-us"){
              $htmlopen = "<html lang=\"en\">";
          }
          return $htmlopen . "\n";
      } //end function _getHtmlOpen()
      
      protected function _getHead(){
          $output = "";
          $output .= file_get_contents("pageHead.txt");
          if ($this->titleExtra != ""){
              $title = $title->titleExtra . "|" . $this->title;
          }
          else{
              $title = $this->title;
          }
          $output .= "<title>" . $title . "</title>";
          $output .= "</head>";
          return $output;
      } //end function _getHead()
      
      public function getBottom(){
          return file_get_contents("pageBottom.txt");
      } //ed function getBottom()    
}  //end class Page
?>
