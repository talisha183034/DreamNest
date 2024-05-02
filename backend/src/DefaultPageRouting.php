<?php

class DefaultPageRouting{
  public function index(){
    header("Location: /soft-eng/DreamNest/frontend/index.html");
    exit;
  }
}