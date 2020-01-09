<?php
declare(strict_types=1);
namespace In2code\Powermail\Utility;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\Exception;

/**
 * Class TypoScriptUtility
 */
class TypoScriptUtility extends AbstractUtility
{

    /**
     * Overwrite a string if a TypoScript cObject is available
     *
     * @param string $string Value to overwrite
     * @param array $conf TypoScript Configuration Array
     * @param string $key Key for TypoScript Configuration
     * @return void
     * @codeCoverageIgnore
     * @throws Exception
     */
    public static function overwriteValueFromTypoScript(
        string &$string = null,
        array $conf = [],
        string $key = ''
    ): void {
        if (self::getContentObject()->cObjGetSingle($conf[$key], $conf[$key . '.'])) {
            $string = self::getContentObject()->cObjGetSingle($conf[$key], $conf[$key . '.']);
        }
    }

    /**
     * Parse TypoScript from path like lib.blabla
     *
     * @param $typoScriptObjectPath
     * @return string
     * @codeCoverageIgnore
     * @throws Exception
     */
    public static function parseTypoScriptFromTypoScriptPath(string $typoScriptObjectPath): string
    {
        if (empty($typoScriptObjectPath)) {
            return '';
        }
        $setup = self::getTyposcriptFrontendController()->tmpl->setup;
        $pathSegments = GeneralUtility::trimExplode('.', $typoScriptObjectPath);
        $lastSegment = array_pop($pathSegments);
        foreach ($pathSegments as $segment) {
            $setup = $setup[$segment . '.'];
        }
        return self::getContentObject()->cObjGetSingle($setup[$lastSegment], $setup[$lastSegment . '.']);
    }

    /**
     * Return configured captcha extension
     *
     * @param array $settings
     * @return string
     */
    public static function getCaptchaExtensionFromSettings(array $settings): string
    {
        $allowedExtensions = [
            'captcha'
        ];
        if (in_array($settings['captcha']['use'], $allowedExtensions) &&
            ExtensionManagementUtility::isLoaded($settings['captcha']['use'])) {
            // @codeCoverageIgnoreStart
            return $settings['captcha']['use'];
            // @codeCoverageIgnoreEnd
        }
        return 'default';
    }
}
