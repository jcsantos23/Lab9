<?php

/**
 * core/MY_Controller.php
 *
 * Default application controller
 */
class Application extends CI_Controller {

    protected $data = array();      // parameters for view components
    protected $id;    // identifier for our content

    /**
     * Constructor.
     * Establish view parameters & load common helpers
     */

    function __construct() {
        parent::__construct();
        $this->data = array();
        $this->data['pagetitle'] = 'ACIT Timetable Winter 2016';
    }

    /**
     * Render this page
     */
    function render() {
        $this->data['content'] = $this->parser->parse($this->data['pagebody'], $this->data, true);
        $this->data['data'] = &$this->data;
        $this->parser->parse('template', $this->data);
    }

}

/* End of file MY_Controller.php */
/* Location: application/core/MY_Controller.php */
