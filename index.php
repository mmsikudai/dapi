<?php
require_once 'classes.php';
require_once 'forms.php';

// Create an instance of MyClass 
$instance = new MyClass();
 // create an instance of user_forms
$formInstance = new user_forms();

// Call the method myMethod
$instance->heading();
$instance->myMethod();
// call the signup_form method
$formInstance->signup_form();
$instance->footer();


