<?php
require_once 'ClassAutoLoad.php';

$ObjLayouts->header($conf);
$ObjLayouts->navbar($conf);
$ObjLayouts->banner($conf);

// Your login form content
$ObjForms->login();

$ObjLayouts->content($conf);
$ObjLayouts->footer($conf);