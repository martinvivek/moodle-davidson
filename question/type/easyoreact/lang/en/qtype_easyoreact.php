<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'qtype_easyoreact', language 'en', branch 'MOODLE_20_STABLE'
 *
 * @package    qtype
 * @subpackage easyoreact
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


$string['caseproducts'] = 'Products';
$string['casereactants'] = 'Reactants';
$string['casereagents'] = 'Reagents';
$string['caseproductsorreactants'] = 'Do you want the students to provide products, reactants or reagents?';


$string['configeasyoreactoptions'] = 'The path of your marvin installation relative to your web root.  (e.g. If your moodle is installed at /var/www/moodle and you install your marvin at /var/www/marvin then you should use the default /marvin)';
$string['easyoreact_options'] = 'Path to Marvin Applet installation';
$string['addmoreanswerblanks'] = 'Blanks for {no} More Answers';
$string['answermustbegiven'] = 'You must enter an answer if there is a grade or feedback.';
$string['answerno'] = 'Answer {$a}';
$string['pluginname'] = 'Reaction Completion';
$string['pluginname_help'] = '<ul>
<li>Draw a complete reaction in the MarvinSketch applet below.</li>
<li>Use reaction arrow to separate your reactants from your products.</li>
<li>Draw reagents on top of arrow.</li>
<li>Choose whether you want the student to provide products, reactants or reagents. (This will automatically be stripped from your reactions when viewed by a student).</li>
</ul>
<p>You can ask questions like;</p>
<ul>
<li>Draw the products of the following reaction?</li>
<li>Show the reactant in the following reaction?</li>
<li>Provide the reagents required to achieve the following transformation?&lt;/ul&gt;</li>
</ul>';
$string['pluginname_link'] = 'question/type/easyoreact';
$string['pluginnameadding'] = 'Adding a Reaction question';
$string['pluginnameediting'] = 'Editing Reaction question';
$string['pluginnamesummary'] = 'Student must draw the reactants, reagents or products of a reaction that you predefine.  Ask questions like;<ul><li>Show the reactant in the following reaction?</li><li>Provide the reagents required to achieve the following transformation?</li><li>Provide the major product of the following reaction?</li></ul>';
$string['enablejava'] = 'Tried but failed to load Marvinsketch editor. You have not got a JAVA runtime environment working in your browser. You will need one to attempt this question.';
$string['enablejavaandjavascript'] = 'Loading Marvinsketch editor.... If this message does not get replaced by the Marvin editor then you have not got javascript and a JAVA runtime environment working in your browser.';
$string['filloutoneanswer'] = '<b><ul>
<li>Draw a <b>complete</b> reaction in the MarvinSketch applet below.</li>
<li>Use reaction arrow to separate your reactants from your products.</li>
<li>Draw reagents on top and/or bottom of arrow.</li>
<li>Click the "Insert from editor" button to add the reaction code to the answer box.</li>
<li>Choose whether you want the student to provide products, reactants or reagents. (This will automatically be stripped from your reactions when viewed by a student).</li>
<li>Incorrect answer feedback: You can add incorrect answer (and feedback) in the other answer fields.</li>
</ul></b>
<p>You can ask questions like;</p>
<ul>
<li>Draw the products of the following reaction?</li>
<li>Show the reactant in the following reaction?</li>
<li>Provide the reagents required to achieve the following transformation?&lt;/ul&gt;</li>
</ul>';
$string['filloutanswers'] = 'Use the Marvinsketch Molecular editor to create the answers, then press the "Insert from editor" buttons to insert the SMILES code into the answer boxes';
$string['insertfromeditor'] = 'Insert from editor';
$string['javaneeded'] = 'To use this page you need a Java-enabled browser. Download the latest Java plug-in from {$a}.';
$string['instructions'] = 'The ChemAxon ("mrv") representation of your model must be stored in the following field in order to be graded:';
$string['answer'] = 'Answer: {$a}';
$string['youranswer'] = 'Your answer: {$a}';
$string['correctansweris'] = 'The correct answer is: {$a}.';
$string['correctanswers'] = 'Instructions';
$string['notenoughanswers'] = 'This type of question requires at least {$a} answers';
$string['pleaseenterananswer'] = 'Please enter an answer.';
$string['easyoreacteditor'] = 'MarvinSketch Editor';
$string['author'] = 'Question type courtesy of Carl LeBlond, Indiana University of Pennsylvania';
$string['insert'] = 'Insert from editor';
