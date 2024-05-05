<?php

class DefaultPageRouting{
  public function index(){
    header("Location: /DreamNest/frontend/index.html");
    exit;
  }
}