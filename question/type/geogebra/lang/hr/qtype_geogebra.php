<?php

/**
 * Strings for component 'qtype_geogebra'
 *
 * @package        qtype
 * @subpackage     geogebra
 * @author         Christoph Stadlbauer <christoph.stadlbauer@geogebra.org>
 * @copyright  (c) International GeoGebra Institute 2014
 * @license        http://www.geogebra.org/license
 */
$string['pluginname'] = 'GeoGebra';
$string['pluginname_link'] = 'question/type/geogebra';$string['addconstraints'] = 'Dodavanje ograničenja (uvjeta) varijablama.';
$string['addmorevarblanks'] = 'Prostor za još {no} varijablu(i)';
$string['answerinvalid'] = 'Povratni niz znakova  je neispravan. To se ne bi trebalo dogoditi.';
$string['answermissing'] = 'Nedostaje odgovor na ulazni zahtjev. Vjerojatno nije postavljen JavaScript u pregledniku ili je došlo do nepoznate greške.';
$string['answervar'] = 'Varijable automatskog ocjenjivanja';
$string['answervar_help'] = 'Kod automatskog ocjenjivanja: naziv GeoGebra logičke varijable čija je vrijednost true ako je učenik (djelomiučno) riješio zadatak. Izračunavaju se sve ocjene za sve logičke  varijable. Zadatak je ispravan ukoliko neka kombinacija premaši 100%, ali treba postojati bar jedna kombinacija kod koje je izračun upravo  100%. Ostavite prazno za ručno ocjenjivanje.';
$string['constraints'] = 'Ograničenja (uvjeti)';
$string['constraints_help'] = 'Imaju li varijale bilo kakva ograničenja, recimo a < b, koja se  ne mogu zadati u postavkama klizača? Razdvojite zarezom. Podržani su: <, <=, >, >=. Trebate li jednakost, morate koristiti istu varijablu kod izrade GeoGebra uratka. Dinamički rasponi, tj. upotreba varijabli za  min/max kod klizača nisu podržani.';
$string['constraintswrongortoohard'] = '{$a->inequalities} je neispravno ili je teško pronaći, pokušali smo (bez rezultata ) {$a->tries} puta u {$a->time} sekundi. Možda ćemo ubuduće koristiti uspješniju metodu...';
$string['feedback'] = 'Povratna informacija ako je varijabla istinita';
$string['feedback_help'] = 'Povratna informacija je automatski preuzeta iz natpisa varijable u GeoGebra datoteci.';
$string['geogebraapplet'] = 'GeoGebra aplet';
$string['getvars'] = 'Get variables which can be randomized from the applet';
$string['ggbfilemissing'] = 'The base64 string in the response is missing. Probably JavaScript isn\'t turned on in the Browser or an unknown error occurred';
$string['ggbturl'] = 'URL ili ID ili GeoGebraTube uradak';
$string['ggbturl_help'] = 'You could either use the share button on GeoGebraTube and copy and paste the link or use the GeoGebraTube repository. The applet and parameters are stored in the database, the applet will not be reloaded from GeoGebraTube unless requested. Just providing the ID or sharing key of the Applet is also supported.';
$string['ggbxmlmissing'] = 'U odgovoru nedostaje XML niz znakova. Vjerojatno u pregledniku nije uključen JavaScript ili je došlo do nepoznate greške';
$string['invalidinequality'] = '{$a} je neispravna';
$string['israndomized'] = 'Postoji li bilo koja varijabla slučajnog odabira?';
$string['loadapplet'] = '(Ponovno)pokreni i prikaži aplet';
$string['loadapplet_help'] = '(Ponovno)učitaj aplet s GeGebraTube i spremi novu verziju s GeoGebraTube u bazu podataka.';
$string['mineqmax'] = 'Min and Max for the randomization aren\'t specified properly for variable {$a}, either you haven\'t specified the slider’s min and max or the element isn\'t a slider at all. You probably have to correct this in your GeoGebra file.';
$string['minplusstepgtmax'] = 'Min plus increment is greater than Max for variable {$a}, you probably have to correct this in your GeoGebra file.';
$string['noappletloaded'] = 'No Applet loaded! Check if URL is correct and if you see an applet after choosing a link or (re)loading the applet';
$string['nofractionsumeq1'] = 'At least one combination of grades must sum to 100%';
$string['pluginname_help'] = 'Questions where the student can solve the question using GeoGebra';
$string['pluginnameadding'] = 'Adding a GeoGebra question';
$string['pluginnameediting'] = 'Editing a GeoGebra question';
$string['pluginnamesummary'] = 'A version of calculated questions which uses GeoGebra to show the question and verify the answer when the quiz is taken.';
$string['randomizedbutnovars'] = 'You have selected that the question should be randomized, but you didn\'t specify any valid variables to be randomized.';
$string['randomizedvar'] = 'Variables to be randomized';
$string['randomizedvar_help'] = 'Variables which should be randomized (comma separated). Use the slider options in GeoGebra to declare Min, Max and Increment. These variables can also be used in the question text by enclosing them with curly braces, for example: {a}';
$string['stepzero'] = 'Increment is 0 for variable {$a}; either you haven\'t specified the slider’s increment or the element isn\'t a slider at all. You probably have to correct this in your GeoGebra file.';
$string['valuecheckedfor'] = 'Boolean Object in GeoGebra which is used to check correctness.';
$string['variablenamewrong'] = 'Varijabla takvog imena nije pronađena u GeoGebra datoteci.';
$string['variableno'] = 'Varijabla {$a}';
$string['variables'] = 'Varijable';
$string['willbereadfromfile'] = 'Will be read from GeoGebra... (see help button)';