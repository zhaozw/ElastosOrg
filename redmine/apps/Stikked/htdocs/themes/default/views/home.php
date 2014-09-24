<?php

	global $userName;

	if (empty($userName)) {
		header('Location: /stikked/lists');
	} else {
		$this->load->view('defaults/header');
		$this->load->view('defaults/paste_form');
		$this->load->view('defaults/footer');
	}
?>
