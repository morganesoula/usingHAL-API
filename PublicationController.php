<?php

class PublicationController {

    public $publication;

    public function __construct()
    {
        $this->publication = new Publication();
    }
}
