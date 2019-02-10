<?php
namespace T3\Dce\Components\UserConditions;

/*  | This extension is made for TYPO3 CMS and is licensed
 *  | under GNU General Public License.
 *  |
 *  | (c) 2012-2019 Armin Vieweg <armin@v.ieweg.de>
 */
use T3\Dce\Utility\DatabaseUtility;
use TYPO3\CMS\Core\Configuration\TypoScript\ConditionMatching\AbstractCondition;

/**
 * Checks if the current page contains a DCE (instance).
 *
 * Usage in typoscript (8.7):
 * [T3\Dce\Components\UserConditions\DceOnCurrentPage = 42]
 * [T3\Dce\Components\UserConditions\DceOnCurrentPage = teaser]
 * or
 * [userFunc = ArminVieweg\Dce\UserConditions\user_dceOnCurrentPage(42)]
 * [userFunc = ArminVieweg\Dce\UserConditions\user_dceOnCurrentPage(teaser)]
 *
 * Usage in typoscript (9.5):
 * [dceOnCurrentPage("42")]
 * [dceOnCurrentPage("teaser")]
 *
 * You can pass the uid (e.g. 42) or the identifier (e.g. teaser).
 *
 * @param int|string $dceUidOrIdentifier Uid of DCE type to check for
 * @return bool Returns true if the current page contains a DCE (instance)
 */
class DceOnCurrentPage extends AbstractCondition
{
    /**
     * @param array $parameters
     * @param array $arguments See TypoScriptConditionFunctionProvider::getDceOnCurrentPageFunction()
     * @return bool
     */
    public function matchCondition(array $parameters, array $arguments = null) : bool
    {
        if (TYPO3_MODE !== 'FE') {
            return false;
        }

        $dceIdentifier = ltrim($parameters[0], " =");
        if (is_numeric($dceIdentifier)) {
            $dce = DatabaseUtility::getDatabaseConnection()->exec_SELECTgetSingleRow(
                '*',
                'tx_dce_domain_model_dce',
                'uid=' . $dceIdentifier . ' AND deleted=0'
            );
            if (!$dce) {
                return false;
            }
            $dceIdentifier = !empty($dce['identifier']) ? 'dce_' . $dce['identifier'] : 'dce_dceuid' . $dceIdentifier;
        } else {
            if (strpos($dceIdentifier, 'dce_') !== 0) {
                $dceIdentifier = 'dce_' . $dceIdentifier;
            }
        }

        $currentPageUid = $GLOBALS['TSFE']->id;
        if (isset($GLOBALS['TSFE']->page['content_from_pid']) && $GLOBALS['TSFE']->page['content_from_pid'] > 0) {
            $currentPageUid = $GLOBALS['TSFE']->page['content_from_pid'];
        }

        return \count(
            DatabaseUtility::getDatabaseConnection()->exec_SELECTgetRows(
                'uid',
                'tt_content',
                'pid=' . $currentPageUid . ' AND CType="' . $dceIdentifier . '" AND hidden=0 AND deleted=0'
            )
        ) > 0;
    }


}

