  Moodle 2.3 plugin: EasyOChem Reaction (EasyOReact) question type

IMPORTANT:

Chemaxons Marvin Appets are used in this question type.  We do not provide 
the Marvin applets, so you must apply for a license and download on your own.  
A FreeWeb Licenses is available for educational, non commercial, freely accessible web pages. 

The PHP scripts which accompany the editor are open-source under the 
GNU Public Licence (GPL) - the same licence as Moodle.


INSTALLATION:

This will NOT work with Moodle 2.0 or older, since it uses the new
question API implemented in Moodle 2.1.

1) Install marvin applets on you server by downloading the "Marvin 
for Web Developers" at http://www.chemaxon.com/download/marvin/for-web-developers/  
Install at your web servers root.  If you don't want to or can't install at 
root you can modify the module.js and edit_marvin_form.php to point 
toward your marvin installation. 

2) This is a Moodle question type. It came as a self-contained 
"easyomech" folder which should be placed inside the "question/type" folder
which already exists on your Moodle web server.


Once you have done that, visit your Moodle admin page - the database 
tables should automatically be upgraded to include an extra table for
the EasyOReact question type.


USAGE:

The EasyOChem Reaction question can be used to query students for products, 
reactants and reagents in single step reactions.  You can ask questions such 
as "Please provide the product for the following reaction
?"  or "Please provide reactants for the following reaction?" or "Please provide 
reagents for the following reaction?".
