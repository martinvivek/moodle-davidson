
function getSmiles(textfieldid) {
	document.getElementById(textfieldid).value = document.getElementById('EASYOREACT' + textfieldid).smiles();
}


/*
function getSmilesEdit(buttonname){
    var buttonnumber= buttonname.slice(7,-1);
	textfieldid = 'id_answer_' + buttonnumber;
	document.getElementById(textfieldid).value = document.getElementById('JME').smiles();
}
*/


///modified by crl for easyoreact sketch
function getSmilesEdit(buttonname, format){
    var buttonnumber = buttonname.slice(7,-1);
    var s = document.MSketch.getMol(format);
//	s = unix2local(s); // Convert "\n" to local line separator
	textfieldid = 'id_answer_' + buttonnumber;
	document.getElementById(textfieldid).value = s;
}






/*
function exportMol(format) {
	if(document.MSketch != null) {
		var s = document.MSketch.getMol(format);
		s = unix2local(s); // Convert "\n" to local line separator
		document.MolForm.MolTxt.value = s;
	} else {
		alert("Cannot import molecule:\n"+
		      "no JavaScript to Java communication in your browser.\n");
	}
}

*/




M.qtype_easyoreact={
    insert_structure_into_applet : function(){
		var textfieldid = 'id_answer_0';
		if(document.getElementById(textfieldid).value != '') {
		
		var s = document.getElementById(textfieldid).value;
		document.MSketch.setMol(s, 'cxsmiles');
		}

	}
}
