<?php
class seo42ext extends seo42 {

	public static function getGeneratedCSSMinFile($file, $vars = array()) {
		return res42ext::getGeneratedCSSMinFile($file, $vars);
	}

	public static function getCombinedCSSMinFile($combinedFile, $sourceFiles) {
		return res42ext::getCombinedCSSMinFile($combinedFile, $sourceFiles);
	}

	public static function getCombinedJSMinFile($combinedFile, $sourceFiles) {
		 return res42ext::getCombinedJSMinFile($combinedFile, $sourceFiles);
	}
}
