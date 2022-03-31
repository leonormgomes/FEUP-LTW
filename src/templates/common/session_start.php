<?php

function generate_random_token()
{
  return bin2hex(openssl_random_pseudo_bytes(32));
}

// session_set_cookie_params(0, '/', 'www.fe.up.pt', true, true);

session_start();
if (!isset($_SESSION['csrf'])) {
  $_SESSION['csrf'] = generate_random_token();
}
//session_regenerate_id(true);