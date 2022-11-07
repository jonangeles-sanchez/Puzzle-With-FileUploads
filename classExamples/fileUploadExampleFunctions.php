<?php

function checkDir($dir){
  if(!is_dir($dir)||!is_writeable($dir)){
    return false;
  }
  return true;
}


function tooBig($size){
  global $max;

  if($size>$max){
    return true;
  }
  return false;
}