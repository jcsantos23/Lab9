<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends Application {

    public function index() {
        
        $this->data['pagebody'] = 'welcome';
    $this->data['ValidationResults'] = $this->timetable->schemaValidate();
        $this->render();
    }
    
    public function day(){
    }

}
