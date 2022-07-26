<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{   $mesimage=$this->Model->getRequete("SELECT * FROM `image`");
	    $data=array('mesimage'=>$mesimage);
		$this->load->view('welcome_message',$data);
	}
	public function saveMyimages($value='')
	{
		// code...
		$file = $_FILES['image']['tmp_name'];
        $image = file_get_contents($file);
		$insert=$this->Model->create('image',array('imageblob'=>$image));
		if ($insert) {
			// code...
			echo(json_encode(array('message'=>"Vyakunze")));
		}
		//print_r($image) ;
	}
}
