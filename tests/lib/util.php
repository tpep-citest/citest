<?php
/**
 * Copyright (c) 2012 Lukas Reschke <lukas@statuscode.ch>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

class Test_Util extends UnitTestCase {

	// Constructor
	function Test_Util() {
		date_default_timezone_set("UTC"); 
	}

	function testFormatDate() {
		$result = OC_Util::formatDate(1350129205);
		$expected = 'October 13, 2012, 11:53';
		$this->assertEquals($result, $expected);

		$result = OC_Util::formatDate(1102831200, true);
		$expected = 'December 12, 2004';
		$this->assertEquals($result, $expected);
	}

	function testCallRegister() {
		$result = strlen(OC_Util::callRegister());
		$this->assertEquals($result, 20);
	}

	function testSanitizeHTML() {
		$badString = "<script>alert('Hacked!');</script>";
		$result = OC_Util::sanitizeHTML($badString);
		$this->assertEquals($result, "&lt;script&gt;alert(&#039;Hacked!&#039;);&lt;/script&gt;");

		$goodString = "This is an harmless string.";
		$result = OC_Util::sanitizeHTML($goodString);
		$this->assertEquals($result, "This is an harmless string.");
	} 

	function testGenerate_random_bytes() {
		$result = strlen(OC_Util::generate_random_bytes(59));
		$this->assertEquals($result, 59);
	} 
}