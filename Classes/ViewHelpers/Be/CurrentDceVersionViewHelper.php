<?php
namespace ArminVieweg\Dce\ViewHelpers\Be;

/*  | This extension is part of the TYPO3 project. The TYPO3 project is free software and is                          *
 *  | licensed under GNU General Public License.                                                                ♥php  *
 *  | (c) 2012-2015 Armin Ruediger Vieweg <armin@v.ieweg.de>                                                          */

/**
 * Gets the current version of DCE as integer
 *
 * @package ArminVieweg\Dce
 * @see t3lib_utility_VersionNumber::convertVersionNumberToInteger
 */
class CurrentDceVersionViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Be\AbstractBackendViewHelper {

	/**
	 * Returns the current version of DCE as int
	 *
	 * @return int Current DCE version
	 */
	public function render() {
		return \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger( \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getExtensionVersion('dce'));
	}
}