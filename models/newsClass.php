<?php
class News
{
    public $title;
    public $text;
    public $imgURL;

    function __construct($title, $text, $imgURL)
    {
        $this->title = $title;
        $this->text = $text;
        $this->imgURL = $imgURL;
    }
}
