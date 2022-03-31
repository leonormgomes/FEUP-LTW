<?php

# This file should be moved to another directory cause might lead to data breaches

function has_fields($array, $fields)
{
  foreach ($fields as $field) {
    if (!isset($array[$field])) {
      return [false, $field];
    }
  }

  return [true, null];
}

function not_empty_fields($array, $fields)
{
  foreach ($fields as $field) {
    if (empty($array[$field])) {
      return [false, $field];
    }
  }

  return [true, null];
}
