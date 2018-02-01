/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var vTitleField = document.getElementById('mtgTitle');
var vTitleHint = document.getElementById('titlehint');

vTitleField.onfocus = function(){
    vTitleHint.innerHTML = "Enter lesson or testimony name";
}// onfocus
vTitleField.onblur = function(){
    vTitleHint.innerHTML = "";
}
var vTitle = document.getElementById('mtgTitle').value;

