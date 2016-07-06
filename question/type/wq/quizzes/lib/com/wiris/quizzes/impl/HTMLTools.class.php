<?php

class com_wiris_quizzes_impl_HTMLTools {
	public function __construct() {
		;
	}
	public function setItemSeparator($sep) {
		$this->separator = (($sep === null) ? "," : $sep);
	}
	public function unitTestDev() {
		haxe_Log::trace("compare(a,a1)=" . _hx_string_rec(com_wiris_quizzes_impl_HTMLTools::compareStrings("a", "a1"), ""), _hx_anonymous(array("fileName" => "HTMLTools.hx", "lineNumber" => 2287, "className" => "com.wiris.quizzes.impl.HTMLTools", "methodName" => "unitTestDev")));
		$a = new _hx_array(array());
		$a->push("a");
		$a->push("a1");
		$s = $this->sortIterator($a->iterator());
		$i = null;
		$r = "";
		{
			$_g1 = 0; $_g = $s->length;
			while($_g1 < $_g) {
				$i1 = $_g1++;
				$r = $r . " " . $s[$i1];
				unset($i1);
			}
		}
		haxe_Log::trace("sort({a1,a})" . $r, _hx_anonymous(array("fileName" => "HTMLTools.hx", "lineNumber" => 2298, "className" => "com.wiris.quizzes.impl.HTMLTools", "methodName" => "unitTestDev")));
	}
	public function unitTestTables() {
		$variables = new Hash();
		$t = new Hash();
		$t->set("T", "{1,2,3,4}");
		$t->set("M", "{a,b,c,d}");
		$t->set("r", "10");
		$variables->set(com_wiris_quizzes_impl_MathContent::$TYPE_TEXT, $t);
		$m = new Hash();
		$m->set("a", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mfenced open=\"[\" close=\"]\"><mrow><mn>1</mn><mo>,</mo><mn>2</mn><mo>,</mo><mn>3</mn></mrow></mfenced></math>");
		$m->set("b", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mfenced open=\"{\" close=\"}\"><mrow><mfenced open=\"{\" close=\"}\"><mrow><mn>1</mn><mo>,</mo><mn>1</mn></mrow></mfenced><mo>,</mo><mfenced open=\"{\" close=\"}\"><mrow><mn>2</mn><mo>,</mo><mn>4</mn></mrow></mfenced><mo>,</mo><mfenced open=\"{\" close=\"}\"><mrow><mn>3</mn><mo>,</mo><mn>9</mn></mrow></mfenced></mrow></mfenced></math>");
		$variables->set(com_wiris_quizzes_impl_MathContent::$TYPE_MATHML, $m);
		$html = new _hx_array(array("<table><tr><td></table>", "<div><table class=\"table\"><tr class=\"tr1\"><td class=\"td1\">Header</td></tr><tr class=\"tr2\"><td class=\"td2\">#M</td></tr></table>", "<div><table><thead><tr><th>Name</th><th>Value</th></tr></thead><tbody><tr><td>#M</td><td>#T</td></tr></tbody></table></div>", "<div><table><thead><tr><td>Label:</td><td>#M</td><td></td></tr></thead><tbody><tr><td>Value:</td><td>#T</td><td>#r</td></tr></tbody></table></div>", "<div><table><tr><td>#T</td></tr></table></div>", "<div><table><tr><td>Values:</td><td>#T</td><td>#r</td></tr></table></div>", "", "<table><tr></tr></table>", "<table><tr><td>#a</td></tr></table>", "<table><tr><th>title</th></tr><tr><td>#b</td></tr></table>"));
		$res = new _hx_array(array("<table><tr><td></table>", "<div><table class=\"table\"><tr class=\"tr1\"><td class=\"td1\">Header</td></tr><tr class=\"tr2\"><td class=\"td2\">a</td></tr><tr class=\"tr2\"><td class=\"td2\">b</td></tr><tr class=\"tr2\"><td class=\"td2\">c</td></tr><tr class=\"tr2\"><td class=\"td2\">d</td></tr></table>", "<div><table class=\"wiristable\"><thead><tr><th>Name</th><th>Value</th></tr></thead><tbody><tr><td>a</td><td>1</td></tr><tr><td>b</td><td>2</td></tr><tr><td>c</td><td>3</td></tr><tr><td>d</td><td>4</td></tr></tbody></table></div>", "<div><table class=\"wiristable\"><thead><tr><td>Label:</td><td>a</td><td>b</td><td>c</td><td>d</td><td></td></tr></thead><tbody><tr><td>Value:</td><td>1</td><td>2</td><td>3</td><td>4</td><td>#r</td></tr></tbody></table></div>", "<div><table class=\"wiristable\"><tr><td>1</td><td>2</td><td>3</td><td>4</td></tr></table></div>", "<div><table class=\"wiristable\"><tr><td>Values:</td><td>1</td><td>2</td><td>3</td><td>4</td><td>#r</td></tr></table></div>", "", "<table><tr></tr></table>", "<table class=\"wiristable\"><tr><td><math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mn>1</mn></math></td><td><math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mn>2</mn></math></td><td><math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mn>3</mn></math></td></tr></table>", "<table class=\"wiristable\"><tr><th>title</th></tr><tr><td><math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mfenced open=\"{\" close=\"}\"><mrow><mn>1</mn><mo>,</mo><mn>1</mn></mrow></mfenced></math></td></tr><tr><td><math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mfenced open=\"{\" close=\"}\"><mrow><mn>2</mn><mo>,</mo><mn>4</mn></mrow></mfenced></math></td></tr><tr><td><math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mfenced open=\"{\" close=\"}\"><mrow><mn>3</mn><mo>,</mo><mn>9</mn></mrow></mfenced></math></td></tr></table>"));
		$i = null;
		{
			$_g1 = 0; $_g = $html->length;
			while($_g1 < $_g) {
				$i1 = $_g1++;
				$computed = $this->replaceVariablesInsideHTMLTables($html[$i1], $variables);
				if(!($computed === $res[$i1])) {
					throw new HException("Failed table test " . _hx_string_rec($i1, "") . ".\x0A" . "Expected: " . $res[$i1] . "\x0A" . "Computed: " . $computed);
				}
				unset($i1,$computed);
			}
		}
	}
	public function unitTest() {
		$this->unitTestUpdateReservedWords();
		$this->unitTestReplaceVariablesInHTML();
		$this->unitTestVariableNames();
		$this->unitTestConvertEditor2Newlines();
		$this->unitTestExtractText();
		$this->unitTestTextToMathML();
		$this->unitTestPrepareFormulasAlgorithm();
		$this->unitTestMathMLToText();
		$this->unitTestTables();
	}
	public function implode($a, $tok) {
		$sb = new StringBuf();
		$i = null;
		{
			$_g1 = 0; $_g = $a->length;
			while($_g1 < $_g) {
				$i1 = $_g1++;
				if($i1 > 0) {
					$sb->add($tok);
				}
				$sb->add($a[$i1]);
				unset($i1);
			}
		}
		return $sb->b;
	}
	public function unitTestVariableNames() {
		$html = new _hx_array(array("<math><mi>#a1</mi></math>", "<p>Calcular la corriente que circula por el condensador D del circuito adjunto. Los valores de los compenentes son: A = #A Ω;  B = #B Ω;  C = #C Ω;  D = #D Ω.</p>\x0A" . "<p><img src=\"@@PLUGINFILE@@/Circuito1.gif\" alt=\"\" width=\"424\" height=\"170\" /></p> <p>#x</p> <p>#x1</p> <p>#x2</p> <p>#x3</p> ", "<span style=\"color: #aaffaa;\">&#x32;#x_11</span>", "123 #a<ol>\x0A" . "<li><span style=\"background-color: #ffff00; font-family: 'times new roman', times, serif;\">Use inverse operations to undo Addition and Subtraction (add and/or subtract all terms from left to right)</span></li>\x0A" . "<li><span style=\"background-color: #ffff00; font-family: 'times new roman', times, serif;\">Use inverse operations to undo Multiplication and Division (multiply and/or divide all factors from left to right)</span></li>\x0A" . "</ol>", "ΩΩΩΩΩΩΩ<div>ΩΩΩ<p>ΩΩ#aΩΩ</p>ΩΩΩ</div>ΩΩΩΩΩΩΩ", "<math><mlongdiv longdivstyle=\"shortstackedrightright\" charalign=\"center\" charspacing=\"0px\" stackalign=\"left\"><mrow><mo>#</mo><mi>d</mi></mrow><mrow><mo>#</mo><mi>q</mi></mrow><msgroup><msrow><mo>#</mo><mi>n</mi></msrow></msgroup></mlongdiv></math>"));
		$vars = new _hx_array(array(new _hx_array(array("a1")), new _hx_array(array("A", "B", "C", "D", "x", "x1", "x2", "x3")), new _hx_array(array("x_11")), new _hx_array(array("a")), new _hx_array(array("aΩΩ")), new _hx_array(array("d", "n", "q"))));
		$i = null;
		{
			$_g1 = 0; $_g = $html->length;
			while($_g1 < $_g) {
				$i1 = $_g1++;
				$v = $this->extractVariableNames($html[$i1]);
				$a1 = $this->implode($vars[$i1], ", ");
				$a2 = $this->implode($v, ", ");
				if(!($a1 === $a2)) {
					throw new HException("Expected: " . $a1 . ". Found: " . $a2 . ".");
				}
				unset($v,$i1,$a2,$a1);
			}
		}
	}
	public function unitTestMathMLToText() {
		$mathml = new _hx_array(array("<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mi>sin</mi><mo>(</mo><mi>x</mi><mo>)</mo><mo>+</mo><mn>1</mn></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mo>#</mo><mi>F</mi><mo>+</mo><mi>C</mi></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mn>2</mn><mo>.</mo><mn>0</mn><mo>·</mo><mi>x</mi><mi>y</mi></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mi mathvariant=\"normal\">sin</mi><mi>x</mi></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mfenced><mrow><mn>1</mn><mo>,</mo><mn>0</mn></mrow></mfenced></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mfrac><mn>1</mn><mn>3</mn></mfrac></math>", "<math><mfenced open=\"|\" close = \"|\"><mtable><mtr><mtd><mn>1</mn></mtd><mtd><mn>0</mn></mtd></mtr><mtr><mtd><mn>0</mn></mtd><mtd><mn>1</mn></mtd></mtr></mtable></mfenced></math>", "<math><mfenced><mtable><mtr><mtd><mn>1</mn></mtd><mtd><mn>0</mn></mtd></mtr><mtr><mtd><mn>0</mn></mtd><mtd><mn>1</mn></mtd></mtr></mtable></mfenced></math>", "<math><msqrt><mn>2</mn></msqrt><mo>+</mo><mroot><mi>x</mi><mn>3</mn></mroot></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mi>α</mi><mo>+</mo><mn>1</mn></math>"));
		$responses = new _hx_array(array("sin(x)+1", "#F+C", "2.0·xy", "sinx", "(1,0)", "1/3", "|((1,0),(0,1))|", "((1,0),(0,1))", "sqrt(2)+root(x,3)", "α+1"));
		$i = null;
		{
			$_g1 = 0; $_g = $mathml->length;
			while($_g1 < $_g) {
				$i1 = $_g1++;
				$res = $this->mathMLToText($mathml[$i1]);
				if(!($res === $responses[$i1])) {
					throw new HException("Expected: '" . $responses[$i1] . "' but got: '" . $res . "'.");
				}
				unset($res,$i1);
			}
		}
	}
	public function unitTestTextToMathML() {
		$texts = new _hx_array(array("sin(x)+1", "#F +C", "2.0·xy", "x<3", "\x0A" . " #resposta\x0A" . "  "));
		$responses = new _hx_array(array("<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mi>sin</mi><mo>(</mo><mi>x</mi><mo>)</mo><mo>+</mo><mn>1</mn></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mo>#</mo><mi>F</mi><mo> </mo><mo>+</mo><mi>C</mi></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mn>2</mn><mo>.</mo><mn>0</mn><mo>·</mo><mi>x</mi><mi>y</mi></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mi>x</mi><mo>&lt;</mo><mn>3</mn></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mo>\x0A" . "</mo><mo> </mo><mo>#</mo><mi>r</mi><mi>e</mi><mi>s</mi><mi>p</mi><mi>o</mi><mi>s</mi><mi>t</mi><mi>a</mi><mo>\x0A" . "</mo><mo> </mo><mo> </mo></math>"));
		$i = null;
		{
			$_g1 = 0; $_g = $texts->length;
			while($_g1 < $_g) {
				$i1 = $_g1++;
				$res = $this->textToMathML($texts[$i1]);
				if(!($res === $responses[$i1])) {
					throw new HException("Expected: '" . $responses[$i1] . "' but got: '" . $res . "'.");
				}
				unset($res,$i1);
			}
		}
	}
	public function unitTestReplaceVariablesInHTML() {
		$texts = new _hx_array(array("<p>«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mo mathcolor=¨#FF0000¨»#«/mo»«mi mathcolor=¨#FF0000¨»a«/mi»«mo mathcolor=¨#FF0000¨»+«/mo»«mn mathcolor=¨#FF0000¨»3«/mn»«/math»</p>", "<math><mi>#a1</mi></math>", "<math><mo>#</mo><mi>a</mi><mn>1</mn></math>", "<p><img align=\"middle\" src=\"http://localhost/moodle21/lib/editor/tinymce/tiny_mce/3.4.2/plugins/tiny_mce_wiris/integration/showimage.php?formula=cb550f21cbc30fac59e4f2bba550693d.png\" /> + #dif</p>", "a  «math xmlns=&quot;http://www.w3.org/1998/Math/MathML&quot;»«mfrac»«mrow»«mi»#«/mi»«mi»a«/mi»«mo»+«/mo»«mn»1«/mn»«/mrow»«mrow»«mi»#«/mi»«mi»b«/mi»«mo»-«/mo»«mn»1«/mn»«/mrow»«/mfrac»«/math» a", "<p>#a&#xa1;<script type=\"text/javascript\"> <!-- #a1 will be replaced by wiris quizzes --> <![CDATA[ a = #a1; ]]> </script> <select><option>#a</option><option>#a1</option></select></p>", "<math><mo>#</mo><mi>a</mi><mn>0</mn></math>", "<p><img align=\"middle\" alt=\"# alpha\" class=\"Wirisformula\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mo»#«/mo»«mi»§#945;«/mi»«/math»\" height=\"13\" src=\"/pluginwiris_engine/app/showimage?formula=4f52b56f431aac53cad4c548ef47e646&amp;cw=26&amp;ch=13&amp;cb=12\" style=\"vertical-align: -1px;\" width=\"26\" />&nbsp;i #&alpha;.</p>"));
		$mml = new Hash();
		$mml->set("dif", "<math><mn>0</mn></math>");
		$mml->set("a", "<math><mi>x</mi></math>");
		$mml->set("b", "<math><mi>y</mi></math>");
		$mml->set("a1", "<math><mi>z</mi></math>");
		$mml->set("α", "<math><mn>2</mn></math>");
		$txt = new Hash();
		$txt->set("dif", "0");
		$txt->set("a", "x");
		$txt->set("b", "y");
		$txt->set("a1", "z");
		$txt->set("α", "2");
		$v = new Hash();
		$v->set(com_wiris_quizzes_impl_MathContent::$TYPE_MATHML, $mml);
		$v->set(com_wiris_quizzes_impl_MathContent::$TYPE_TEXT, $txt);
		$responses = new _hx_array(array("<p>«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mstyle mathcolor=¨#FF0000¨»«mrow»«mi»x«/mi»«/mrow»«/mstyle»«mo mathcolor=¨#FF0000¨»+«/mo»«mn mathcolor=¨#FF0000¨»3«/mn»«/math»</p>", "<math><mrow><mi>z</mi></mrow></math>", "<math><mrow><mi>z</mi></mrow></math>", "<p><img align=\"middle\" src=\"http://localhost/moodle21/lib/editor/tinymce/tiny_mce/3.4.2/plugins/tiny_mce_wiris/integration/showimage.php?formula=cb550f21cbc30fac59e4f2bba550693d.png\" /> + <math><mn>0</mn></math></p>", "a  «math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mfrac»«mrow»«mrow»«mi»x«/mi»«/mrow»«mo»+«/mo»«mn»1«/mn»«/mrow»«mrow»«mrow»«mi»y«/mi»«/mrow»«mo»-«/mo»«mn»1«/mn»«/mrow»«/mfrac»«/math» a", "<p><math><mi>x</mi></math>¡<script type=\"text/javascript\"> <!-- #a1 will be replaced by wiris quizzes --> <![CDATA[ a = z; ]]> </script> <select><option>x</option><option>z</option></select></p>", "<math><mrow><mrow><mi>x</mi></mrow><mo>0</mo></mrow></math>", "<p><img align=\"middle\" alt=\"# alpha\" class=\"Wirisformula\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mrow»«mn»2«/mn»«/mrow»«/math»\" height=\"13\" src=\"/pluginwiris_engine/app/showimage?formula=4f52b56f431aac53cad4c548ef47e646&amp;cw=26&amp;ch=13&amp;cb=12\" style=\"vertical-align: -1px;\" width=\"26\" /> i «math»«mn»2«/mn»«/math».</p>"));
		$i = null;
		{
			$_g1 = 0; $_g = $texts->length;
			while($_g1 < $_g) {
				$i1 = $_g1++;
				$res = $this->expandVariables($texts[$i1], $v);
				if(!($res === $responses[$i1])) {
					throw new HException("Expected: '" . $responses[$i1] . "' but got: '" . $res . "'.");
				}
				unset($res,$i1);
			}
		}
	}
	public function unitTestExtractText() {
		$inputs = new _hx_array(array("<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mrow><mtext>Cap de les altres</mtext></mrow></math>", "<math><mrow><mtext>La resposta és </mtext><mfrac><mfrac><mn>1</mn><mn>2</mn></mfrac><mi>x</mi></mfrac><mtext>.</mtext></mrow></math>", "<math><mrow><mo>(</mo><mtext>tiruliru</mtext><mo>)</mo></mrow></math>", "<math><mrow><msqrt><mtext>radicand</mtext></msqrt></mrow></math>", "<math><mtext>&#xa0;a&#xa0;b&#xa0;</mtext></math>", "<math><mtext>&#xa0;</mtext></math>"));
		$outputs = new _hx_array(array("Cap de les altres", "La resposta és <math><mrow><mfrac><mfrac><mn>1</mn><mn>2</mn></mfrac><mi>x</mi></mfrac></mrow></math>.", "<math><mrow><mo>(</mo></mrow></math>tiruliru<math><mrow><mo>)</mo></mrow></math>", "<math><mrow><msqrt><mtext>radicand</mtext></msqrt></mrow></math>", " a b ", " "));
		$i = null;
		{
			$_g1 = 0; $_g = $inputs->length;
			while($_g1 < $_g) {
				$i1 = $_g1++;
				$coutput = $this->extractTextFromMathML($inputs[$i1]);
				if(!($coutput === $outputs[$i1])) {
					throw new HException("Expected: '" . $outputs[$i1] . "' but got: '" . $coutput . "'.");
				}
				unset($i1,$coutput);
			}
		}
	}
	public function unitTestPrepareFormulasAlgorithm() {
		$tests = new _hx_array(array("<math><mo>#</mo><mi>v</mi><mi>a</mi><mi>r</mi><mo>_</mo><mi>k</mi></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mo>#</mo><mi>a</mi><mo>&#160;</mo><mo>+</mo><mo>#</mo><mi>b</mi></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mi>p</mi><mo>(</mo><mi>x</mi><mo>)</mo><mo>=</mo><mi>#</mi><mi>p</mi></math>", "<math><mrow><mi>#</mi><mi>f</mi></mrow></math>", "<math><mrow><mi>#</mi><msup><mi>f</mi><mn>2</mn></msup></mrow></math>", "<math><mrow><msqrt><mrow><mn>2</mn><msqrt><mn>3</mn></msqrt></mrow></msqrt><mi>#</mi><mi>a</mi></mrow></math>", "<math><mrow><msub><mi>#</mi><mi>a</mi></msub></mrow></math>", "<math><mrow><mi>#</mi><msub><mi>a</mi><mi>c</mi></msub></mrow></math>", "<math><mrow><msqrt><mrow><mi>#</mi><mi>f</mi><mi>u</mi><mi>n</mi><mi>c</mi></mrow></msqrt></mrow></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mo>&#8594;</mo><mn>0</mn></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mi>x</mi><mo>=</mo><mo>#</mo><mi>a</mi><mspace linebreak=\"newline\"/><mi>y</mi><mo>=</mo><mo>#</mo><mi>b</mi></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mo>#</mo><mi>α</mi></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mo mathcolor=\"#FF0000\">#</mo><mi mathcolor=\"#FF0000\">a</mi><mo mathcolor=\"#FF0000\">+</mo><mn mathcolor=\"#FF0000\">3</mn></math>"));
		$responses = new _hx_array(array("<math><mo>#var_k</mo></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mo>#a</mo><mo>&#160;</mo><mo>+</mo><mo>#b</mo></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mi>p</mi><mo>(</mo><mi>x</mi><mo>)</mo><mo>=</mo><mi>#p</mi></math>", "<math><mrow><mi>#f</mi></mrow></math>", "<math><mrow><msup><mi>#f</mi><mn>2</mn></msup></mrow></math>", "<math><mrow><msqrt><mrow><mn>2</mn><msqrt><mn>3</mn></msqrt></mrow></msqrt><mi>#a</mi></mrow></math>", "<math><mrow><msub><mi>#</mi><mi>a</mi></msub></mrow></math>", "<math><mrow><msub><mi>#a</mi><mi>c</mi></msub></mrow></math>", "<math><mrow><msqrt><mrow><mi>#func</mi></mrow></msqrt></mrow></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mo>&#8594;</mo><mn>0</mn></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mi>x</mi><mo>=</mo><mo>#a</mo><mspace linebreak=\"newline\"/><mi>y</mi><mo>=</mo><mo>#b</mo></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mo>#α</mo></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mo mathcolor=\"#FF0000\">#a</mo><mo mathcolor=\"#FF0000\">+</mo><mn mathcolor=\"#FF0000\">3</mn></math>"));
		$i = null;
		{
			$_g1 = 0; $_g = $tests->length;
			while($_g1 < $_g) {
				$i1 = $_g1++;
				$res = $this->prepareFormulas($tests[$i1]);
				if(!($res === $responses[$i1])) {
					throw new HException("Expected: '" . $responses[$i1] . "' but got: '" . $res . "'.");
				}
				unset($res,$i1);
			}
		}
	}
	public function unitTestConvertEditor2Newlines() {
		$tests = new _hx_array(array("<mtable columnalign=\"left\" rowspacing=\"0\"><mtr><mtd><mfenced><mtable><mtr><mtd><mn>1</mn></mtd><mtd><mn>2</mn></mtd></mtr><mtr><mtd><mtable><mtr><mtd><mi>a</mi></mtd><mtd><mi>b</mi></mtd></mtr></mtable></mtd><mtd><mn>2</mn></mtd></mtr></mtable></mfenced></mtd></mtr><mtr><mtd><mtable><mtr><mtd><mn>1</mn></mtd><mtd><mn>2</mn></mtd></mtr></mtable></mtd></mtr></mtable>"));
		$res = new _hx_array(array("<math><mfenced><mtable><mtr><mtd><mn>1</mn></mtd><mtd><mn>2</mn></mtd></mtr><mtr><mtd><mtable><mtr><mtd><mi>a</mi></mtd><mtd><mi>b</mi></mtd></mtr></mtable></mtd><mtd><mn>2</mn></mtd></mtr></mtable></mfenced><mspace linebreak=\"newline\"/><mtable><mtr><mtd><mn>1</mn></mtd><mtd><mn>2</mn></mtd></mtr></mtable></math>"));
		$i = null;
		{
			$_g1 = 0; $_g = $tests->length;
			while($_g1 < $_g) {
				$i1 = $_g1++;
				$u = com_wiris_quizzes_impl_HTMLTools::convertEditor2Newlines($tests[$i1]);
				if(!($u === $res[$i1])) {
					throw new HException("Expected: " . $res[$i1] . ". Got: " . $u . ".");
				}
				unset($u,$i1);
			}
		}
	}
	public function unitTestUpdateReservedWords() {
		$tests = new _hx_array(array("<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><msup><mi>x</mi><mn>3</mn></msup><msup><mi>y</mi><mn>2</mn></msup></math>", "<math><semantics><mrow><mn>6</mn><mi>k</mi><mi>m</mi><mo>+</mo><mn>4</mn><mi>k</mi><mi>m</mi></mrow><annotation encoding=\"application/json\">[[[18,9],...,[4,27]]]</annotation></semantics></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mrow><mn>4</mn><mo>.</mo><mn>1</mn><mo>&#xD7;</mo><mn>1</mn><msup><mn>0</mn><mrow><mo>-</mo><mn>4</mn></mrow></msup></mrow></math>", "<math><mi>s</mi><mi>i</mi><mi>n</mi><mi>s</mi><mi>i</mi><mi>n</mi></math>", "<math><mi>s</mi><mi>i</mi><mi>n</mi><mn>1</mn><mi>s</mi><mi>i</mi><mi>n</mi></math>", "<math><msup><mrow><mi>s</mi><mi>i</mi><msup><mi>n</mi><mi>k</mi></msup></mrow><mi>n</mi></msup></math>", "<math><mi>s</mi><msup><mi>i</mi><mn>1</mn></msup><mi>s</mi><msup><mi>i</mi><mn>1</mn></msup></math>", "<math><mi>s</mi><mrow><mi>i</mi><mi>m</mi><mi>x</mi></mrow></math>", "<math><mi>si</mi><mi>n</mi><mi>x</mi></math>", "<math><mn>2</mn><mi>k</mi><mi>m</mi></math>", "<math><mn>2</mn><mi>k</mi><mo>&nbsp;</mo><mi>m</mi></math>", "<math><mn>5</mn><mi>k</mi><msup><mi>m</mi><mn>2</mn></msup></math>"));
		$words = new _hx_array(array("s", "si", "sin", "m", "km"));
		$res = new _hx_array(array("<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><msup><mi>x</mi><mn>3</mn></msup><msup><mi>y</mi><mn>2</mn></msup></math>", "<math><semantics><mrow><mn>6</mn><mi>km</mi><mo>+</mo><mn>4</mn><mi>km</mi></mrow><annotation encoding=\"application/json\">[[[18,9],...,[4,27]]]</annotation></semantics></math>", "<math xmlns=\"http://www.w3.org/1998/Math/MathML\"><mn>4</mn><mo>.</mo><mn>1</mn><mo>&#xD7;</mo><msup><mn>10</mn><mrow><mo>-</mo><mn>4</mn></mrow></msup></math>", "<math><mi>sin</mi><mi>sin</mi></math>", "<math><mi>sin</mi><mn>1</mn><mi>sin</mi></math>", "<math><msup><msup><mi>sin</mi><mi>k</mi></msup><mi>n</mi></msup></math>", "<math><msup><mi>si</mi><mn>1</mn></msup><msup><mi>si</mi><mn>1</mn></msup></math>", "<math><mi>si</mi><mi mathvariant=\"normal\">m</mi><mi>x</mi></math>", "<math><mi>sin</mi><mi>x</mi></math>", "<math><mn>2</mn><mi>km</mi></math>", "<math><mn>2</mn><mi>k</mi><mo>&#xA0;</mo><mi mathvariant=\"normal\">m</mi></math>", "<math><mn>5</mn><msup><mi>km</mi><mn>2</mn></msup></math>"));
		$i = null;
		{
			$_g1 = 0; $_g = $tests->length;
			while($_g1 < $_g) {
				$i1 = $_g1++;
				$u = $this->updateReservedWords($tests[$i1], $words);
				if(!($u === $res[$i1])) {
					throw new HException("Expected: " . $res[$i1] . ". Got: " . $u . ".");
				}
				unset($u,$i1);
			}
		}
	}
	public function fullMathML2TextImpl($e) {
		$sb = new StringBuf();
		if($e->getNodeName() === "mo" || $e->getNodeName() === "mn" || $e->getNodeName() === "mi") {
			$sb->add(com_wiris_util_xml_WXmlUtils::getNodeValue($e->firstChild()));
		} else {
			if($e->getNodeName() === "mfenced" || $e->getNodeName() === "mtr" || $e->getNodeName() === "mtable") {
				$open = $e->get("open");
				if($open === null) {
					$open = "(";
				}
				$close = $e->get("close");
				if($close === null) {
					$close = ")";
				}
				$separators = $e->get("separators");
				if($separators === null) {
					$separators = ",";
				}
				if($open === "(" && $close === ")" && $e->firstElement()->getNodeName() === "mtable") {
					$open = "";
					$close = "";
				}
				$sb->add($open);
				$it = $e->elements();
				$i = 0;
				$n = haxe_Utf8::length($separators);
				while($it->hasNext()) {
					if($i > 0 && $n > 0) {
						$sb->add(com_wiris_quizzes_impl_HTMLTools_0($this, $close, $e, $i, $it, $n, $open, $sb, $separators));
					}
					$sb->add($this->fullMathML2TextImpl($it->next()));
					$i++;
				}
				$sb->add($close);
			} else {
				if($e->getNodeName() === "mfrac") {
					$it = $e->elements();
					$num = $this->fullMathML2TextImpl($it->next());
					if(strlen($num) > 1) {
						$num = "(" . $num . ")";
					}
					$den = $this->fullMathML2TextImpl($it->next());
					if(strlen($den) > 1) {
						$den = "(" . $den . ")";
					}
					$sb->add($num);
					$sb->add("/");
					$sb->add($den);
				} else {
					if($e->getNodeName() === "msup") {
						$it = $e->elements();
						$bas = $this->fullMathML2TextImpl($it->next());
						if(strlen($bas) > 1) {
							$bas = "(" . $bas . ")";
						}
						$exp = $this->fullMathML2TextImpl($it->next());
						if(strlen($exp) > 1) {
							$exp = "(" . $exp . ")";
						}
						$sb->add($bas);
						$sb->add("^");
						$sb->add($exp);
					} else {
						if($e->getNodeName() === "msqrt") {
							$rad = $this->fullMathML2TextImpl($e->firstElement());
							$sb->add("sqrt(");
							$sb->add($rad);
							$sb->add(")");
						} else {
							if($e->getNodeName() === "mroot") {
								$it = $e->elements();
								$rad = $this->fullMathML2TextImpl($it->next());
								$ind = $this->fullMathML2TextImpl($it->next());
								$sb->add("root(");
								$sb->add($rad);
								$sb->add(",");
								$sb->add($ind);
								$sb->add(")");
							} else {
								if($e->getNodeName() === "mspace" && "newline" === $e->get("linebreak")) {
									$sb->add("\x0A");
								} else {
									if($e->getNodeName() === "semantics") {
										$it = $e->elements();
										if($it->hasNext()) {
											$mml = $it->next();
											if($it->hasNext()) {
												$ann = $it->next();
												if($ann->getNodeName() === "annotation" && "text/plain" === $ann->get("encoding")) {
													return com_wiris_util_xml_WXmlUtils::getText($ann);
												}
											}
											return $this->fullMathML2TextImpl($mml);
										}
									} else {
										$it = $e->elements();
										while($it->hasNext()) {
											$sb->add($this->fullMathML2TextImpl($it->next()));
										}
									}
								}
							}
						}
					}
				}
			}
		}
		return $sb->b;
	}
	public function mathMLToText($mathml) {
		$root = com_wiris_util_xml_WXmlUtils::parseXML($mathml);
		if($root->nodeType == Xml::$Document) {
			$root = $root->firstElement();
		}
		return $this->fullMathML2TextImpl($root);
	}
	public function isReservedWordPrefix($token, $words) {
		$i = null;
		{
			$_g1 = 0; $_g = $words->length;
			while($_g1 < $_g) {
				$i1 = $_g1++;
				if(StringTools::startsWith($words[$i1], $token)) {
					return true;
				}
				unset($i1);
			}
		}
		return false;
	}
	public function reservedWordTokens($elem, $words) {
		$it = $elem->elements();
		while($it->hasNext()) {
			$this->reservedWordTokens($it->next(), $words);
		}
		if(_hx_index_of(com_wiris_quizzes_impl_HTMLTools::$MROWS, "@" . $elem->getNodeName() . "@", null) !== -1) {
			$children = $elem->elements();
			$c = (($children->hasNext()) ? $children->next() : null);
			while($c !== null) {
				if($c->getNodeName() === "mi") {
					$mis = new _hx_array(array());
					$mitexts = new _hx_array(array());
					while($c !== null && $c->getNodeName() === "mi") {
						$text = com_wiris_util_xml_WXmlUtils::getNodeValue($c->firstChild());
						$mitexts->push($text);
						$mis->push($c);
						$c = (($children->hasNext()) ? $children->next() : null);
						unset($text);
					}
					$k = 0;
					while($k < $mis->length) {
						$word = $mitexts[$k];
						$lastReservedWord = null;
						$j = 0;
						$l = 0;
						while($this->isReservedWordPrefix($word, $words)) {
							if($this->inArray($word, $words)) {
								$lastReservedWord = $word;
								$l = $j;
							}
							$j++;
							if($j + $k >= $mis->length) {
								break;
							}
							$word .= $mitexts[$k + $j];
						}
						if($lastReservedWord !== null) {
							if($mitexts[$k] === $lastReservedWord) {
								_hx_array_get($mis, $k)->set("mathvariant", "normal");
							} else {
								_hx_array_get($mis, $k)->removeChild(_hx_array_get($mis, $k)->firstChild());
								_hx_array_get($mis, $k)->addChild(com_wiris_util_xml_WXmlUtils::createPCData($elem, $lastReservedWord));
								$m = null;
								{
									$_g = 0;
									while($_g < $l) {
										$m1 = $_g++;
										$k++;
										$mi = $mis[$k];
										$elem->removeChild($mi);
										unset($mi,$m1);
									}
									unset($_g);
								}
								unset($m);
							}
						}
						$k++;
						unset($word,$lastReservedWord,$l,$j);
					}
					unset($mitexts,$mis,$k);
				} else {
					if($c->getNodeName() === "mn") {
						$first = $c;
						$c = (($children->hasNext()) ? $children->next() : null);
						if($c !== null && $c->getNodeName() === "mn") {
							$mns = new _hx_array(array());
							$num = new StringBuf();
							$num->add(com_wiris_util_xml_WXmlUtils::getNodeValue($first->firstChild()));
							while($c !== null && $c->getNodeName() === "mn") {
								$mns->push($c);
								$num->add(com_wiris_util_xml_WXmlUtils::getNodeValue($c->firstChild()));
								$c = (($children->hasNext()) ? $children->next() : null);
							}
							$first->removeChild($first->firstChild());
							$first->addChild(com_wiris_util_xml_WXmlUtils::createPCData($first, $num->b));
							$m = null;
							{
								$_g1 = 0; $_g = $mns->length;
								while($_g1 < $_g) {
									$m1 = $_g1++;
									$elem->removeChild($mns[$m1]);
									unset($m1);
								}
								unset($_g1,$_g);
							}
							unset($num,$mns,$m);
						}
						unset($first);
					} else {
						$c = (($children->hasNext()) ? $children->next() : null);
					}
				}
			}
		}
	}
	public function restoreFlatMathML($elem) {
		$it = $elem->elements();
		while($it->hasNext()) {
			$this->restoreFlatMathML($it->next());
		}
		if(_hx_index_of(com_wiris_quizzes_impl_HTMLTools::$MROWS, "@" . $elem->getNodeName() . "@", null) !== -1) {
			$children = $elem->elements();
			$elements = new _hx_array(array());
			while($children->hasNext()) {
				$elements->push($children->next());
			}
			if($elements->length > 0) {
				$current = $elements[0];
				$i = 1;
				while($i < $elements->length) {
					$previous = $current;
					$current = $elements[$i++];
					if(_hx_index_of(com_wiris_quizzes_impl_HTMLTools::$MSUPS, "@" . $current->getNodeName() . "@", null) !== -1) {
						$elem->removeChild($previous);
						$current->insertChild($previous, 0);
					}
					unset($previous);
				}
			}
		}
	}
	public function removeMrows($elem) {
		if($elem->nodeType != Xml::$Element && $elem->nodeType != Xml::$Document) {
			return;
		}
		$children = $elem->iterator();
		while($children->hasNext()) {
			$this->removeMrows($children->next());
		}
		$children = $elem->iterator();
		$i = 0;
		while($children->hasNext()) {
			$c = $children->next();
			if($c->nodeType == Xml::$Element) {
				if($c->getNodeName() === "mrow") {
					$mrowChildren = $c->elements();
					$singlechild = false;
					if($mrowChildren->hasNext()) {
						$mrowChildren->next();
						$singlechild = !$mrowChildren->hasNext();
					}
					if($singlechild || _hx_index_of(com_wiris_quizzes_impl_HTMLTools::$MROWS, $elem->getNodeName(), null) !== -1) {
						$elem->removeChild($c);
						$n = null;
						$count = 0;
						while(($n = $c->firstChild()) !== null) {
							$c->removeChild($n);
							$elem->insertChild($n, $i + $count);
							$count++;
						}
						if($count !== 1) {
							$i = -1;
							$children = $elem->iterator();
						}
						unset($n,$count);
					}
					unset($singlechild,$mrowChildren);
				} else {
					if($c->getNodeName() === "mfenced") {
						if("(" === $c->get("open")) {
							$c->remove("open");
						}
						if(")" === $c->get("close")) {
							$c->remove("close");
						}
					}
				}
			}
			$i++;
			unset($c);
		}
	}
	public function breakMis($elem, $pos) {
		if($elem->nodeType != Xml::$Element && $elem->nodeType != Xml::$Document) {
			return;
		}
		$children = $elem->iterator();
		$i = 0;
		while($children->hasNext()) {
			$this->breakMis($children->next(), $i);
			$i++;
		}
		if($elem->nodeType == Xml::$Element && $elem->getNodeName() === "mi") {
			$text = com_wiris_util_xml_WXmlUtils::getNodeValue($elem->firstChild());
			if(haxe_Utf8::length($text) > 1) {
				$p = $elem->_parent;
				$mrow = Xml::createElement("mrow");
				$p->removeChild($elem);
				$p->insertChild($mrow, $pos);
				while(strlen($text) > 0) {
					$mi = Xml::createElement("mi");
					$chartext = haxe_Utf8::sub($text, 0, 1);
					$mi->addChild(com_wiris_util_xml_WXmlUtils::createPCData($elem, $chartext));
					$text = _hx_substr($text, strlen($chartext), null);
					$mrow->addChild($mi);
					unset($mi,$chartext);
				}
			} else {
				$elem->remove("mathvariant");
			}
		}
	}
	public function flattenMsups($elem, $pos) {
		if($elem->nodeType != Xml::$Element && $elem->nodeType != Xml::$Document) {
			return;
		}
		$children = $elem->iterator();
		$i = 0;
		while($children->hasNext()) {
			$this->flattenMsups($children->next(), $i);
			$i++;
		}
		if($elem->nodeType == Xml::$Element && _hx_index_of(com_wiris_quizzes_impl_HTMLTools::$MSUPS, "@" . $elem->getNodeName() . "@", null) !== -1) {
			$n = $elem->_parent;
			$mrow = Xml::createElement("mrow");
			$c = $elem->firstElement();
			$elem->removeChild($c);
			$mrow->addChild($c);
			$n->removeChild($elem);
			$mrow->addChild($elem);
			$n->insertChild($mrow, $pos);
		}
	}
	public function updateReservedWords($mathml, $words) {
		if($mathml === null || trim($mathml) === "") {
			return "";
		}
		$mathml = com_wiris_util_xml_WXmlUtils::resolveEntities($mathml);
		$doc = Xml::parse($mathml);
		$this->flattenMsups($doc, 0);
		$this->breakMis($doc, 0);
		$this->removeMrows($doc);
		$this->reservedWordTokens($doc->firstElement(), $words);
		$this->restoreFlatMathML($doc->firstElement());
		return com_wiris_util_xml_WXmlUtils::serializeXML($doc);
	}
	public function getParentTag($s, $n) {
		$stack = new _hx_array(array());
		$error = false;
		while(($n = _hx_index_of($s, "<", $n)) !== -1 && !$error) {
			if($this->isQuizzesIdentifierStart(_hx_char_code_at($s, $n + 1))) {
				$close = _hx_index_of($s, ">", $n);
				$space = _hx_index_of($s, " ", $n);
				if($space !== -1 && $space < $close) {
					$close = $space;
				}
				if($close !== -1) {
					$stack->push(_hx_substr($s, $n + 1, $close - $n - 1));
				} else {
					$error = true;
				}
				unset($space,$close);
			} else {
				if(_hx_char_code_at($s, $n + 1) === 47) {
					$close = _hx_index_of($s, ">", $n);
					$tag = _hx_substr($s, $n + 2, $close - $n - 2);
					if($stack->length === 0) {
						return $tag;
					} else {
						if(!($stack->pop() === $tag)) {
							$error = true;
						}
					}
					unset($tag,$close);
				} else {
					if(_hx_substr($s, $n, 4) === "<!--") {
						$n = _hx_index_of($s, "-->", $n);
						if($n === -1) {
							$error = true;
						}
					}
				}
			}
			$n++;
		}
		return null;
	}
	public function isEntity($s, $n) {
		if($n > 0 && _hx_char_code_at($s, $n - 1) === 38) {
			$n++;
			$end = _hx_index_of($s, ";", $n);
			if($end !== -1) {
				while($this->isQuizzesIdentifierPart(_hx_char_code_at($s, $n))) {
					$n++;
				}
				return $n === $end;
			}
		}
		return false;
	}
	public function variablePosition($s, $n) {
		if($this->insideTag($s, $n) || $this->isEntity($s, $n) || $this->insideComment($s, $n)) {
			return com_wiris_quizzes_impl_HTMLTools::$POSITION_NONE;
		} else {
			$parent = $this->getParentTag($s, $n);
			if($parent === null) {
				return com_wiris_quizzes_impl_HTMLTools::$POSITION_ALL;
			}
			if($parent === "script" || $parent === "option") {
				return com_wiris_quizzes_impl_HTMLTools::$POSITION_ONLY_TEXT;
			} else {
				if($parent === "style") {
					return com_wiris_quizzes_impl_HTMLTools::$POSITION_NONE;
				} else {
					if($parent === "mi" || $parent === "mo" || $parent === "mtext" || $parent === "ms") {
						return com_wiris_quizzes_impl_HTMLTools::$POSITION_ONLY_MATHML;
					} else {
						if($parent === "td") {
							return com_wiris_quizzes_impl_HTMLTools::$POSITION_TABLE;
						} else {
							return com_wiris_quizzes_impl_HTMLTools::$POSITION_ALL;
						}
					}
				}
			}
		}
	}
	public function extractTextFromMathML($formula) {
		if(_hx_index_of($formula, "<mtext", null) === -1) {
			return $formula;
		}
		$allowedTags = new _hx_array(array("math", "mrow"));
		$stack = new _hx_array(array());
		$omittedcontent = false;
		$lasttag = null;
		$beginformula = _hx_index_of($formula, "<", null);
		$start = null;
		$end = 0;
		while($end < strlen($formula) && ($start = _hx_index_of($formula, "<", $end)) !== -1) {
			$end = _hx_index_of($formula, ">", $start);
			$tag = _hx_substr($formula, $start, $end - $start + 1);
			$trimmedTag = _hx_substr($formula, $start + 1, $end - $start - 1);
			if(_hx_substr($trimmedTag, strlen($trimmedTag) - 1, null) === "/") {
				continue;
			}
			$spacepos = _hx_index_of($tag, " ", null);
			if($spacepos !== -1) {
				$trimmedTag = _hx_substr($tag, 1, $spacepos - 1);
			}
			if($this->inArray($trimmedTag, $allowedTags)) {
				$stack->push(new _hx_array(array($trimmedTag, $tag)));
				$lasttag = $trimmedTag;
			} else {
				if($trimmedTag === "/" . $lasttag) {
					$stack->pop();
					if($stack->length > 0) {
						$lastpair = $stack[$stack->length - 1];
						$lasttag = $lastpair[0];
						unset($lastpair);
					} else {
						$lasttag = null;
					}
					if($stack->length === 0 && !$omittedcontent) {
						$formula1 = _hx_substr($formula, 0, $beginformula);
						if($end < strlen($formula) - 1) {
							$formula2 = _hx_substr($formula, $end + 1, null);
							$formula = $formula1 . $formula2;
							unset($formula2);
						} else {
							$formula = $formula1;
						}
						unset($formula1);
					}
				} else {
					if($trimmedTag === "mtext") {
						$pos2 = _hx_index_of($formula, "</mtext>", $start);
						$text = _hx_substr($formula, $start + 7, $pos2 - $start - 7);
						$text = com_wiris_util_xml_WXmlUtils::resolveEntities($text);
						$nbsp = com_wiris_quizzes_impl_HTMLTools_1($this, $allowedTags, $beginformula, $end, $formula, $lasttag, $omittedcontent, $pos2, $spacepos, $stack, $start, $tag, $text, $trimmedTag);
						$nbspLength = strlen($nbsp);
						if(strlen($text) >= $nbspLength) {
							if(_hx_substr($text, 0, $nbspLength) === $nbsp) {
								$text = " " . _hx_substr($text, $nbspLength, null);
							}
							if(strlen($text) >= $nbspLength && _hx_substr($text, strlen($text) - $nbspLength, null) === $nbsp) {
								$text = _hx_substr($text, 0, strlen($text) - $nbspLength) . " ";
							}
						}
						$formula1 = _hx_substr($formula, 0, $start);
						$formula2 = _hx_substr($formula, $pos2 + 8, null);
						if($omittedcontent) {
							$tail1 = "";
							$head2 = "";
							$i = $stack->length - 1;
							while($i >= 0) {
								$pair = $stack[$i];
								$tail1 = $tail1 . "</" . $pair[0] . ">";
								$head2 = $pair[1] . $head2;
								$i--;
								unset($pair);
							}
							$formula1 = $formula1 . $tail1;
							$formula2 = $head2 . $formula2;
							if(com_wiris_quizzes_impl_MathContent::isEmpty($formula2)) {
								$formula2 = "";
							}
							$formula = $formula1 . $text . $formula2;
							$beginformula = $start + strlen($tail1) + strlen($text);
							$end = $beginformula + strlen($head2);
							unset($tail1,$i,$head2);
						} else {
							$head = _hx_substr($formula1, 0, $beginformula);
							$head2 = _hx_substr($formula1, $beginformula, null);
							$formula2 = $head2 . $formula2;
							if(com_wiris_quizzes_impl_MathContent::isEmpty($formula2)) {
								$formula2 = "";
							}
							$formula = $head . $text . $formula2;
							$beginformula += strlen($text);
							$end = $beginformula + strlen($formula1);
							unset($head2,$head);
						}
						$omittedcontent = false;
						unset($text,$pos2,$nbspLength,$nbsp,$formula2,$formula1);
					} else {
						$num = 1;
						$pos = $start + strlen($tag);
						while($num > 0) {
							$end = _hx_index_of($formula, "</" . $trimmedTag . ">", $pos);
							$mid = _hx_index_of($formula, "<" . $trimmedTag, $pos);
							if($end === -1) {
								return $formula;
							} else {
								if($mid === -1 || $end < $mid) {
									$num--;
									$pos = $end + strlen(("</" . $trimmedTag . ">"));
								} else {
									$pos = $mid + strlen(("<" . $trimmedTag));
									$num++;
								}
							}
							unset($mid);
						}
						$end += strlen(("</" . $trimmedTag . ">"));
						$omittedcontent = true;
						unset($pos,$num);
					}
				}
			}
			unset($trimmedTag,$tag,$spacepos);
		}
		return $formula;
	}
	public function ImageB64Url($b64) {
		return "data:image/png;base64," . $b64;
	}
	public function addPlotterImageB64Tag($value) {
		$h = new com_wiris_quizzes_impl_HTML();
		$h->imageClass($this->ImageB64Url($value), null, "wirisplotter");
		return $h->getString();
	}
	public function addPlotterImageTag($filename) {
		$url = null;
		if(StringTools::endsWith($filename, ".b64")) {
			$s = com_wiris_system_Storage::newStorage($filename);
			$url = $this->ImageB64Url($s->read());
		} else {
			$url = com_wiris_quizzes_impl_QuizzesBuilderImpl::getInstance()->getConfiguration()->get(com_wiris_quizzes_api_ConfigurationKeys::$PROXY_URL) . "?service=cache&name=" . $filename;
		}
		$h = new com_wiris_quizzes_impl_HTML();
		$h->imageClass($url, null, "wirisplotter");
		return $h->getString();
	}
	public function isTokensMathML($mathml) {
		$mathml = com_wiris_quizzes_impl_HTMLTools::stripRootTag($mathml, "math");
		$allowedTags = new _hx_array(array("mrow", "mn", "mi", "mo"));
		$start = 0;
		while(($start = _hx_index_of($mathml, "<", $start)) !== -1) {
			$sb = new StringBuf();
			$start++;
			$c = _hx_char_code_at($mathml, $start);
			if($c === 47) {
				continue;
			}
			while($c !== 32 && $c !== 47 && $c !== 62) {
				$sb->b .= chr($c);
				$start++;
				$c = _hx_char_code_at($mathml, $start);
			}
			if($c === 32 || $c === 47) {
				return false;
			}
			$tagname = $sb->b;
			if(!$this->inArray($tagname, $allowedTags)) {
				return false;
			}
			$start++;
			$end = _hx_index_of($mathml, "<", $start);
			$content = _hx_substr($mathml, $start, $end - $start);
			$i = null;
			{
				$_g1 = 0; $_g = strlen($content);
				while($_g1 < $_g) {
					$i1 = $_g1++;
					$c = _hx_char_code_at($content, $i1);
					if(!($c === 35 || $c >= 48 && $c <= 57 || $c >= 65 && $c <= 90 || $c >= 97 && $c <= 122)) {
						return false;
					}
					unset($i1);
				}
				unset($_g1,$_g);
			}
			unset($tagname,$sb,$i,$end,$content,$c);
		}
		return true;
	}
	public function textToMathMLImpl($text) {
		$n = haxe_Utf8::length($text);
		if($n === 0) {
			return $text;
		}
		$mathml = new StringBuf();
		$token = null;
		$i = 0;
		$c = haxe_Utf8::charCodeAt($text, $i);
		while($i < $n) {
			if(com_wiris_util_xml_WCharacterBase::isDigit($c)) {
				$token = new StringBuf();
				while($i < $n && com_wiris_util_xml_WCharacterBase::isDigit($c)) {
					$token->b .= chr($c);
					$i++;
					if($i < $n) {
						$c = haxe_Utf8::charCodeAt($text, $i);
					}
				}
				$mathml->add("<mn>");
				$mathml->add($token->b);
				$mathml->add("</mn>");
			} else {
				if(com_wiris_util_xml_WCharacterBase::isLetter($c)) {
					$token = new StringBuf();
					while($i < $n && com_wiris_util_xml_WCharacterBase::isLetter($c)) {
						$token->add(com_wiris_quizzes_impl_HTMLTools_2($this, $c, $i, $mathml, $n, $text, $token));
						$i++;
						if($i < $n) {
							$c = haxe_Utf8::charCodeAt($text, $i);
						}
					}
					$tok = $token->b;
					$tokens = null;
					if($this->isReservedWord($tok)) {
						$tokens = new _hx_array(array($tok));
					} else {
						$m = haxe_Utf8::length($tok);
						$tokens = new _hx_array(array());
						$j = null;
						{
							$_g = 0;
							while($_g < $m) {
								$j1 = $_g++;
								$tokens[$j1] = com_wiris_quizzes_impl_HTMLTools_3($this, $_g, $c, $i, $j, $j1, $m, $mathml, $n, $text, $tok, $token, $tokens);
								unset($j1);
							}
							unset($_g);
						}
						unset($m,$j);
					}
					$k = null;
					{
						$_g1 = 0; $_g = $tokens->length;
						while($_g1 < $_g) {
							$k1 = $_g1++;
							$mathml->add("<mi>");
							$mathml->add($tokens[$k1]);
							$mathml->add("</mi>");
							unset($k1);
						}
						unset($_g1,$_g);
					}
					unset($tokens,$tok,$k);
				} else {
					$mathml->add("<mo>");
					if($c === 32) {
						$c = 160;
					}
					$mathml->add(com_wiris_util_xml_WXmlUtils::htmlEscape(com_wiris_quizzes_impl_HTMLTools_4($this, $c, $i, $mathml, $n, $text, $token)));
					$mathml->add("</mo>");
					$i++;
					if($i < $n) {
						$c = haxe_Utf8::charCodeAt($text, $i);
					}
				}
			}
		}
		return $mathml->b;
	}
	public function textToMathMLWithSemantics($text) {
		$mathml = $this->textToMathMLImpl($text);
		$mathml = "<semantics><mrow>" . $mathml . "</mrow><annotation encoding=\"text/plain\">" . $text . "</annotation></semantics>";
		$result = $this->addMathTag($mathml);
		return $result;
	}
	public function textToMathML($text) {
		$mathml = $this->textToMathMLImpl($text);
		$result = $this->addMathTag($mathml);
		return $result;
	}
	public function isReservedWord($word) {
		$reservedWords = new _hx_array(array("sin", "cos", "tan", "log", "ln"));
		return $this->inArray($word, $reservedWords);
	}
	public function toSubFormula($mathml) {
		$mathml = com_wiris_quizzes_impl_HTMLTools::stripRootTag($mathml, "math");
		return "<mrow>" . $mathml . "</mrow>";
	}
	public function addMathTag($mathml) {
		if(!StringTools::startsWith($mathml, "<math")) {
			$mathml = "<math xmlns=\"http://www.w3.org/1998/Math/MathML\">" . $mathml . "</math>";
		}
		return $mathml;
	}
	public function inArray($value, $array) {
		$i = null;
		{
			$_g1 = 0; $_g = $array->length;
			while($_g1 < $_g) {
				$i1 = $_g1++;
				if($array[$i1] === $value) {
					return true;
				}
				unset($i1);
			}
		}
		return false;
	}
	public function prepareFormulas($text) {
		$start = 0;
		while(($start = _hx_index_of($text, "<math", $start)) !== -1) {
			$length = _hx_index_of($text, "</math>", $start) - $start + strlen("</math>");
			$formula = _hx_substr($text, $start, $length);
			$pos = 0;
			while(($pos = _hx_index_of($formula, "#", $pos)) !== -1) {
				$initag = $pos;
				while($initag >= 0 && _hx_char_code_at($formula, $initag) !== 60) {
					$initag--;
				}
				$parentpos = $initag;
				$parenttag = null;
				$parenttagname = null;
				while($parenttag === null) {
					while($parentpos >= 2 && _hx_char_code_at($formula, $parentpos - 2) === 47 && _hx_char_code_at($formula, $parentpos - 1) === 62) {
						$parentpos -= 2;
						while($parentpos >= 0 && _hx_char_code_at($formula, $parentpos) !== 60) {
							$parentpos--;
						}
					}
					$parentpos--;
					while($parentpos >= 0 && _hx_char_code_at($formula, $parentpos) !== 60) {
						$parentpos--;
					}
					if(_hx_char_code_at($formula, $parentpos) === 60 && _hx_char_code_at($formula, $parentpos + 1) === 47) {
						$namepos = $parentpos + strlen("</");
						$character = _hx_char_code_at($formula, $namepos);
						$nameBuf = new StringBuf();
						while($this->isQuizzesIdentifierPart($character)) {
							$nameBuf->b .= chr($character);
							$namepos++;
							$character = _hx_char_code_at($formula, $namepos);
						}
						$name = $nameBuf->b;
						$depth = 1;
						$namelength = strlen($name);
						while($depth > 0 && $parentpos >= 0) {
							$currentTagName = _hx_substr($formula, $parentpos, $namelength);
							if($name === $currentTagName) {
								$currentStartTag = _hx_substr($formula, $parentpos - strlen("<"), $namelength + strlen("<"));
								if("<" . $name === $currentStartTag && _hx_index_of($formula, ">", $parentpos) < _hx_index_of($formula, "/", $parentpos)) {
									$depth--;
								} else {
									$currentOpenCloseTag = _hx_substr($formula, $parentpos - strlen("</"), $namelength + strlen("</"));
									if("</" . $name === $currentOpenCloseTag) {
										$depth++;
									}
									unset($currentOpenCloseTag);
								}
								unset($currentStartTag);
							}
							if($depth > 0) {
								$parentpos--;
							} else {
								$parentpos -= strlen("<");
							}
							unset($currentTagName);
						}
						if($depth > 0) {
							return $text;
						}
						unset($namepos,$namelength,$nameBuf,$name,$depth,$character);
					} else {
						$parenttag = _hx_substr($formula, $parentpos, _hx_index_of($formula, ">", $parentpos) - $parentpos + 1);
						$parenttagname = _hx_substr($parenttag, 1, strlen($parenttag) - 2);
						if(_hx_index_of($parenttagname, " ", null) !== -1) {
							$parenttagname = _hx_substr($parenttagname, 0, _hx_index_of($parenttagname, " ", null));
						}
					}
				}
				if(_hx_index_of(com_wiris_quizzes_impl_HTMLTools::$MROWS, "@" . $parenttagname . "@", null) !== -1) {
					$firstchar = true;
					$appendpos = $pos + 1;
					$character = com_wiris_util_xml_WXmlUtils::getUtf8Char($formula, $appendpos);
					while($this->isQuizzesIdentifierStart($character) || $this->isQuizzesIdentifierPart($character) && !$firstchar) {
						$appendpos += strlen((com_wiris_quizzes_impl_HTMLTools_5($this, $appendpos, $character, $firstchar, $formula, $initag, $length, $parentpos, $parenttag, $parenttagname, $pos, $start, $text)));
						$character = com_wiris_util_xml_WXmlUtils::getUtf8Char($formula, $appendpos);
						$firstchar = false;
					}
					if(_hx_char_code_at($formula, $appendpos) !== 60) {
						$pos++;
						continue;
					}
					$nextpos = _hx_index_of($formula, ">", $pos);
					$end = false;
					while(!$end && $nextpos !== -1 && $pos + strlen(">") < strlen($formula)) {
						$nextpos += strlen(">");
						$nexttaglength = _hx_index_of($formula, ">", $nextpos) - $nextpos + strlen(">");
						$nexttag = _hx_substr($formula, $nextpos, $nexttaglength);
						$nexttagname = _hx_substr($nexttag, 1, strlen($nexttag) - 2);
						if(_hx_index_of($nexttagname, " ", null) !== -1) {
							$nexttagname = _hx_substr($nexttagname, 0, _hx_index_of($nexttagname, " ", null));
						}
						$specialtag = null;
						$speciallength = 0;
						if($nexttagname === "msup" || $nexttagname === "msub" || $nexttagname === "msubsup") {
							$specialtag = $nexttag;
							$speciallength = $nexttaglength;
							$nextpos = $nextpos + $nexttaglength;
							$nexttaglength = _hx_index_of($formula, ">", $nextpos) - $nextpos + strlen(">");
							$nexttag = _hx_substr($formula, $nextpos, $nexttaglength);
							$nexttagname = _hx_substr($nexttag, 1, strlen($nexttag) - 2);
							if(_hx_index_of($nexttagname, " ", null) !== -1) {
								$nexttagname = _hx_substr($nexttagname, 0, _hx_index_of($nexttagname, " ", null));
							}
						}
						if($nexttagname === "mi" || $nexttagname === "mn" || $nexttagname === "mo") {
							$contentpos = $nextpos + $nexttaglength;
							$toappend = new StringBuf();
							$character = com_wiris_util_xml_WXmlUtils::getUtf8Char($formula, $contentpos);
							while($this->isQuizzesIdentifierStart($character) || $this->isQuizzesIdentifierPart($character) && !$firstchar) {
								$charstr = com_wiris_quizzes_impl_HTMLTools_6($this, $appendpos, $character, $contentpos, $end, $firstchar, $formula, $initag, $length, $nextpos, $nexttag, $nexttaglength, $nexttagname, $parentpos, $parenttag, $parenttagname, $pos, $speciallength, $specialtag, $start, $text, $toappend);
								$contentpos += strlen($charstr);
								$toappend->add($charstr);
								$character = com_wiris_util_xml_WXmlUtils::getUtf8Char($formula, $contentpos);
								$firstchar = false;
								unset($charstr);
							}
							$toAppendStr = $toappend->b;
							$nextclosepos = _hx_index_of($formula, "<", $contentpos);
							$nextcloseend = _hx_index_of($formula, ">", $nextclosepos) + strlen(">");
							if(strlen($toAppendStr) === 0) {
								$end = true;
							} else {
								if($nextclosepos !== $contentpos) {
									$content = _hx_substr($formula, $contentpos, $nextclosepos - $contentpos);
									$nextclosetag = _hx_substr($formula, $nextclosepos, $nextcloseend - $nextclosepos);
									$newnexttag = $nexttag . $content . $nextclosetag;
									$formula = _hx_substr($formula, 0, $nextpos) . $newnexttag . _hx_substr($formula, $nextcloseend, null);
									$formula = _hx_substr($formula, 0, $appendpos) . $toAppendStr . _hx_substr($formula, $appendpos, null);
									$end = true;
									unset($nextclosetag,$newnexttag,$content);
								} else {
									$formula = _hx_substr($formula, 0, $nextpos) . _hx_substr($formula, $nextcloseend, null);
									$formula = _hx_substr($formula, 0, $appendpos) . $toAppendStr . _hx_substr($formula, $appendpos, null);
									if($specialtag !== null) {
										$fulltaglength = _hx_index_of($formula, ">", $appendpos) + strlen(">") - $initag;
										$formula = _hx_substr($formula, 0, $initag) . $specialtag . _hx_substr($formula, $initag, $fulltaglength) . _hx_substr($formula, $initag + $fulltaglength + $speciallength, null);
										$end = true;
										unset($fulltaglength);
									}
								}
							}
							$appendpos += strlen($toAppendStr);
							unset($toappend,$toAppendStr,$nextclosepos,$nextcloseend,$contentpos);
						} else {
							$end = true;
						}
						if(!$end) {
							$nextpos = _hx_index_of($formula, ">", $pos);
						}
						unset($specialtag,$speciallength,$nexttagname,$nexttaglength,$nexttag);
					}
					unset($nextpos,$firstchar,$end,$character,$appendpos);
				}
				$pos++;
				unset($parenttagname,$parenttag,$parentpos,$initag);
			}
			$text = _hx_substr($text, 0, $start) . $formula . _hx_substr($text, $start + $length, null);
			$start = $start + strlen($formula);
			unset($pos,$length,$formula);
		}
		return $text;
	}
	public function sortIterator($it) {
		$sorted = new _hx_array(array());
		while($it->hasNext()) {
			$a = $it->next();
			$j = 0;
			while($j < $sorted->length) {
				if(com_wiris_quizzes_impl_HTMLTools::compareStrings($sorted[$j], $a) > 0) {
					break;
				}
				$j++;
			}
			$sorted->insert($j, $a);
			unset($j,$a);
		}
		return $sorted;
	}
	public function getPlaceHolder($name) {
		return "#" . $name;
	}
	public function insideComment($html, $pos) {
		$beginComment = $this->lastIndexOf($html, "<!--", $pos);
		if($beginComment !== -1) {
			$endComment = $this->lastIndexOf($html, "-->", $pos);
			return $endComment < $beginComment;
		}
		return false;
	}
	public function lastIndexOf($src, $str, $pos) {
		return _hx_last_index_of(_hx_substr($src, 0, $pos), $str, null);
	}
	public function insideTag($html, $pos) {
		$beginTag = $this->lastIndexOf($html, "<", $pos);
		while($beginTag !== -1 && !$this->isQuizzesIdentifierStart(_hx_char_code_at($html, $beginTag + 1))) {
			if($beginTag === 0) {
				return false;
			}
			$beginTag = $this->lastIndexOf($html, "<", $beginTag - 1);
		}
		if($beginTag === -1) {
			return false;
		}
		$endTag = _hx_index_of($html, ">", $beginTag);
		return $endTag > $pos;
	}
	public function isQuizzesIdentifierPart($c) {
		return $this->isQuizzesIdentifierStart($c) || com_wiris_util_xml_WCharacterBase::isDigit($c);
	}
	public function isQuizzesIdentifierStart($c) {
		return com_wiris_util_xml_WCharacterBase::isLetter($c) || $c === 95;
	}
	public function isQuizzesIdentifier($s) {
		if($s === null || $s === "") {
			return false;
		}
		$c = haxe_Utf8::charCodeAt($s, 0);
		if(!$this->isQuizzesIdentifierStart($c)) {
			return false;
		}
		$i = strlen((com_wiris_quizzes_impl_HTMLTools_7($this, $c, $s)));
		while($i < strlen($s)) {
			$c = haxe_Utf8::charCodeAt(_hx_substr($s, $i, null), 0);
			if(!$this->isQuizzesIdentifierPart($c)) {
				return false;
			}
			$i += strlen((com_wiris_quizzes_impl_HTMLTools_8($this, $c, $i, $s)));
		}
		return true;
	}
	public function getVariableName($html, $pos) {
		$name = null;
		if(_hx_char_code_at($html, $pos) === 35) {
			$end = $pos + 1;
			if($end < strlen($html)) {
				$c = com_wiris_util_xml_WXmlUtils::getUtf8Char($html, $end);
				if($this->isQuizzesIdentifierStart($c)) {
					$end += strlen((com_wiris_quizzes_impl_HTMLTools_9($this, $c, $end, $html, $name, $pos)));
					if($end < strlen($html)) {
						$c = com_wiris_util_xml_WXmlUtils::getUtf8Char($html, $end);
						while($c > 0 && $this->isQuizzesIdentifierPart($c)) {
							$end += strlen((com_wiris_quizzes_impl_HTMLTools_10($this, $c, $end, $html, $name, $pos)));
							$c = (($end < strlen($html)) ? com_wiris_util_xml_WXmlUtils::getUtf8Char($html, $end) : -1);
						}
					}
					$name = _hx_substr($html, $pos + 1, $end - ($pos + 1));
				}
			}
		}
		return $name;
	}
	public function replaceVariablesInsideHTML($token, $variables, $type) {
		$mathml = $type === com_wiris_quizzes_impl_MathContent::$TYPE_MATHML;
		$text = $type === com_wiris_quizzes_impl_MathContent::$TYPE_TEXT;
		$imageRef = $type === com_wiris_quizzes_impl_MathContent::$TYPE_IMAGE_REF;
		$imageData = $type === com_wiris_quizzes_impl_MathContent::$TYPE_IMAGE;
		$keys = $this->sortIterator($variables->keys());
		$j = $keys->length - 1;
		while($j >= 0) {
			$name = $keys[$j];
			$placeholder = $this->getPlaceHolder($name);
			$pos = 0;
			while(($pos = _hx_index_of($token, $placeholder, $pos)) !== -1) {
				$v = $this->variablePosition($token, $pos);
				if(($v === com_wiris_quizzes_impl_HTMLTools::$POSITION_ALL || $v === com_wiris_quizzes_impl_HTMLTools::$POSITION_TABLE || $text && $v === com_wiris_quizzes_impl_HTMLTools::$POSITION_ONLY_TEXT || $mathml && $v === com_wiris_quizzes_impl_HTMLTools::$POSITION_ONLY_MATHML) && $name === $this->getVariableName($token, $pos)) {
					$value = $variables->get($name);
					if($text) {
						$value = com_wiris_util_xml_WXmlUtils::htmlEscape($value);
					} else {
						if($mathml) {
							$value = $this->addMathTag($value);
							$value = $this->extractTextFromMathML($value);
						} else {
							if($imageRef) {
								$value = $this->addPlotterImageTag($value);
							} else {
								if($imageData) {
									$value = $this->addPlotterImageB64Tag($value);
								}
							}
						}
					}
					$token = _hx_substr($token, 0, $pos) . $value . _hx_substr($token, $pos + strlen($placeholder), null);
					$pos += strlen($value);
					unset($value);
				} else {
					$pos++;
				}
				unset($v);
			}
			$j--;
			unset($pos,$placeholder,$name);
		}
		return $token;
	}
	public function removeRootTag($xml, $name) {
		if(StringTools::startsWith($xml, "<" . $name) && StringTools::endsWith($xml, "</" . $name . ">")) {
			$xml = _hx_substr($xml, _hx_index_of($xml, ">", null) + 1, null);
			$xml = _hx_substr($xml, 0, strlen($xml) - (strlen($name) + 3));
		}
		return $xml;
	}
	public function parseTabularVariableMathML($value) {
		$parts = new _hx_array(array());
		$value = $this->removeRootTag($value, "math");
		$value = $this->removeRootTag($value, "mrow");
		$value = $this->removeRootTag($value, "mfenced");
		$value = $this->removeRootTag($value, "mrow");
		$level = 0;
		$end = 0;
		$start = null;
		$lastindex = 0;
		while(($start = _hx_index_of($value, "<", $end)) !== -1) {
			$closing = false;
			$end = _hx_index_of($value, ">", $start);
			if(_hx_char_code_at($value, $start + 1) === _hx_char_code_at("/", 0)) {
				$start++;
				$closing = true;
			}
			$name = _hx_substr($value, $start + 1, $end - $start - 1);
			if(!$closing) {
				$aux = _hx_index_of($name, " ", null);
				if($aux !== -1) {
					$name = _hx_substr($name, 0, $aux);
				}
				if($name === "mo" && !$closing) {
					$op = _hx_substr($value, $end + 1, 1);
					if($op === "{" || $op === "[" || $op === "(") {
						$level++;
					} else {
						if($op === "}" || $op === "]" || $op === ")") {
							$level--;
						} else {
							if($op === $this->separator && $level === 0) {
								$parts->push($this->addMathTag(_hx_substr($value, $lastindex, $start - $lastindex)));
								$lastindex = $end + 7;
							}
						}
					}
					unset($op);
				}
				unset($aux);
			}
			if($name === "mfenced") {
				$level += (($closing) ? -1 : 1);
			}
			unset($name,$closing);
		}
		$parts->push($this->addMathTag(_hx_substr($value, $lastindex, null)));
		return $parts;
	}
	public function parseTabularVariableText($value) {
		$parts = new _hx_array(array());
		$value = _hx_substr($value, 1, strlen($value) - 2);
		$s = (($this->separator !== null) ? _hx_char_code_at($this->separator, 0) : _hx_char_code_at(",", 0));
		$i = null;
		$level = 0;
		$token = new StringBuf();
		$open = new _hx_array(array(_hx_char_code_at("{", 0), _hx_char_code_at("[", 0), _hx_char_code_at("(", 0)));
		$close = new _hx_array(array(_hx_char_code_at("}", 0), _hx_char_code_at("]", 0), _hx_char_code_at(")", 0)));
		{
			$_g1 = 0; $_g = strlen($value);
			while($_g1 < $_g) {
				$i1 = $_g1++;
				$c = _hx_char_code_at($value, $i1);
				if($c === $s && $level === 0) {
					$parts->push($token->b);
					$token = new StringBuf();
				} else {
					$token->b .= chr($c);
					if($c === $open[0] || $c === $open[1] || $c === $open[2]) {
						$level++;
					} else {
						if($c === $close[0] || $c === $close[1] || $c === $close[2]) {
							$level--;
						}
					}
				}
				unset($i1,$c);
			}
		}
		$parts->push($token->b);
		return $parts;
	}
	public function parseTabularVariable($name, $variables) {
		$v = $variables->get(com_wiris_quizzes_impl_MathContent::$TYPE_MATHML);
		if($v !== null && $v->exists($name)) {
			return $this->parseTabularVariableMathML($v->get($name));
		}
		$v = $variables->get(com_wiris_quizzes_impl_MathContent::$TYPE_TEXT);
		if($v !== null && $v->exists($name)) {
			return $this->parseTabularVariableText($v->get($name));
		}
		return null;
	}
	public function isTabularTextVariable($value) {
		$list = new EReg("[\\[\\{]([^\\" . $this->separator . "]*\\" . $this->separator . ")+[^\\}\\]]+[\\}\\]]", "");
		return $list->match($value);
	}
	public function isTabularMathMLVariable($value) {
		$list = new EReg("(<math[^>]*>)?<mfenced(\\s+open\\s*=\\s*\"[\\[\\{]\"|\\s+close\\s*=\\s*\"[\\]\\}]\"){2}\\s*><mrow>([^\\" . $this->separator . "]*<mo>\\" . $this->separator . "</mo>)+[^\\" . $this->separator . "]*</mrow></mfenced>(</math>)?", "g");
		return $list->match($value);
	}
	public function isCellExpandable($td, $variables) {
		$content = trim(com_wiris_quizzes_impl_HTMLTools::stripTags($td));
		if(StringTools::startsWith($content, "#")) {
			$content = _hx_substr($content, 1, null);
			if(_hx_index_of($td, "#" . $content, null) !== -1) {
				$v = $variables->get(com_wiris_quizzes_impl_MathContent::$TYPE_MATHML);
				if($v !== null && $v->exists($content) && $this->isTabularMathMLVariable($v->get($content))) {
					return true;
				}
				$v = $variables->get(com_wiris_quizzes_impl_MathContent::$TYPE_TEXT);
				if($v !== null && $v->exists($content) && $this->isTabularTextVariable($v->get($content))) {
					return true;
				}
			}
		}
		return false;
	}
	public function setClass($element, $name) {
		$end = _hx_index_of($element, ">", null);
		if($end !== -1) {
			$tag = _hx_substr($element, 0, $end + 1);
			$e = new EReg("<\\w+[^>]*\\s+class\\s*=\\s*\"[^\"]*\"[^>]*>", "g");
			if(!$e->match($tag)) {
				$tag = _hx_substr($tag, 0, $end) . " class=\"" . $name . "\">";
				$element = $tag . _hx_substr($element, $end + 1, null);
			}
		}
		return $element;
	}
	public function replaceVariablesInsideHTMLTables($html, $variables) {
		$tend = 0;
		$tstart = null;
		while(($tstart = _hx_index_of($html, "<table", $tend)) !== -1) {
			$tend = _hx_index_of($html, "</table>", $tstart);
			if($tend === -1) {
				return $html;
			}
			$tend += strlen("</table>");
			$table = _hx_substr($html, $tstart, $tend - $tstart);
			$rows = $this->splitTableRows($table);
			$grid = $this->parseTableCells($rows);
			$expanded = $this->expandHorizontal($rows, $grid, $variables);
			if($expanded === null) {
				$expanded = $this->expandVertical($rows, $grid, $variables);
			}
			if($expanded !== null) {
				$expanded = $this->setClass($expanded, "wiristable");
				$html = _hx_substr($html, 0, $tstart) . $expanded . _hx_substr($html, $tend, null);
				$tend = $tstart + strlen($expanded);
			}
			unset($table,$rows,$grid,$expanded);
		}
		return $html;
	}
	public function expandVertical($rows, $grid, $variables) {
		$i = null;
		{
			$_g1 = 0; $_g = $grid->length;
			while($_g1 < $_g) {
				$i1 = $_g1++;
				$thisrow = true;
				$j = 0;
				while($thisrow && $j < _hx_array_get($grid, $i1)->length) {
					$thisrow = $this->isCellExpandable($grid[$i1][$j], $variables);
					$j++;
				}
				if($thisrow && $j > 0) {
					$opentds = new _hx_array(array());
					$closetds = new _hx_array(array());
					$vars = new _hx_array(array());
					$n = -1;
					{
						$_g3 = 0; $_g2 = _hx_array_get($grid, $i1)->length;
						while($_g3 < $_g2) {
							$j1 = $_g3++;
							$model = $grid[$i1][$j1];
							$placeholder = trim(com_wiris_quizzes_impl_HTMLTools::stripTags($model));
							$pos = _hx_index_of($model, $placeholder, null);
							$opentds[$j1] = _hx_substr($model, 0, $pos);
							$closetds[$j1] = _hx_substr($model, $pos + strlen($placeholder), null);
							$parsed = $this->parseTabularVariable(_hx_substr($placeholder, 1, null), $variables);
							$vars->push($parsed);
							if($parsed->length > $n) {
								$n = $parsed->length;
							}
							unset($pos,$placeholder,$parsed,$model,$j1);
						}
						unset($_g3,$_g2);
					}
					$original = $rows[2 * $i1 + 1];
					$prefix = _hx_substr($original, 0, _hx_index_of($original, "<td", null));
					$suffix = _hx_substr($original, _hx_last_index_of($original, "</td>", null) + strlen("</td>"), null);
					$row = new StringBuf();
					$k = null;
					{
						$_g2 = 0;
						while($_g2 < $n) {
							$k1 = $_g2++;
							$row->add($prefix);
							{
								$_g4 = 0; $_g3 = $opentds->length;
								while($_g4 < $_g3) {
									$j1 = $_g4++;
									$row->add($opentds[$j1]);
									if($k1 < _hx_array_get($vars, $j1)->length) {
										$row->add($vars[$j1][$k1]);
									}
									$row->add($closetds[$j1]);
									unset($j1);
								}
								unset($_g4,$_g3);
							}
							$row->add($suffix);
							unset($k1);
						}
						unset($_g2);
					}
					$rows[2 * $i1 + 1] = $row->b;
					return $rows->join("");
					unset($vars,$suffix,$row,$prefix,$original,$opentds,$n,$k,$closetds);
				}
				unset($thisrow,$j,$i1);
			}
		}
		return null;
	}
	public function reconstructHorizontalExpand($rows, $grid) {
		$newTable = new StringBuf();
		$i = null;
		{
			$_g1 = 0; $_g = $grid->length;
			while($_g1 < $_g) {
				$i1 = $_g1++;
				$newTable->add($rows[2 * $i1]);
				$row = $rows[2 * $i1 + 1];
				$newTable->add(_hx_substr($row, 0, _hx_index_of($row, "<td", null)));
				$j = null;
				{
					$_g3 = 0; $_g2 = _hx_array_get($grid, $i1)->length;
					while($_g3 < $_g2) {
						$j1 = $_g3++;
						$newTable->add($grid[$i1][$j1]);
						unset($j1);
					}
					unset($_g3,$_g2);
				}
				$newTable->add(_hx_substr($row, _hx_last_index_of($row, "</td>", null) + strlen("</td>"), null));
				unset($row,$j,$i1);
			}
		}
		$newTable->add($rows[2 * $grid->length]);
		return $newTable->b;
	}
	public function expandHorizontal($rows, $grid, $variables) {
		$i = 0;
		$end = false;
		while(!$end) {
			$thiscolumn = true;
			$j = 0;
			while($j < $grid->length && $thiscolumn && !$end) {
				$thiscell = false;
				if($i < _hx_array_get($grid, $j)->length) {
					$thiscell = $this->isCellExpandable($grid[$j][$i], $variables);
				} else {
					$end = true;
				}
				$thiscolumn = $thiscolumn && $thiscell;
				$j++;
				unset($thiscell);
			}
			$end = $end || $j === 0;
			if($thiscolumn && !$end) {
				$end = true;
				{
					$_g1 = 0; $_g = $grid->length;
					while($_g1 < $_g) {
						$j1 = $_g1++;
						$model = $grid[$j1][$i];
						$placeholder = trim(com_wiris_quizzes_impl_HTMLTools::stripTags($model));
						$pos = _hx_index_of($model, $placeholder, null);
						$prefix = _hx_substr($model, 0, $pos);
						$suffix = _hx_substr($model, $pos + strlen($placeholder), null);
						$sb = new StringBuf();
						$parsed = $this->parseTabularVariable(_hx_substr($placeholder, 1, null), $variables);
						$k = null;
						{
							$_g3 = 0; $_g2 = $parsed->length;
							while($_g3 < $_g2) {
								$k1 = $_g3++;
								$sb->add($prefix);
								$sb->add($parsed[$k1]);
								$sb->add($suffix);
								unset($k1);
							}
							unset($_g3,$_g2);
						}
						$grid[$j1][$i] = $sb->b;
						unset($suffix,$sb,$prefix,$pos,$placeholder,$parsed,$model,$k,$j1);
					}
					unset($_g1,$_g);
				}
				return $this->reconstructHorizontalExpand($rows, $grid);
			}
			$i++;
			unset($thiscolumn,$j);
		}
		return null;
	}
	public function parseTableCells($rows) {
		$grid = new _hx_array(array());
		$i = 1;
		while($i < $rows->length) {
			$cells = new _hx_array(array());
			$row = $rows[$i];
			$tdstart = null;
			$tdend = 0;
			while(($tdstart = _hx_index_of($row, "<td", $tdend)) !== -1) {
				$tdend = _hx_index_of($row, "</td>", $tdstart);
				if($tdend === -1) {
					$tdend = strlen($row);
				} else {
					$tdend += strlen("</td>");
				}
				$cells->push(_hx_substr($row, $tdstart, $tdend - $tdstart));
			}
			$grid->push($cells);
			$i += 2;
			unset($tdstart,$tdend,$row,$cells);
		}
		return $grid;
	}
	public function splitTableRows($table) {
		$rows = new _hx_array(array());
		$trend = 0;
		$trstart = null;
		while(($trstart = _hx_index_of($table, "<tr", $trend)) !== -1) {
			$rows->push(_hx_substr($table, $trend, $trstart - $trend));
			$trend = _hx_index_of($table, "</tr>", $trstart);
			if($trend === -1) {
				$last = $rows[$rows->length - 1];
				$rows[$rows->length - 1] = $last . _hx_substr($table, $trstart, null);
				$trend = strlen($table);
				unset($last);
			} else {
				$trend += strlen("</tr>");
				$rows->push(_hx_substr($table, $trstart, $trend - $trstart));
			}
		}
		if($trend < strlen($table)) {
			$rows->push(_hx_substr($table, $trend, null));
		}
		return $rows;
	}
	public function replaceMathMLVariablesInsideMathML($formula, $variables) {
		$keys = $this->sortIterator($variables->keys());
		$j = $keys->length - 1;
		while($j >= 0) {
			$name = $keys[$j];
			$placeholder = $this->getPlaceHolder($name);
			$pos = 0;
			while(($pos = _hx_index_of($formula, $placeholder, $pos)) !== -1) {
				if($this->variablePosition($formula, $pos) >= 2) {
					$value = $this->toSubFormula($variables->get($name));
					$splittag = false;
					$formula1 = _hx_substr($formula, 0, $pos);
					$formula2 = _hx_substr($formula, $pos + strlen($placeholder), null);
					$openTag1 = _hx_last_index_of($formula1, "<", null);
					$closeTag1 = _hx_last_index_of($formula1, ">", null);
					$openTag2 = _hx_index_of($formula2, "<", null);
					$closeTag2 = _hx_index_of($formula2, ">", null);
					$after = "";
					$before = "";
					if($closeTag1 + 1 < strlen($formula1)) {
						$splittag = true;
						$closeTag = _hx_substr($formula2, $openTag2, $closeTag2 - $openTag2 + 1);
						$before = _hx_substr($formula1, $openTag1, null) . $closeTag;
						unset($closeTag);
					}
					if($openTag2 > 0) {
						$splittag = true;
						$openTag = _hx_substr($formula1, $openTag1, $closeTag1 - $openTag1 + 1);
						$after = $openTag . _hx_substr($formula2, 0, $closeTag2 + 1);
						unset($openTag);
					}
					$tag1 = _hx_substr($formula1, $openTag1, $closeTag1 + 1 - $openTag1);
					$space = _hx_index_of($tag1, " ", null);
					if($space !== -1) {
						$attribs = _hx_substr($tag1, $space + 1, strlen($tag1) - 1 - ($space + 1));
						$value = "<mstyle " . $attribs . ">" . $value . "</mstyle>";
						unset($attribs);
					}
					$formula1 = _hx_substr($formula1, 0, $openTag1);
					$formula2 = _hx_substr($formula2, $closeTag2 + 1, null);
					if($splittag) {
						$formula = $formula1 . "<mrow>" . $before . $value . $after . "</mrow>" . $formula2;
					} else {
						$formula = $formula1 . $value . $formula2;
					}
					unset($value,$tag1,$splittag,$space,$openTag2,$openTag1,$formula2,$formula1,$closeTag2,$closeTag1,$before,$after);
				}
				$pos++;
			}
			$j--;
			unset($pos,$placeholder,$name);
		}
		return $formula;
	}
	public function splitHTMLbyMathML($html) {
		$tokens = new _hx_array(array());
		$start = 0;
		$end = 0;
		while(($start = _hx_index_of($html, "<math", $end)) !== -1) {
			if($start - $end > 0) {
				$tokens->push(_hx_substr($html, $end, $start - $end));
			}
			$firstClose = _hx_index_of($html, ">", $start);
			if($firstClose !== -1 && _hx_substr($html, $firstClose - 1, 1) === "/") {
				$end = $firstClose + 1;
			} else {
				$end = _hx_index_of($html, "</math>", $start) + strlen("</math>");
			}
			$tokens->push(_hx_substr($html, $start, $end - $start));
			unset($firstClose);
		}
		if($end < strlen($html)) {
			$tokens->push(_hx_substr($html, $end, null));
		}
		return $tokens;
	}
	public function expandVariables($html, $variables) {
		if($variables === null || _hx_index_of($html, "#", null) === -1) {
			return $html;
		}
		$encoded = $this->isMathMLEncoded($html);
		if($encoded) {
			$html = $this->decodeMathML($html);
		}
		$html = com_wiris_util_xml_WXmlUtils::resolveEntities($html);
		$html = $this->prepareFormulas($html);
		$tokens = $this->splitHTMLbyMathML($html);
		$sb = new StringBuf();
		$i = null;
		{
			$_g1 = 0; $_g = $tokens->length;
			while($_g1 < $_g) {
				$i1 = $_g1++;
				$token = $tokens[$i1];
				$v = null;
				if(StringTools::startsWith($token, "<math")) {
					$v = $variables->get(com_wiris_quizzes_impl_MathContent::$TYPE_MATHML);
					if($v !== null) {
						$token = $this->replaceMathMLVariablesInsideMathML($token, $v);
					}
					$v = $variables->get(com_wiris_quizzes_impl_MathContent::$TYPE_TEXT);
					if($v !== null) {
						$token = $this->replaceMathMLVariablesInsideMathML($token, $v);
					}
				} else {
					$v = $variables->get(com_wiris_quizzes_impl_MathContent::$TYPE_IMAGE_REF);
					if($v !== null) {
						$token = $this->replaceVariablesInsideHTML($token, $v, com_wiris_quizzes_impl_MathContent::$TYPE_IMAGE_REF);
					}
					$v = $variables->get(com_wiris_quizzes_impl_MathContent::$TYPE_IMAGE);
					if($v !== null) {
						$token = $this->replaceVariablesInsideHTML($token, $v, com_wiris_quizzes_impl_MathContent::$TYPE_IMAGE);
					}
					$token = $this->replaceVariablesInsideHTMLTables($token, $variables);
					$v = $variables->get(com_wiris_quizzes_impl_MathContent::$TYPE_MATHML);
					if($v !== null) {
						$token = $this->replaceVariablesInsideHTML($token, $v, com_wiris_quizzes_impl_MathContent::$TYPE_MATHML);
					}
					$v = $variables->get(com_wiris_quizzes_impl_MathContent::$TYPE_TEXT);
					if($v !== null) {
						$token = $this->replaceVariablesInsideHTML($token, $v, com_wiris_quizzes_impl_MathContent::$TYPE_TEXT);
					}
				}
				$sb->add($token);
				unset($v,$token,$i1);
			}
		}
		$result = $sb->b;
		if($encoded) {
			$result = $this->encodeMathML($result);
		}
		return $result;
	}
	public function encodeMathML($html) {
		$opentag = "«";
		$closetag = "»";
		$quote = "¨";
		$amp = "§";
		$start = null;
		$end = 0;
		while(($start = _hx_index_of($html, "<math", $end)) !== -1) {
			$closemath = "</math>";
			$end = _hx_index_of($html, $closemath, $start) + strlen($closemath);
			$formula = _hx_substr($html, $start, $end - $start);
			$formula = str_replace("<", $opentag, $formula);
			$formula = str_replace(">", $closetag, $formula);
			$formula = str_replace("\"", $quote, $formula);
			$formula = str_replace("&", $amp, $formula);
			$html = _hx_substr($html, 0, $start) . $formula . _hx_substr($html, $end, null);
			$end = $start + strlen($formula);
			unset($formula,$closemath);
		}
		return $html;
	}
	public function decodeMathML($html) {
		$opentag = "«";
		$closetag = "»";
		$quote = "¨";
		$amp = "§";
		$closemath = $opentag . "/math" . $closetag;
		$start = null;
		$end = 0;
		while(($start = _hx_index_of($html, $opentag . "math", $end)) !== -1) {
			$end = _hx_index_of($html, $closemath, $start) + strlen($closemath);
			$formula = _hx_substr($html, $start, $end - $start);
			$formula = com_wiris_util_xml_WXmlUtils::htmlUnescape($formula);
			$formula = str_replace($opentag, "<", $formula);
			$formula = str_replace($closetag, ">", $formula);
			$formula = str_replace($quote, "\"", $formula);
			$formula = str_replace($amp, "&", $formula);
			$html = _hx_substr($html, 0, $start) . $formula . _hx_substr($html, $end, null);
			$end = $start + strlen($formula);
			unset($formula);
		}
		return $html;
	}
	public function isMathMLEncoded($html) {
		$opentag = "«";
		return _hx_index_of($html, $opentag . "math", null) !== -1;
	}
	public function extractVariableNames($html) {
		if($this->isMathMLEncoded($html)) {
			$html = $this->decodeMathML($html);
		}
		$html = com_wiris_util_xml_WXmlUtils::resolveEntities($html);
		$html = $this->prepareFormulas($html);
		$names = new _hx_array(array());
		$start = 0;
		while(($start = _hx_index_of($html, "#", $start)) !== -1) {
			if($this->variablePosition($html, $start) > 0) {
				$name = $this->getVariableName($html, $start);
				com_wiris_quizzes_impl_HTMLTools::insertStringInSortedArray($name, $names);
				unset($name);
			}
			$start++;
		}
		return com_wiris_quizzes_impl_HTMLTools::toNativeArray($names);
	}
	public $separator = ",";
	public function __call($m, $a) {
		if(isset($this->$m) && is_callable($this->$m))
			return call_user_func_array($this->$m, $a);
		else if(isset($this->�dynamics[$m]) && is_callable($this->�dynamics[$m]))
			return call_user_func_array($this->�dynamics[$m], $a);
		else if('toString' == $m)
			return $this->__toString();
		else
			throw new HException('Unable to call �'.$m.'�');
	}
	static $POSITION_NONE = -1;
	static $POSITION_ONLY_TEXT = 1;
	static $POSITION_ONLY_MATHML = 2;
	static $POSITION_ALL = 3;
	static $POSITION_TABLE = 4;
	static $MROWS = "@math@mrow@msqrt@mstyle@merror@mpadded@mphantom@mtd@menclose@mscarry@msrow@";
	static $MSUPS = "@msub@msup@msubsup@";
	static function toNativeArray($a) {
		$n = new _hx_array(array());
		$k = null;
		{
			$_g1 = 0; $_g = $a->length;
			while($_g1 < $_g) {
				$k1 = $_g1++;
				$n[$k1] = $a[$k1];
				unset($k1);
			}
		}
		return $n;
	}
	static function insertStringInSortedArray($s, $a) {
		if($s !== null) {
			$i = 0;
			while($i < $a->length) {
				if(com_wiris_quizzes_impl_HTMLTools::compareStrings($a[$i], $s) >= 0) {
					break;
				}
				$i++;
			}
			if($i < $a->length) {
				if(!($a[$i] === $s)) {
					$a->insert($i, $s);
				}
			} else {
				$a->push($s);
			}
		}
	}
	static function encodeUnicodeChars($mathml) {
		$sb = new StringBuf();
		$i = null;
		{
			$_g1 = 0; $_g = strlen($mathml);
			while($_g1 < $_g) {
				$i1 = $_g1++;
				$c = _hx_char_code_at($mathml, $i1);
				if($c > 127) {
					$sb->add("&#");
					$sb->add($c);
					$sb->add(";");
				} else {
					$sb->b .= chr($c);
				}
				unset($i1,$c);
			}
		}
		return $sb->b;
	}
	static function stripTags($html) {
		$e = new EReg("<[^>]*>", "g");
		return $e->replace($html, "");
	}
	static function compareStrings($a, $b) {
		$i = null;
		$an = strlen($a);
		$bn = strlen($b);
		$n = (($an > $bn) ? $bn : $an);
		{
			$_g = 0;
			while($_g < $n) {
				$i1 = $_g++;
				$c = _hx_char_code_at($a, $i1) - _hx_char_code_at($b, $i1);
				if($c !== 0) {
					return $c;
				}
				unset($i1,$c);
			}
		}
		return strlen($a) - strlen($b);
	}
	static function stripRootTag($xml, $tag) {
		$xml = trim($xml);
		if(StringTools::startsWith($xml, "<" . $tag)) {
			$xml = _hx_substr($xml, _hx_index_of($xml, ">", null) + 1, null);
			$xml = _hx_substr($xml, 0, _hx_last_index_of($xml, "<", null));
		}
		return $xml;
	}
	static function ensureRootTag($xml, $tag) {
		$xml = trim($xml);
		if(!StringTools::startsWith($xml, "<" . $tag)) {
			$xml = "<" . $tag . ">" . $xml . "</" . $tag . ">";
		}
		return $xml;
	}
	static function parseCompoundAnswer($correctAnswer) {
		if($correctAnswer->content !== null && com_wiris_quizzes_impl_MathContent::$TYPE_TEXT === $correctAnswer->type) {
			return com_wiris_quizzes_impl_HTMLTools::parseCompoundAnswerText($correctAnswer);
		} else {
			if($correctAnswer->content !== null && com_wiris_quizzes_impl_MathContent::$TYPE_MATHML === $correctAnswer->type) {
				return com_wiris_quizzes_impl_HTMLTools::parseCompoundAnswerMathML($correctAnswer);
			} else {
				return new _hx_array(array());
			}
		}
	}
	static function parseCompoundAnswerText($correctAnswer) {
		$answers = new _hx_array(array());
		$text = $correctAnswer->content;
		$lines = _hx_explode("\x0A", $text);
		$i = null;
		{
			$_g1 = 0; $_g = $lines->length;
			while($_g1 < $_g) {
				$i1 = $_g1++;
				$line = $lines[$i1];
				$p = _hx_index_of($line, "=", null);
				if($p !== -1) {
					$label = _hx_substr($line, 0, $p + 1);
					$value = trim(_hx_substr($line, $p + 1, null));
					$answers->push(new _hx_array(array($label, $value)));
					unset($value,$label);
				}
				unset($p,$line,$i1);
			}
		}
		return $answers;
	}
	static function parseCompoundAnswerMathML($correctAnswer) {
		$answers = new _hx_array(array());
		$newline = "<mspace linebreak=\"newline\"/>";
		$equal = "<mo>=</mo>";
		$mml = com_wiris_quizzes_impl_HTMLTools::convertEditor2Newlines($correctAnswer->content);
		$mml = com_wiris_quizzes_impl_HTMLTools::stripRootTag($mml, "math");
		$mml = com_wiris_quizzes_impl_HTMLTools::stripRootTag($mml, "mrow");
		$lines = new _hx_array(array());
		$end = 0;
		$start = 0;
		while(($end = _hx_index_of($mml, $newline, $start)) !== -1) {
			$lines->push(_hx_substr($mml, $start, $end - $start));
			$start = $end + strlen($newline);
		}
		$lines->push(_hx_substr($mml, $start, null));
		$i = null;
		{
			$_g1 = 0; $_g = $lines->length;
			while($_g1 < $_g) {
				$i1 = $_g1++;
				$line = com_wiris_quizzes_impl_HTMLTools::stripRootTag($lines[$i1], "mrow");
				$equalIndex = _hx_index_of($line, $equal, null);
				if($equalIndex !== -1) {
					$equalIndex += strlen($equal);
					$label = com_wiris_quizzes_impl_HTMLTools::ensureRootTag(_hx_substr($line, 0, $equalIndex), "math");
					$value = _hx_substr($line, $equalIndex, null);
					$a = _hx_index_of($value, "<annotation", null);
					if($a !== -1) {
						$a = _hx_index_of($value, ">", $a) + 1;
						$b = _hx_index_of($value, "</annotation>", $a);
						$value = _hx_substr($value, $a, $b - $a);
						unset($b);
					} else {
						$value = com_wiris_quizzes_impl_HTMLTools::ensureRootTag($value, "math");
					}
					$answer = new _hx_array(array($label, $value));
					$answers->push($answer);
					unset($value,$label,$answer,$a);
				}
				unset($line,$i1,$equalIndex);
			}
		}
		return $answers;
	}
	static function joinCompoundAnswer($answers) {
		$sb = new StringBuf();
		$m = new com_wiris_quizzes_impl_MathContent();
		if($answers->length > 0) {
			$mml = com_wiris_quizzes_impl_MathContent::getMathType($answers[0][0]) === com_wiris_quizzes_impl_MathContent::$TYPE_MATHML;
			$m->type = com_wiris_quizzes_impl_HTMLTools_11($answers, $m, $mml, $sb);
			$i = null;
			{
				$_g1 = 0; $_g = $answers->length;
				while($_g1 < $_g) {
					$i1 = $_g1++;
					if($i1 !== 0) {
						$sb->add((($mml) ? "<mspace linebreak=\"newline\"/>" : "\x0A"));
					}
					$ans = $answers[$i1];
					$sb->add(com_wiris_quizzes_impl_HTMLTools::stripRootTag($ans[0], "math"));
					$sb->add(com_wiris_quizzes_impl_HTMLTools::stripRootTag($ans[1], "math"));
					unset($i1,$ans);
				}
			}
			$m->content = $sb->b;
			if($mml) {
				$m->content = com_wiris_quizzes_impl_HTMLTools::ensureRootTag($m->content, "math");
			}
		} else {
			$m->set("");
		}
		return $m;
	}
	static function tagName($xml, $n) {
		$endtag = _hx_index_of($xml, ">", $n);
		$tag = _hx_substr($xml, $n + 1, $endtag - ($n + 1));
		$aux = null;
		if(($aux = _hx_index_of($tag, " ", null)) !== -1) {
			$tag = _hx_substr($tag, 0, $aux);
		}
		return $tag;
	}
	static function endTag($xml, $n) {
		$name = com_wiris_quizzes_impl_HTMLTools::tagName($xml, $n);
		$depth = 1;
		$pos = $n + 1;
		while($depth > 0) {
			$pos = _hx_index_of($xml, "<", $pos);
			if($pos === -1) {
				return strlen($xml);
			} else {
				if(_hx_substr($xml, _hx_index_of($xml, ">", $pos) - 1, 1) === "/") {
				} else {
					if(_hx_substr($xml, $pos + 1, 1) === "/") {
						if(com_wiris_quizzes_impl_HTMLTools::tagName($xml, $pos + 1) === $name) {
							$depth--;
						}
					} else {
						if(com_wiris_quizzes_impl_HTMLTools::tagName($xml, $pos) === $name) {
							$depth++;
						}
					}
				}
			}
			$pos = $pos + 1;
		}
		$pos = _hx_index_of($xml, ">", $pos) + 1;
		return $pos;
	}
	static function convertEditor2Newlines($mml) {
		$head = "<mtable columnalign=\"left\" rowspacing=\"0\">";
		$start = null;
		if(($start = _hx_index_of($mml, $head, null)) !== -1) {
			$start += strlen($head);
			$end = _hx_last_index_of($mml, "</mtable>", null);
			$mml = _hx_substr($mml, $start, $end - $start);
			$start = 0;
			$sb = new StringBuf();
			$lines = 0;
			while(($start = _hx_index_of($mml, "<mtd>", $start)) !== -1) {
				if($lines !== 0) {
					$sb->add("<mspace linebreak=\"newline\"/>");
				}
				$end = com_wiris_quizzes_impl_HTMLTools::endTag($mml, $start);
				$start += 5;
				$end -= 6;
				$sb->add(_hx_substr($mml, $start, $end - $start));
				$start = $end + 6;
				$lines++;
			}
			$mml = $sb->b;
			$mml = com_wiris_quizzes_impl_HTMLTools::ensureRootTag($mml, "math");
		}
		return $mml;
	}
	static function emptyCasSession($value) {
		return $value === null || _hx_index_of($value, "<mo", null) === -1 && _hx_index_of($value, "<mi", null) === -1 && _hx_index_of($value, "<mn", null) === -1 && _hx_index_of($value, "<csymbol", null) === -1;
	}
	static function main() {
		$h = new com_wiris_quizzes_impl_HTMLTools();
		$h->unitTest();
	}
	function __toString() { return 'com.wiris.quizzes.impl.HTMLTools'; }
}
function com_wiris_quizzes_impl_HTMLTools_0(&$�this, &$close, &$e, &$i, &$it, &$n, &$open, &$sb, &$separators) {
	{
		$s = new haxe_Utf8(null);
		$s->addChar(haxe_Utf8::charCodeAt($separators, com_wiris_quizzes_impl_HTMLTools_12($close, $e, $i, $it, $n, $open, $s, $sb, $separators)));
		return $s->toString();
	}
}
function com_wiris_quizzes_impl_HTMLTools_1(&$�this, &$allowedTags, &$beginformula, &$end, &$formula, &$lasttag, &$omittedcontent, &$pos2, &$spacepos, &$stack, &$start, &$tag, &$text, &$trimmedTag) {
	{
		$s = new haxe_Utf8(null);
		$s->addChar(160);
		return $s->toString();
	}
}
function com_wiris_quizzes_impl_HTMLTools_2(&$�this, &$c, &$i, &$mathml, &$n, &$text, &$token) {
	{
		$s = new haxe_Utf8(null);
		$s->addChar($c);
		return $s->toString();
	}
}
function com_wiris_quizzes_impl_HTMLTools_3(&$�this, &$_g, &$c, &$i, &$j, &$j1, &$m, &$mathml, &$n, &$text, &$tok, &$token, &$tokens) {
	{
		$s = new haxe_Utf8(null);
		$s->addChar(haxe_Utf8::charCodeAt($tok, $j1));
		return $s->toString();
	}
}
function com_wiris_quizzes_impl_HTMLTools_4(&$�this, &$c, &$i, &$mathml, &$n, &$text, &$token) {
	{
		$s = new haxe_Utf8(null);
		$s->addChar($c);
		return $s->toString();
	}
}
function com_wiris_quizzes_impl_HTMLTools_5(&$�this, &$appendpos, &$character, &$firstchar, &$formula, &$initag, &$length, &$parentpos, &$parenttag, &$parenttagname, &$pos, &$start, &$text) {
	{
		$s = new haxe_Utf8(null);
		$s->addChar($character);
		return $s->toString();
	}
}
function com_wiris_quizzes_impl_HTMLTools_6(&$�this, &$appendpos, &$character, &$contentpos, &$end, &$firstchar, &$formula, &$initag, &$length, &$nextpos, &$nexttag, &$nexttaglength, &$nexttagname, &$parentpos, &$parenttag, &$parenttagname, &$pos, &$speciallength, &$specialtag, &$start, &$text, &$toappend) {
	{
		$s = new haxe_Utf8(null);
		$s->addChar($character);
		return $s->toString();
	}
}
function com_wiris_quizzes_impl_HTMLTools_7(&$�this, &$c, &$s) {
	{
		$s1 = new haxe_Utf8(null);
		$s1->addChar($c);
		return $s1->toString();
	}
}
function com_wiris_quizzes_impl_HTMLTools_8(&$�this, &$c, &$i, &$s) {
	{
		$s1 = new haxe_Utf8(null);
		$s1->addChar($c);
		return $s1->toString();
	}
}
function com_wiris_quizzes_impl_HTMLTools_9(&$�this, &$c, &$end, &$html, &$name, &$pos) {
	{
		$s = new haxe_Utf8(null);
		$s->addChar($c);
		return $s->toString();
	}
}
function com_wiris_quizzes_impl_HTMLTools_10(&$�this, &$c, &$end, &$html, &$name, &$pos) {
	{
		$s = new haxe_Utf8(null);
		$s->addChar($c);
		return $s->toString();
	}
}
function com_wiris_quizzes_impl_HTMLTools_11(&$answers, &$m, &$mml, &$sb) {
	if($mml) {
		return com_wiris_quizzes_impl_MathContent::$TYPE_MATHML;
	} else {
		return com_wiris_quizzes_impl_MathContent::$TYPE_TEXT;
	}
}
function com_wiris_quizzes_impl_HTMLTools_12(&$close, &$e, &$i, &$it, &$n, &$open, &$s, &$sb, &$separators) {
	if($i < $n) {
		return $i;
	} else {
		return $n - 1;
	}
}
