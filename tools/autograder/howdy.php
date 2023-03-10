<?php

require_once "webauto.php";

use Goutte\Client;

line_out("Grading PHP-Intro Assignment 2");

titleNote();

$url = getUrl('http://csevumich.byethost18.com/howdy.php');
if ( $url === false ) return;
$grade = 0;

error_log("ASSN02 ".$url);
line_out("Retrieving ".htmlent_utf8($url)."...");
flush();

webauto_setup();

$crawler = webauto_load_url($url);
if ( $crawler === false ) return;
$html = webauto_get_html($crawler);

line_out("Searching for h1 tag...");

$passed = 0;
$titlefound = false;
try {
    $h1 = $crawler->filter('h1')->text();
    line_out("Found h1 tag...");
} catch(Exception $ex) {
    error_out("Did not find h1 tag");
    $h1 = "";
}

if ( stripos($h1, 'Hello') !== false ) {
    success_out("Found 'Hello' in the h1 tag");
    $passed += 1;
} else {
    error_out("Did not find 'Hello' in the h1 tag");
}

if ( $USER->displayname && stripos($h1,$USER->displayname) !== false ) {
    success_out("Found ($USER->displayname) in the h1 tag");
    $passed += 1;
} else if ( $USER->displayname ) {
    error_out("Did not find $USER->displayname in the h1 tag");
    error_out("No score sent");
    return;
}

$perfect = 2;
$score = webauto_compute_effective_score($perfect, $passed, $penalty);

if ( $score < 1.0 ) autoToggle();

// Send grade
if ( $score > 0.0 ) webauto_test_passed($score, $url);

