<?php
/**
 * Custom form element date_time_selector
 *
 * @package    profilefield_multiselect
 * @subpackage NED
 * @copyright  2025 NED {@link http://ned.ca}
 * @author     NED {@link http://ned.ca}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace profilefield_multiselect\form\element;

defined('MOODLE_INTERNAL') || die();

/** @var object $CFG */
require_once($CFG->libdir.'/form/autocomplete.php');

use MoodleQuickForm_autocomplete as core_autcomplete;

class MoodleQuickForm_autocomplete extends core_autcomplete {
    /** @var string - name of the element for the global(!) space */
    public const NAME = 'custom_autocomplete';

    /**
     * Registers our new element as a new element type
     *
     * @return void
     */
    public static function init(){
        static $_init = false;
        if ($_init) return;

        \MoodleQuickForm::registerElementType(static::NAME, __FILE__, __CLASS__);
        $_init = true;
    }

    /**
     * Returns a 'safe' element's value
     * Cuntstruct elements in string
     *
     * @see    uu_process_template()
     * Avoid bug with missing param 'text' when mutiple enabled
     *  while massupdate/import users
     *  /admin/tool/uploaduser/locallib.php line 287
     *
     * @param array   array of submitted values to search
     * @param bool    whether to return the value as associative array
     *
     * @return string
     */
    public function exportValue(&$submitValues, $assoc = false){
        $values = parent::exportValue($submitValues, false);

        $to_prepare = '';

        if (!$this->getMultiple() && is_string($values) && isset($this->_options[$values]['text'])){
            $to_prepare = $this->_options[$values]['text'];
        }

        if (is_array($values)){
            $retvalues = [];
            foreach ($values as $key){
                if (isset($this->_options[$key]['text'])){
                    $retvalues[] = $this->_options[$key]['text'];
                }
            }

            $to_prepare = join("\r\n", $retvalues);
        }

        return $this->_prepareValue($to_prepare, $assoc);
    }
}
