<?php

require_once('Bean.php');

class Catalogue extends Bean
{

    public $publications = array();


    /* ********************************* GETTER AND SETTER ********************************* */

    /**
     * @return array
     */
    public function getPublications()
    {
        return $this->publications;
    }

    /**
     * @param array $publications
     */
    public function setPublications($publications)
    {
        $this->publications = (array) $publications;
    }


}



