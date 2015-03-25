<?php
namespace ArminVieweg\Dce\ViewHelpers\Format;

/*  | This extension is part of the TYPO3 project. The TYPO3 project is free software and is                          *
 *  | licensed under GNU General Public License.                                                                ♥php  *
 *  | (c) 2012-2015 Armin Ruediger Vieweg <armin@v.ieweg.de>                                                          */

/**
 * WrapWithCurlyBraces Viewhelper
 *
 * @package ArminVieweg\Dce
 */
class WrapWithCurlyBracesViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Returns the given string with encircling curly braces
	 *
	 * @param string $subject
	 * @param string $prepend
	 * @param string $append
	 * @return string
	 */
	public function render($subject = NULL, $prepend = '', $append = '') {
		if ($subject === NULL) {
			$subject = $this->renderChildren();
		}
		return '{' . $prepend . $subject . $append . '}';
	}
}