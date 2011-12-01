<?php
/**
 * @author Dmitry Ketov <dketov@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package block
 * @subpackage category_combo
 *
 * Block: Category Combo List
 */

class block_category_combo extends block_base {
    function init() {
      $this->title = get_string('courses').": ".get_string('frontpagecategorycombo');
    }

    function get_content () {
      global $CFG, $OUTPUT, $DB, $PAGE;

        ob_start();
	if($this->content !== NULL) {
	  return $this->content;
	}

        echo html_writer::tag('a', get_string('skipa', 'access', moodle_strtolower(get_string('courses'))), array('href'=>'#skipcourses', 'class'=>'skip-block'));
        //echo $OUTPUT->heading(get_string('courses'), 2, 'headingblock header');
        $renderer = $PAGE->get_renderer('core','course');
        // if there are too many courses, budiling course category tree could be slow,
        // users should go to course index page to see the whole list.
        $coursecount = $DB->count_records('course');
        if (empty($CFG->numcoursesincombo)) {
            // if $CFG->numcoursesincombo hasn't been set, use default value 500
            $CFG->numcoursesincombo = 500;
        }
        if ($coursecount > $CFG->numcoursesincombo) {
            $link = new moodle_url('/course/');
            echo $OUTPUT->notification(get_string('maxnumcoursesincombo', 'moodle', array('link'=>$link->out(), 'maxnumofcourses'=>$CFG->numcoursesincombo, 'numberofcourses'=>$coursecount)));
        } else {
            echo $renderer->course_category_tree(get_course_category_tree());
        }
        print_course_search('', false, 'short');
        echo html_writer::tag('span', '', array('class'=>'skip-block-to', 'id'=>'skipcourses'));

        $this->content->text = ob_get_contents();
        ob_end_clean();

        return $this->content;
    }
}
?>
