<?php

/*
 * Model for schedule data
 */

class Timetable extends CI_Model {

    protected $xml_day = null;
    protected $xml_period = null;
    protected $xml_course = null;
    protected $weekday = array();
    protected $timeslot = array();
    protected $course = array();

    public function __construct() {
        parent:: __construct();
        $this->xml_day = simplexml_load_file(DATAPATH . 'winter2016-day.xml');
        $this->xml_period = simplexml_load_file(DATAPATH . 'winter2016-period.xml');
        $this->xml_course = simplexml_load_file(DATAPATH . 'winter2016-course.xml');

        //build list of school days
        foreach ($this->xml_day->aDay as $day) {
            foreach ($day->dBooking as $booking) {
                $this->weekday[] = new Booking($booking, $day);
            }
            //$this->weekday[(string) $day['weekday']] = (string) $day;
        }

        //build list of school time periods
        foreach ($this->xml_period->timeslot as $period) {
            foreach ($period->pBooking as $booking) {
                $this->timeslot[] = new Booking($booking, $period);
            }
            //$record = new stdClass();
            //$record->duration = (string) $period['duration'];
            //$record->start = (string) $period['start'];
            //$record->end = (string) $period['end'];
            //$this->timeslot[$record->start] = $record;
        }

        //build list of Term 4 ACIT courses
        foreach ($this->xml_course->aCourse as $courseRecord) {
            foreach ($courseRecord->cBooking as $booking) {
                $this->course[] = new Booking($booking, $courseRecord);
            }
            //$record = new stdClass();
            //$record->code = (string) $courseRecord['code'];
            //$record->name = (string) $courseRecord['name'];
            //$record->aCourse[$record->code] = $record;
        }
        
    }
    
      /**
 * Schema xml validation
 */
  public function schemaValidate()
	{
		$doc = new DOMDocument();
		$doc->load(DATAPATH . 'schedule.xml');
		$schema = DATAPATH . 'schedule.xsd';
		$results = array();
		libxml_use_internal_errors(true);
		if ($doc->schemaValidate($schema))
		{
			$results['resultCSS'] = 'schema_pass';
			$results['ValidationResults'][]['message'] = "Validated against schema successfully.";
		} else
		{
			$results['resultCSS'] = 'schema_fail';
			$results[] = "<b>Oh noooo...</b><br />";
			foreach (libxml_get_errors() as $error)
			{
				$results['ValidationResults'][]['message'] = $error->message;
			}
		}
		libxml_clear_errors();
		return $this->parser->parse('welcome', $results, true);
	}

    /*
     * Public Accessors
     */

    public function getDays() {
        return $this->day;
    }

    public function getPeriod() {
        return $this->period;
    }

    public function getCourse() {
        return $this->course;
    }

}

class Booking extends CI_Model {

    public $day = "";
    public $periodDuration = "";
    public $periodStart = "";
    public $periodEnd = "";
    public $courseCode = "";
    public $courseName = "";
    public $courseType = "";
    public $room = "";
    public $instructor = "";

    public function __construct($record, $parent) {

        //Weekday Slot
        $this->day = ( (string) (isset($record->aDay['weekday']) ?
                        $record->aDay['weekday'] : $parent['weekday']));

        //Period Duration
        $this->periodDuration = ( (string) (isset($record->timeslot['duration']) ?
                        $record->timeslot['duration'] : $parent['duration']));

        //Period Starting Time
        $this->periodStart = ( (string) (isset($record->timeslot['start']) ?
                        $record->timeslot['start'] : $parent['start']));

        //Period Ending Time
        $this->periodEnd = ( (string) (isset($record->timeslot['end']) ?
                        $record->timeslot['end'] : $parent['end']));

        //Course Code
        $this->courseCode = ( (string) (isset($record->aCourse['code']) ?
                        $record->aCourse['code'] : $parent['code']));

        //Course Name
        $this->courseName = ( (string) (isset($record->aCourse['name']) ?
                        $record->aCourse['name'] : $parent['name']));

        //Course Type
        $this->courseType = ( (string) (isset($record->cBooking['type']) ?
                        $record->cBooking['type'] : $parent['type']));

        //Classroom
        $this->rooom = (string) $record->room;

        //Instuctor
        $this->instructor = (string) $record->instructor;
    }

    
  
}
