<?php

$regex_name = '^[A-Za-zÀ-ÖØ-öø-ÿ ]{1,64}$';
$regex_profile_description = '^.{1,255}$';
$regex_username = '^\w{1,64}$';
$regex_location = '^.{1,128}$';
$regex_phone_number = '^(\+351)?( )*(\d{9}|\d{3} \d{3} \d{3})$';
$regex_date = '^\d{4}-\d{2}-\d{2}$';
$regex_email = '^[-_a-zA-Z0-9.+!%]*@[-_a-zA-Z0-9.]*$';
$regex_password = '^.{8,}$';

function validate_regex($regex, $pattern)
{
  return preg_match('/' . $regex . '/', $pattern);
}

function is_name($name)
{
  global $regex_name;
  return validate_regex($regex_name, $name);
}

function is_profile_description($description)
{
  global $regex_profile_description;
  return validate_regex($regex_profile_description, $description);
}

function is_username($username)
{
  global $regex_username;
  return validate_regex($regex_username, $username);
}

function is_location($location)
{
  global $regex_location;
  return validate_regex($regex_location, $location);
}

function is_phone_number($phone_number)
{
  global $regex_phone_number;
  return validate_regex($regex_phone_number, $phone_number);
}

function is_date($date)
{
  global $regex_date;
  return validate_regex($regex_date, $date);
}

function is_email($email)
{
  global $regex_email;
  return validate_regex($regex_email, $email);
}

function is_password($password)
{
  global $regex_password;
  return validate_regex($regex_password, $password);
}
