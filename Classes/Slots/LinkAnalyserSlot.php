<?php
namespace T3\Dce\Slots;

/*  | This extension is made with love for TYPO3 CMS and is licensed
 *  | under GNU General Public License.
 *  |
 *  | (c) 2012-2020 Armin Vieweg <armin@v.ieweg.de>
 */
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Linkvalidator\LinkAnalyzer;

/**
 * Class LinkAnalyserSlot
 */
class LinkAnalyserSlot
{
    /**
     * @param array $results
     * @param array $record
     * @param string $table
     * @param array $fields
     * @param LinkAnalyzer $linkAnalyser
     * @return array
     */
    public function beforeAnalyzeRecord(
        array $results,
        array $record,
        $table,
        array $fields,
        LinkAnalyzer $linkAnalyser
    ) : array {
        if ($table === 'tt_content' && !empty($record['pi_flexform'])) {
            $rawRecord = BackendUtility::getRecord('tt_content', $record['uid'], '*');
            if (strpos($rawRecord['CType'], 'dce_') !== 0) {
                return [$results, $record, $table, $fields, $linkAnalyser];
            }
            $flexformData = ArrayUtility::flatten(
                GeneralUtility::xml2array($record['pi_flexform'])
            );
            $newFlexformContent = '';
            foreach ($flexformData as $fieldValue) {
                if (!empty($fieldValue) && !is_numeric($fieldValue)) {
                    // Check for typolink (string, without new lines or < > signs)
                    if (\is_string($fieldValue) &&
                        strpos($fieldValue, "\n") === false &&
                        strpos($fieldValue, '<') === false &&
                        strpos($fieldValue, '>') === false
                    ) {
                        $fieldValue = '<a href="' . $fieldValue . '">Typolink</a>';
                    }
                    $newFlexformContent .= $fieldValue . "\n\n";
                }
            }
            $record['pi_flexform'] = $newFlexformContent;
            $GLOBALS['TCA'][$table]['columns']['pi_flexform']['config']['softref'] = 'typolink,typolink_tag,images,url';
        }
        return [$results, $record, $table, $fields, $linkAnalyser];
    }

    /**
     * @param \TYPO3\CMS\Linkvalidator\Event\BeforeRecordIsAnalyzedEvent $event
     */
    public function dispatchEvent($event)
    {
        [$results, $record] = $this->beforeAnalyzeRecord(
            $event->getResults(),
            $event->getRecord(),
            $event->getTableName(),
            $event->getFields(),
            $event->getLinkAnalyzer()
        );
        $event->setResults($results);
        $event->setRecord($record);
    }
}
