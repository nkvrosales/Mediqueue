<?php
$password = 'admin123';
$hash = '$2y$10$GOzKzx7SBbEugcuFWiZAke4VFoIlKHDF3W3pvfrzb9CZFDb.0RtA2';
var_dump(password_verify($password, $hash));
