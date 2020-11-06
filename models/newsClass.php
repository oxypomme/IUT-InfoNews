<?php
class News
{
    public $title;
    public $text;
    public $imgURL;

    function __construct($title, $text, $imgURL)
    {
        $this->title = utf8_encode($title);
        $this->text = utf8_encode($text);
        $this->imgURL = utf8_encode($imgURL);
    }
}
