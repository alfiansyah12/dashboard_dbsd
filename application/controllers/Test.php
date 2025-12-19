<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    public function hash()
    {
        echo password_hash('admin123', PASSWORD_DEFAULT);
    }
}
