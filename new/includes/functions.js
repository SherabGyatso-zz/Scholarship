// JavaScript Document

function check_submit() {
	var error = '';
	vlogin = document.getElementById('login').value;
	vpass = document.getElementById('pass').value;
	vut1 = document.getElementById('ut1').checked;
	vut2 = document.getElementById('ut2').checked;
	vut3 = document.getElementById('ut3').checked;
	
	if(vlogin=='') error=error+'Login field is empty\n';
	if(vpass=='') error=error+'Password field is empty\n';
	if(vut1==false && vut2==false && vut3==false) error=error+'Status was not selected';
	
	if(error!='') alert('Following error(s) occured:\n------------------------------\n'+error + '\n\nVerify form and submit again to continue...'); else document.getElementById('loginform').submit();
}

function popup(loc,w,h) {

	var newdatawin = window.open('pages/'+loc, 'chp', 'menubar=no, toolbar=no, scrollbars=no, resizable=no, width='+w+', height='+h);
	newdatawin.creator = self;
	newdatawin.focus();

}

function sortlist(loc,filtering) {
	sel=document.getElementById('ordertype');
	location = loc + '&order=' + sel.options[sel.selectedIndex].value;
}

function showQuery(nr) {
	document.getElementById('sqlquery'+nr).style.display = 'inline';
	return 0;
}

function hideQuery(nr) {
	document.getElementById('sqlquery'+nr).style.display = 'none';
	return 0;
}

function report_show_year(year,type) {
	document.getElementById('year_1').style.display = 'none';
	document.getElementById('year_2').style.display = 'none';
	document.getElementById('year_3').style.display = 'none';
	document.getElementById('year_4').style.display = 'none';
	if(type==1) document.getElementById('year_5').style.display = 'none';
	document.getElementById('year_'+year).style.display = 'inline';
}

/*function display_txt(obj,id,arg)
{
txt = obj.options[obj.selectedIndex].value;
document.getElementById(id).style.visibility = 'hidden';
document.getElementById(arg).style.visibility = 'hidden';
if ( txt.match(id)) {
document.getElementById(arg).style.visibility = 'visible';
document.getElementById(id).style.visibility = 'visible';
}
}*/