<?php
class Redactor
{
    public $id;
    public $lname;
    public $fname;
    public $mail;

    function __construct($id, $lname, $fname, $mail)
    {
        $this->id = $id;
        $this->lname = $lname;
        $this->fname = $fname;
        $this->mail = $mail;
    }
}
