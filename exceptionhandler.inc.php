<?php
/**
 * This function renders any exception that had not ben catch.
 *
 * @param Exception $e The exception that caused the fatal error.
 **/
function exception_handler(Exception $e) {

  $line[] = 'Uncaught exception: '.get_class($e);
  $line[] = 'Message:'. $e->getMessage();
  $line[] = '';
  $text = ereg_replace('php\(([0-9]+)\)',"php(<span style='color:red'>\\1</span>)",$e->getTraceAsString());
  $line = array_merge($line,explode("\n",$text));

  note($line); die();
  echo $text;

  die();
}