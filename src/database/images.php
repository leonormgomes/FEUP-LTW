<?php

function getImageURL($image_name)
{
  return '../../../src/database/images/' . $image_name;
}

function updateImage($image_name)
{
  $arr = explode('.', $image_name);
  return uniqid(rand(), true) . '.' . $arr[count($arr) - 1];
}
