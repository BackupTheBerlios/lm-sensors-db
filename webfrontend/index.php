<?php
//---------------------------------------------------------------------------
// Web-frontend for lm_sensors configurations
// (c) 2002 Alexander Holler
// Distributed under the terms of the GNU General Public License v2
//---------------------------------------------------------------------------
//
// $Id: index.php,v 1.1 2002/10/15 17:43:34 holler Exp $
//
// $Log: index.php,v $
// Revision 1.1  2002/10/15 17:43:34  holler
// First checkin.
//
// Revision 1.3  2002/10/15 17:37:02  holler
// Another change
//
// Revision 1.2  2002/10/15 17:34:36  holler
// Small changes.
//
// Revision 1.1  2002/10/15 16:43:24  holler
// First checkin.
//
//
// How it works:
//   we have stored values for boards, processors and chips in the db
//  to find the right values for board b and processor p we first
//  take the chip defaults (b=0, p=0), overwrite them with
//  defaults for a  processor (b=0), then with the board 
//  defaults (p=0) and last with the specific values for b and p.

//
// Much c&p and unused code here ;)
//

$revision='$Revision: 1.1 $';

// Connects to the DB
function connectDB($center)
{
  global $dbconn, $revision;
  //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
  // This file should be outside of the webspace.
  // It defines $host, $user, $pass and $db. 
  include("/home/users/holler/config.php");
  //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!  
  
  $dbconn=mysql_connect($host, $user, $pass);
  if(!$dbconn) {
    echo "Error connecting to the database!\n";
//    echo "<br />Revision $revision\n";
    if($center)
      echo "</center>\n";
    echo "</body>\n";
    echo "</html>\n";
    exit;
  }
  mysql_select_db($db, $dbconn);
}

//--------------------------------------------------------------------------------------------------------------

// Show form to enter a new mainboard spec
function showAddMainboard()
{
  global $dbconn;

  connectDB(false);

  echo "<center>\n";
  echo "<h1>Add new mainboard specs - step 1</h1>";
  echo "<form method=\"post\" action=\"$PHP_SELF\">";
  echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";

  echo "<tr bgcolor=\"#eeeeee\"><td>Manufacturer</td>";
  echo "<td><select size=\"1\" name=\"mb_manu\">";
  echo "<option value=\"0\" selected>---select one---</option>";
  $sql="SELECT no, name FROM manufacturers";
  $result=mysql_query($sql, $dbconn);
  while($row=mysql_fetch_row($result))
    echo "<option value=\"" . $row[0] . "\">" . $row[1] . "</option>";
  mysql_free_result($result);
  echo "</select></td></tr>\n";
  echo "<tr bgcolor=\"#eeeeee\"><td>or</td><td>&nbsp;</td></tr>\n";
  echo "<tr bgcolor=\"#eeeeee\"><td>New manufacturer &nbsp;</td>";
  echo "<td><input type=\"text\" size=\"30\" maxlength=\"100\" name=\"mb_manu_new\"\"></td></tr>";

  echo "<tr><td colspan=\"2\">&nbsp;</td></tr>";

  echo "<tr bgcolor=\"#dddddd\"><td>New board name</td>";
  echo "<td><input type=\"text\" size=\"30\" maxlength=\"100\" name=\"mb_name_new\"\"></td></tr>";
  echo "<tr bgcolor=\"#dddddd\"><td>(and revision)</td>";
  echo "<td><select size=\"1\" name=\"mb_name\">";
  echo "<option value=\"0\" selected>---see here which are already defined--</option>";
  $sql="SELECT no, name FROM boards";
  $result=mysql_query($sql, $dbconn);
  while($row=mysql_fetch_row($result))
    echo "<option value=\"" . $row[0] . "\">" . $row[1] . "</option>";
  mysql_free_result($result);
  echo "</select></td></tr>\n";

  echo "<tr><td colspan=\"2\">&nbsp;</td></tr>";

  echo "<tr bgcolor=\"#eeeeee\"><td>Chip 1:</td>";
  echo "<td><select size=\"1\" name=\"mb_chip1\">";
  echo "<option value=\"0\" selected>---select one---</option>";
  $sql="SELECT no, name FROM chips";
  $result=mysql_query($sql, $dbconn);
  while($row=mysql_fetch_row($result))
    echo "<option value=\"" . $row[0] . "\">" . $row[1] . "</option>";
  mysql_free_result($result);
  echo "</select></td></tr>\n";

  echo "<tr bgcolor=\"#eeeeee\"><td>Chip 2:</td>";
  echo "<td><select size=\"1\" name=\"mb_chip2\">";
  echo "<option value=\"0\" selected>---undefined---</option>";
  $sql="SELECT no, name FROM chips";
  $result=mysql_query($sql, $dbconn);
  while($row=mysql_fetch_row($result))
    echo "<option value=\"" . $row[0] . "\">" . $row[1] . "</option>";
  mysql_free_result($result);
  echo "</select></td></tr>\n";

  echo "<tr bgcolor=\"#eeeeee\"><td>Chip 3:</td>";
  echo "<td><select size=\"1\" name=\"mb_chip3\">";
  echo "<option value=\"0\" selected>---undefined---</option>";
  $sql="SELECT no, name FROM chips";
  $result=mysql_query($sql, $dbconn);
  while($row=mysql_fetch_row($result))
    echo "<option value=\"" . $row[0] . "\">" . $row[1] . "</option>";
  mysql_free_result($result);
  echo "</select></td></tr>\n";
/*
  echo "<tr bgcolor=\"#dddddd\"><td>Processor</td>";
  echo "<td><select size=\"1\" name=\"processor\">";
  echo "<option value=\"0\" selected>---undefined---</option>";
  $sql="SELECT no, name FROM processors";
  $result=mysql_query($sql, $dbconn);
  while($row=mysql_fetch_row($result))
    echo "<option value=\"" . $row[0] . "\">" . $row[1] . "</option>";
  mysql_free_result($result);
  echo "</select></td></tr>\n";
  echo "<tr bgcolor=\"#dddddd\"><td>or</td><td>&nbsp;</td></tr>\n";
  echo "<tr bgcolor=\"#dddddd\"><td>New processor (be specific,<br />e.g. speed, bus speed and<br />with or w/o fan)</td>";
  echo "<td><input type=\"text\" size=\"30\" maxlength=\"100\" name=\"processor_new\"\"></td></tr>";
*/

//  echo "<tr><td colspan=\"2\"><input type=\"submit\" value=\"next step\"></td>/tr>";
  echo "</table>\n";

  echo "<p /><input type=\"submit\" value=\"insert this mainboard into the database\">";

  echo "</form>\n";
  echo "</center>\n";  
}

//--------------------------------------------------------------------------------------------------------------

// Shows all mainboards
function showIndexOld()
{
  global $dbconn;

  connectDB(false);

  echo "<center>\n";
  echo "<table border=\"1\">\n";
  echo "<th>Mainboards</th>\n";
  $sql="SELECT boards.no, manufacturers.name, boards.name FROM boards, manufacturers WHERE boards.manufacturer = manufacturers.no";
  $result=mysql_query($sql, $dbconn);
  while($row=mysql_fetch_row($result))
    echo "<tr align=\"left\"><td><a href=\"$PHP_SELF?a=s&b=$row[0]\">$row[1] $row[2]</a></td></tr>\n";
  mysql_free_result($result);
  echo "</table>\n";
  
  echo "<p /><a href=\"$PHP_SELF?a=n\">add new motherboard specs</a>";
  echo "</center>";  
}

//--------------------------------------------------------------------------------------------------------------

// Shows all mainboards
function showIndex()
{
  global $dbconn, $revision;

  echo "<center>\n";
  echo "<h1>lm_sensors_db<small></h1>\n";
  echo "<small><sub>$revision</sub></small><p />\n";
  echo "<a href=\"http://developer.berlios.de/projects/lm-sensors-db/\">[Project Summary]</a>\n";
  echo "<p />\n";
  connectDB(true);
//  echo "<form method=\"post\" action=\"$PHP_SELF?a=s\">";
  echo "<form method=\"get\" action=\"$PHP_SELF\">";
  echo "<input type=\"hidden\" name=\"a\" value=\"s\">";
  echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";

  echo "<tr><td>Board</td>";
  echo "<td><select size=\"1\" name=\"b\">";
  echo "<option value=\"0\" selected>---select one---</option>";
  $sql="SELECT boards.no, manufacturers.name, boards.name FROM boards, manufacturers WHERE boards.manufacturer = manufacturers.no";
  $result=mysql_query($sql, $dbconn);
  while($row=mysql_fetch_row($result))
    echo "<option value=\"" . $row[0] . "\">" . $row[1] . " " . $row[2] . "</option>";
  mysql_free_result($result);
  echo "</select></td></tr>\n";
  
  echo "<tr><td>Processor</td>";
  echo "<td><select size=\"1\" name=\"p\">";
  echo "<option value=\"0\" selected>---select one---</option>";
  $sql="SELECT no, name FROM processors";
  $result=mysql_query($sql, $dbconn);
  while($row=mysql_fetch_row($result))
    echo "<option value=\"" . $row[0] . "\">" . $row[1] . "</option>";
  mysql_free_result($result);
  echo "</select></td></tr>\n";
  
  echo "</table>\n";
  echo "<p /><input type=\"submit\" value=\"show values\">";
  echo "</form>\n";

  echo "<p /><a href=\"$PHP_SELF?a=n\">add new motherboard specs</a>";
  echo "</center>";  
}

//--------------------------------------------------------------------------------------------------------------

function showFans($b, $c, $p, & $conf)
{
  global $dbconn;

  // First we take the chip defaults (board =0, processor =0)  
  $sql="SELECT fan_no, ignored, label, min FROM fan_descriptions WHERE board_no = 0 AND chip_no = $c AND processor_no = 0";
  $result=mysql_query($sql , $dbconn);
  while($row=mysql_fetch_row($result)) {
    $specific[$row[0]]='';
    $ignored[$row[0]]=$row[1];
    if(! $ignored[$row[0]]) {
      if(isset($row[2]))
        $label[$row[0]]=$row[2];
      if(isset($row[3]))
        $min[$row[0]]=$row[3];
    }
  }
  mysql_free_result($result);

  if($p) {
  // Next we take the processor defaults (board =0)  
  $sql="SELECT fan_no, ignored, label, min FROM fan_descriptions WHERE board_no = 0 AND chip_no = $c AND processor_no = $p";
  $result=mysql_query($sql , $dbconn);
  while($row=mysql_fetch_row($result)) {
    $specific[$row[0]]='p';
    $ignored[$row[0]]=$row[1];
    if(! $ignored[$row[0]]) {
      if(isset($row[2]))
        $label[$row[0]]=$row[2];
      else
        unset($label[$row[0]]);
      if(isset($row[3]))
        $min[$row[0]]=$row[3];
      else
        unset($min[$row[0]]);
    }
    else {
      unset($label[$row[0]]);
      unset($min[$row[0]]);
    }
  }
  mysql_free_result($result);
  } // if $p

  // Next we take the board defaults (processor =0)  
  $sql="SELECT fan_no, ignored, label, min FROM fan_descriptions WHERE board_no = $b AND chip_no = $c AND processor_no = 0";
  $result=mysql_query($sql , $dbconn);  
  if($result==FALSE)
  echo "error";
  while($row=mysql_fetch_row($result)) {
    $specific[$row[0]]='b';
    $ignored[$row[0]]=$row[1];
    if(! $ignored[$row[0]]) {
      if(isset($row[2]))
        $label[$row[0]]=$row[2];
      else
        unset($label[$row[0]]);
      if(isset($row[3]))
        $min[$row[0]]=$row[3];
      else
        unset($min[$row[0]]);
    }
    else {
      unset($label[$row[0]]);
      unset($min[$row[0]]);
    }
  }
  mysql_free_result($result);

  if($p) {
  // At last, we take the sepcific values
  $sql="SELECT fan_no, ignored, label, min FROM fan_descriptions WHERE board_no = $b AND chip_no = $c AND processor_no = $p";
  $result=mysql_query($sql , $dbconn);
  while($row=mysql_fetch_row($result)) {
    $specific[$row[0]]='b, p';
    $ignored[$row[0]]=$row[1];
    if(! $ignored[$row[0]]) {
      if(isset($row[2]))
        $label[$row[0]]=$row[2];
      else
        unset($label[$row[0]]);
      if(isset($row[3]))
        $min[$row[0]]=$row[3];
      else
        unset($min[$row[0]]);
    }
    else {
      unset($label[$row[0]]);
      unset($min[$row[0]]);
    }
  }
  mysql_free_result($result);
  } // if $p

  if(!count($ignored))
    return;

  // Print the values
  echo "<table border=\"1\">\n";
  echo "<tr><th colspan=\"3\">Fans</th></tr>\n";
  echo "<tr><th>Number</th><th>Label</th><th>min</th></tr>\n";
  for($i=0; $i<10; $i++) { // max. 10 fans
    if(! isset($ignored[$i]))
      continue;
    echo "<tr align=\"left\" ";
    if ($specific[$i]=="b, p")
      echo "bgcolor=\"#00ff00\"";
    else if ($specific[$i]=="b")
      echo "bgcolor=\"#00ffff\"";
    else if ($specific[$i]=="p")
      echo "bgcolor=\"#ffff00\"";
    echo "><td>$i</td>";
    if($ignored[$i] == "1") {
      echo "<td colspan=\"2\">ignored</td>";
      $conf = $conf . "ignore fan$i\n";
    }
    else {
      if(isset($label[$i])) {
        echo "<td>" . $label[$i] . "</td>";
        $conf = $conf . "label fan$i \"" . $label[$i] . "\"\n";
      }
      else
        echo "<td>&nbsp;</td>";
      if(isset($min[$i])) {
        echo "<td>" . $min[$i] . "</td>";
        $conf = $conf . "set fan$i" . "_min " . $min[$i] . "\n";
      }
      else
        echo "<td>&nbsp;</td>";
    }
    echo "</tr>";
  }
  echo "</table>\n";
}

//--------------------------------------------------------------------------------------------------------------

function showFansSpecific($m, $c, $p)
{
  global $dbconn;

  $sql="SELECT fan_no, ignored, label, min FROM fan_descriptions WHERE board_no = $m AND chip_no = $c AND processor_no = $p";
  $result=mysql_query($sql , $dbconn);
  if(!mysql_num_rows($result)) {
    mysql_free_result($result);
    return;
  }
  echo "<table border=\"1\">\n";
  echo "<tr><th colspan=\"3\">Fans</th></tr>\n";
  echo "<tr><th>Number</th><th>Label</th><th>min</th></tr>\n";
  while($row=mysql_fetch_array($result)) {
    echo "<tr align=\"left\"><td>" . $row['fan_no'] . "</td>";
    if($row['ignored'] == "1")
      echo "<td colspan=\"2\">ignored</td>";
    else {
      if(isset($row['label']))
        echo "<td>" . $row['label'] . "</td>";
      else
        echo "<td>&nbsp;</td>";
      if(isset($row['min']))
        echo "<td>" . $row['min'] . "</td>";
      else
        echo "<td>&nbsp;</td>";
    }
    echo "</tr>";
  }
  mysql_free_result($result);
  echo "</table>\n";
}

//--------------------------------------------------------------------------------------------------------------

function showTemps($b, $c, $p, & $conf)
{
  global $dbconn;

  // First we take the chip defaults (board =0, processor =0)  
  $sql="SELECT temp_no, ignored, label, over, hyst FROM temp_descriptions WHERE board_no = 0 AND chip_no = $c AND processor_no = 0";
  $result=mysql_query($sql , $dbconn);
  while($row=mysql_fetch_row($result)) {
    $specific[$row[0]]='';
    $ignored[$row[0]]=$row[1];
    if(! $ignored[$row[0]]) {
      if(isset($row[2]))
        $label[$row[0]]=$row[2];
      if(isset($row[3]))
        $over[$row[0]]=$row[3];
      if(isset($row[4]))
        $hyst[$row[0]]=$row[4];
    }
  }
  mysql_free_result($result);

  if($p) {
  // Next we take the processor defaults (board =0)  
  $sql="SELECT temp_no, ignored, label, over, hyst FROM temp_descriptions WHERE board_no = 0 AND chip_no = $c AND processor_no = $p";
  $result=mysql_query($sql , $dbconn);
  while($row=mysql_fetch_row($result)) {
    $specific[$row[0]]='p';
    $ignored[$row[0]]=$row[1];
    if(! $ignored[$row[0]]) {
      if(isset($row[2]))
        $label[$row[0]]=$row[2];
      else
        unset($label[$row[0]]);
      if(isset($row[3]))
        $over[$row[0]]=$row[3];
      else
        unset($over[$row[0]]);
      if(isset($row[4]))
        $hyst[$row[0]]=$row[4];
      else
        unset($hyst[$row[0]]);
    }
    else {
      unset($label[$row[0]]);
      unset($over[$row[0]]);
      unset($hyst[$row[0]]);
    }
  }
  mysql_free_result($result);
  } // if $p
  
  // Next we take the board defaults (processor =0)  
  $sql="SELECT temp_no, ignored, label, over, hyst FROM temp_descriptions WHERE board_no = $b AND chip_no = $c AND processor_no = 0";
  $result=mysql_query($sql , $dbconn);
  while($row=mysql_fetch_row($result)) {
    $specific[$row[0]]='b';
    $ignored[$row[0]]=$row[1];
    if(! $ignored[$row[0]]) {
      if(isset($row[2]))
        $label[$row[0]]=$row[2];
      else
        unset($label[$row[0]]);
      if(isset($row[3]))
        $over[$row[0]]=$row[3];
      else
        unset($over[$row[0]]);
      if(isset($row[4]))
        $hyst[$row[0]]=$row[4];
      else
        unset($hyst[$row[0]]);
    }
    else {
      unset($label[$row[0]]);
      unset($over[$row[0]]);
      unset($hyst[$row[0]]);
    }
  }
  mysql_free_result($result);

  if($p) {
  // At last, we take the sepcific values
  $sql="SELECT temp_no, ignored, label, over, hyst FROM temp_descriptions WHERE board_no = $b AND chip_no = $c AND processor_no = $p";
  $result=mysql_query($sql , $dbconn);
  while($row=mysql_fetch_row($result)) {
    $specific[$row[0]]='b, p';
    $ignored[$row[0]]=$row[1];
    if(! $ignored[$row[0]]) {
      if(isset($row[2]))
        $label[$row[0]]=$row[2];
      else
        unset($label[$row[0]]);
      if(isset($row[3]))
        $over[$row[0]]=$row[3];
      else
        unset($over[$row[0]]);
      if(isset($row[4]))
        $hyst[$row[0]]=$row[4];
      else
        unset($hyst[$row[0]]);
    }
    else {
      unset($label[$row[0]]);
      unset($over[$row[0]]);
      unset($hyst[$row[0]]);
    }
  }
  mysql_free_result($result);
  } // if $p

  if(!count($ignored))
    return;

  // Print the values
  echo "<table border=\"1\">\n";
  echo "<tr><th colspan=\"4\">Temps</th></tr>\n";
  echo "<tr><th>Number</th><th>Label</th><th>over</th><th>hyst</th></tr>\n";
  for($i=0; $i<10; $i++) { // max. 10 fans
    if(! isset($ignored[$i]))
      continue;
    echo "<tr align=\"left\" ";
    if ($specific[$i]=="b, p")
      echo "bgcolor=\"#00ff00\"";
    else if ($specific[$i]=="b")
      echo "bgcolor=\"#00ffff\"";
    else if ($specific[$i]=="p")
      echo "bgcolor=\"#ffff00\"";
    echo "><td>$i</td>";
    if($ignored[$i] == "1") {
      echo "<td colspan=\"3\">ignored</td>";
      $conf = $conf . "ignore temp$i\n";
    }
    else {
      if(isset($label[$i])) {
        echo "<td>" . $label[$i] . "</td>";
        $conf = $conf . "label temp$i \"" . $label[$i] . "\"\n";
      }
      else
        echo "<td>&nbsp;</td>";
      if(isset($over[$i])) {
        echo "<td>" . $over[$i] . "</td>";
        $conf = $conf . "set temp$i" . "_over " . $over[$i] . "\n";
      }
      else
        echo "<td>&nbsp;</td>";
      if(isset($hyst[$i])) {
        echo "<td>" . $hyst[$i] . "</td>";
        $conf = $conf . "set temp$i" . "_hyst " . $hyst[$i] . "\n";
      }
      else
        echo "<td>&nbsp;</td>";
    }
    echo "</tr>";
  }
  echo "</table>\n";
}

//--------------------------------------------------------------------------------------------------------------

function showTempsSpecific($m, $c, $p)
{
  global $dbconn;

  $sql="SELECT temp_no, ignored, label, over, hyst FROM temp_descriptions WHERE board_no = $m AND chip_no = $c AND processor_no = $p";
  $result=mysql_query($sql , $dbconn);
  if(!mysql_num_rows($result)) {
    mysql_free_result($result);
    return;
  }
  echo "<table border=\"1\">\n";
  echo "<tr><th colspan=\"4\">Temps</th></tr>\n";
  echo "<tr><th>Number</th><th>Label</th><th>over</th><th>hyst</th></tr>\n";
  while($row=mysql_fetch_array($result)) {
    echo "<tr align=\"left\"><td>" . $row['temp_no'] . "</td>";
    if($row['ignored'] == "1")
      echo "<td colspan=\"3\">ignored</td>";
    else {
      if(isset($row['label']))
        echo "<td>" . $row['label'] . "</td>";
      else
        echo "<td>&nbsp;</td>";
      if(isset($row['over']))
        echo "<td>" . $row['over'] . "</td>";
      else
        echo "<td>&nbsp;</td>";
      if(isset($row['hyst']))
        echo "<td>" . $row['hyst'] . "</td>";
      else
        echo "<td>&nbsp;</td>";
    }
    echo "</tr>";
  }
  mysql_free_result($result);
  echo "</table>\n";
}

//--------------------------------------------------------------------------------------------------------------

function showInputs($b, $c, $p, & $conf)
{
  global $dbconn;

  // First we take the chip defaults (board =0, processor =0)  
  $sql="SELECT in_no, ignored, label, compute, min, max FROM in_descriptions WHERE board_no = 0 AND chip_no = $c AND processor_no = 0";
  $result=mysql_query($sql , $dbconn);
  while($row=mysql_fetch_row($result)) {
    $specific[$row[0]]='';
    $ignored[$row[0]]=$row[1];
    if(! $ignored[$row[0]]) {
      if(isset($row[2]))
        $label[$row[0]]=$row[2];
      if(isset($row[3]))
        $compute[$row[0]]=$row[3];
      if(isset($row[4]))
        $min[$row[0]]=$row[4];
      if(isset($row[5]))
        $max[$row[0]]=$row[5];
    }
  }
  mysql_free_result($result);

  if($p) {
  // Next we take the processor defaults (board =0)  
  $sql="SELECT in_no, ignored, label, compute, min, max FROM in_descriptions WHERE board_no = 0 AND chip_no = $c AND processor_no = $p";
  $result=mysql_query($sql , $dbconn);
  while($row=mysql_fetch_row($result)) {
    $specific[$row[0]]='p';
    $ignored[$row[0]]=$row[1];
    if(! $ignored[$row[0]]) {
      if(isset($row[2]))
        $label[$row[0]]=$row[2];
      else
        unset($label[$row[0]]);
      if(isset($row[3]))
        $compute[$row[0]]=$row[3];
      else
        unset($compute[$row[0]]);
      if(isset($row[4]))
        $min[$row[0]]=$row[4];
      else
        unset($min[$row[0]]);
      if(isset($row[5]))
        $max[$row[0]]=$row[5];
      else
        unset($max[$row[0]]);
    }
    else {
      unset($label[$row[0]]);
      unset($compute[$row[0]]);
      unset($min[$row[0]]);
      unset($max[$row[0]]);
    }
  }
  mysql_free_result($result);
  } // if $p

  // Next we take the board defaults (processor =0)  
  $sql="SELECT in_no, ignored, label, compute, min, max FROM in_descriptions WHERE board_no = $b AND chip_no = $c AND processor_no = 0";
  $result=mysql_query($sql , $dbconn);
  while($row=mysql_fetch_row($result)) {
    $specific[$row[0]]='b';
    $ignored[$row[0]]=$row[1];
    if(! $ignored[$row[0]]) {
      if(isset($row[2]))
        $label[$row[0]]=$row[2];
      else
        unset($label[$row[0]]);
      if(isset($row[3]))
        $compute[$row[0]]=$row[3];
      else
        unset($compute[$row[0]]);
      if(isset($row[4]))
        $min[$row[0]]=$row[4];
      else
        unset($min[$row[0]]);
      if(isset($row[5]))
        $max[$row[0]]=$row[5];
      else
        unset($max[$row[0]]);
    }
    else {
      unset($label[$row[0]]);
      unset($compute[$row[0]]);
      unset($min[$row[0]]);
      unset($max[$row[0]]);
    }
  }
  mysql_free_result($result);

  if($p) {
  // At last, we take the sepcific values
  $sql="SELECT in_no, ignored, label, compute, min, max FROM in_descriptions WHERE board_no = $b AND chip_no = $c AND processor_no = $p";
  $result=mysql_query($sql , $dbconn);
  while($row=mysql_fetch_row($result)) {
    $specific[$row[0]]='b, p';
    $ignored[$row[0]]=$row[1];
    if(! $ignored[$row[0]]) {
      if(isset($row[2]))
        $label[$row[0]]=$row[2];
      else
        unset($label[$row[0]]);
      if(isset($row[3]))
        $compute[$row[0]]=$row[3];
      else
        unset($compute[$row[0]]);
      if(isset($row[4]))
        $min[$row[0]]=$row[4];
      else
        unset($min[$row[0]]);
      if(isset($row[5]))
        $max[$row[0]]=$row[5];
      else
        unset($max[$row[0]]);
    }
    else {
      unset($label[$row[0]]);
      unset($compute[$row[0]]);
      unset($min[$row[0]]);
      unset($max[$row[0]]);
    }
  }
  mysql_free_result($result);
  } // if $p

  if(!count($ignored))
    return;

  // Print the values and build the config
  echo "<table border=\"1\">\n";
  echo "<tr><th colspan=\"5\">Inputs</th></tr>\n";
  echo "<tr><th>Number</th><th>Label</th><th>compute</th><th>min</th><th>max</th></tr>\n";
  for($i=0; $i<10; $i++) { // max. 10 inputs
    if(! isset($ignored[$i]))
      continue;
    echo "<tr align=\"left\" ";
    if ($specific[$i]=="b, p")
      echo "bgcolor=\"#00ff00\"";
    else if ($specific[$i]=="b")
      echo "bgcolor=\"#00ffff\"";
    else if ($specific[$i]=="p")
      echo "bgcolor=\"#ffff00\"";
    echo "><td>$i</td>";
    if($ignored[$i] == "1") {      
      echo "<td colspan=\"4\">ignored</td>";
      $conf = $conf . "ignore in$i\n";
    }
    else {
      if(isset($label[$i])) {
        echo "<td>" . $label[$i] . "</td>";
        $conf= $conf . "label in$i \"" . $label[$i] . "\"\n";
      }
      else
        echo "<td>&nbsp;</td>";
      if(isset($compute[$i])) {
        echo "<td>" . $compute[$i] . "</td>";
        $conf = $conf . "compute in$i " . $compute[$i] . "\n";
      }
      else
        echo "<td>&nbsp;</td>";
      if(isset($min[$i])) {
        echo "<td>" . $min[$i] . "</td>";
        $conf= $conf . "set in$i " . "_min " . $min[$i] . "\n";
      }
      else
        echo "<td>&nbsp;</td>";
      if(isset($max[$i])) {
        echo "<td>" . $max[$i] . "</td>";
        $conf= $conf . "set in$i " . "_max " . $max[$i] . "\n";
      }
      else
        echo "<td>&nbsp;</td>";
    }
    echo "</tr>";
  }
  echo "</table>\n";
}

//--------------------------------------------------------------------------------------------------------------

function showInputsSpecific($m, $c, $p)
{
  global $dbconn;

  $sql="SELECT in_no, ignored, label, compute, min, max FROM in_descriptions WHERE board_no = $m AND chip_no = $c AND processor_no = $p";
  $result=mysql_query($sql , $dbconn);
  if(!mysql_num_rows($result)) {
    mysql_free_result($result);
    return;
  }
  echo "<table border=\"1\">\n";
  echo "<tr><th colspan=\"5\">Inputs</th></tr>\n";
  echo "<tr><th>Number</th><th>Label</th><th>compute</th><th>min</th><th>max</th></tr>\n";
  while($row=mysql_fetch_array($result)) {
    echo "<tr align=\"left\"><td>" . $row['in_no'] . "</td>";
    if($row['ignored'] == "1")
      echo "<td colspan=\"4\">ignored</td>";
    else {
      if(isset($row['label']))
        echo "<td>" . $row['label'] . "</td>";
      else
        echo "<td>&nbsp;</td>";
      if(isset($row['compute']))
        echo "<td>" . $row['compute'] . "</td>";
      else
        echo "<td>&nbsp;</td>";
      if(isset($row['min']))
        echo "<td>" . $row['min'] . "</td>";
      else
        echo "<td>&nbsp;</td>";
      if(isset($row['max']))
        echo "<td>" . $row['max'] . "</td>";
      else
        echo "<td>&nbsp;</td>";
    }
    echo "</tr>";
  }
  mysql_free_result($result);
  echo "</table>\n";
}

//--------------------------------------------------------------------------------------------------------------

// Shows board and processor specific values
function showSpecific($b, $c, $p)
{
  showInputsSpecific($b, $c, $p);
  showFansSpecific($b, $c, $p);
  showTempsSpecific($b, $c, $p);
}

//--------------------------------------------------------------------------------------------------------------

// Shows processor specific default values (board=0)
function showProcessorDefaults($c, $p)
{
  showInputsSpecific(0, $c, $p);
  showFansSpecific(0, $c, $p);
  showTempsSpecific(0, $c, $p);
}

//--------------------------------------------------------------------------------------------------------------

// Shows board specific default values (processor=0)
function showBoardDefaults($b, $c)
{
  echo "<br /><b>Board specific values</b><br />";
  showInputsSpecific($b, $c, 0);
  showFansSpecific($b, $c, 0);
  showTempsSpecific($b, $c, 0);
}

//--------------------------------------------------------------------------------------------------------------

// Shows default values (board=0, processor=0)
function showDefaults($c)
{
  echo "<br /><b>Default values</b><br />";
  showInputsSpecific(0, $c, 0);
  showFansSpecific(0, $c, 0);
  showTempsSpecific(0, $c, 0);
}

//--------------------------------------------------------------------------------------------------------------

// Shows a specific motherboard
function showMainboard($b, $name, $p, $processorName)
{
  global $dbconn;
  // already connected through showMotherboard0()
  
  echo "<center>";
  echo "<h1>$name</h1>\n";
  // Get the chips on the board
  $sql="SELECT chip1, chip2, chip3 FROM boards WHERE no = $b";
  $result=mysql_query($sql , $dbconn);
  if(! ($row=mysql_fetch_row($result))) {
    // Should not happen
    echo "Board number not found!";
    mysql_free_result($result);
    return;
  }
  $cChips=0;
  for($i=0; $i<3; $i++) {
    if($row[$i])
      $chips[$cChips++]=$row[$i];
  }
  mysql_free_result($result);
  if(!$cChips) {
    // Should not happen
    echo "Error in board definition!";
    return;
  }
  echo "<h2>$processorName</h2>\n";

  echo "<table border=\"1\"><tr>";
  echo "<td bgcolor=\"#00ff00\">lime = specific for board and processor</td>";
  echo "<td bgcolor=\"#00ffff\">aqua = specific for board</td>";
  echo "<td bgcolor=\"#ffff00\">yellow = specific for processor</td>";
  echo "<td>white = chip defaults</td>";
  echo "</tr></table>\n";

  $conf='';
  for($i=0 ; $i<$cChips; $i++) {
    $sql="SELECT name FROM chips WHERE no = " . $chips[$i];
    $result=mysql_query($sql , $dbconn);
    if(! ($row=mysql_fetch_row($result))) {
      // Should not happen
      echo "No chip name found for chip number $chips[$i]!";
      mysql_free_result($result);
      return;
    }
    echo "<h3>Chip ". $row[0] . "</h3>\n";
    $conf = $conf . "\nchip \"" . $row[0] . "\"\n";
    mysql_free_result($result);
    $conf = $conf . "\n# inputs\n\n";
    showInputs($b, $chips[$i], $p, $conf);
    $conf = $conf . "\n# fans\n\n";
    showFans($b, $chips[$i], $p, $conf);
    $conf = $conf . "\n# temps\n\n";
    showTemps($b, $chips[$i], $p, $conf);
  }
  echo "</center>\n";
  echo "<pre>\n";
  echo "#------------------------------------------------\n";
  echo "# Sensors configuration file used by 'libsensors'\n";
  echo "#------------------------------------------------\n";
  echo "# Mainboard: $name\n";
  echo "# Processor: $processorName\n";
  echo "#------------------------------------------------\n";
  echo $conf;
  echo "#------------------------------------------------\n\n";
  echo "</pre>\n";
}

//--------------------------------------------------------------------------------------------------------------

// Shows a specific motherboard (all values)
function showMainboardAll($b, $name)
{
  global $dbconn;
  // already connected through showMotherboard0()
  
  echo "<center>";
  echo "<h1>$name</h1>\n";
  // Get the chips on the board
  $sql="SELECT chip1, chip2, chip3 FROM boards WHERE no = $b";
  $result=mysql_query($sql , $dbconn);
  if(! ($row=mysql_fetch_row($result))) {
    // Should not happen
    echo "Boardnumber not found!";
    mysql_free_result($result);
    return;
  }
  $cChips=0;
  for($i=0; $i<3; $i++) {
    if($row[$i])
      $chips[$cChips++]=$row[$i];
  }
  mysql_free_result($result);
  if(!$cChips) {
    // Should not happen
    echo "Error in board definition!";
    return;
  }
  // Get available processors
  $sql="SELECT * FROM processors";
  $result=mysql_query($sql , $dbconn);
  $cProcessors=0;
  while(($row=mysql_fetch_row($result))) {
    $processorNumbers[$cProcessors]=$row[0];
    $processorNames[$cProcessors++]=$row[1];
  }
  for($i=0 ; $i<$cChips; $i++) {
    $sql="SELECT name FROM chips WHERE no = " . $chips[$i];
    $result=mysql_query($sql , $dbconn);
    if(! ($row=mysql_fetch_row($result))) {
      echo "No chip name found for chip number $c!";
      mysql_free_result($result);
      return;
    }
    echo "<h3>Chip ". $row[0] . "</h3>";
    mysql_free_result($result);
    echo "<br /><b>Board and processor specific values</b><br />";
    // quick'n dirty, normally we shouldn't iterate through all available processors
    for($j=0; $j<$cProcessors; $j++) {
      echo $processorNames[$j] . "<br />";
      showSpecific($b, $chips[$i], $processorNumbers[$j]);
    }
    showBoardDefaults($b, $chips[$i]);
    echo "<br /><b>Processor specific values</b><br />";
    // quick'n dirty, normally we shouldn't iterate through all available processors
    for($j=0; $j<$cProcessors; $j++) {
      echo $processorNames[$j] . "<br />";
      showProcessorDefaults($chips[$i], $processorNumbers[$j]);
    }
    showDefaults($chips[$i]);
  }
  echo "</center>";  
}

//--------------------------------------------------------------------------------------------------------------

// Returns manufacturer name + board name
function getMainboardName($b)
{
  global $dbconn;

  connectDB(false);
  $sql="SELECT manufacturers.name, boards.name FROM boards, manufacturers WHERE boards.manufacturer = manufacturers.no AND boards.no = $b";
  $result=mysql_query($sql , $dbconn);
  if($row=mysql_fetch_row($result))
    $name="$row[0] $row[1]";
  else
    $name="unknown";
  mysql_free_result($result);
  return $name;
}

// Returns processor name
function getProcessorName($p)
{
  global $dbconn;

  connectDB(false);
  $sql="SELECT name FROM processors WHERE no = $p";
  $result=mysql_query($sql , $dbconn);
  if($row=mysql_fetch_row($result))
    $name=$row[0];
  else
    $name="unknown";
  mysql_free_result($result);
  return $name;
}

//--------------------------------------------------------------------------------------------------------------


  if($a=='s' && isset($b[0]) && isset($p[0])) {
//  if($a=='s' && $b[0] && $p[0]) {
    $arg1=$b[0];
    $func='showMainboard';
    $arg2=getMainboardName($b[0]);
    $arg3=$p[0];
    $arg4=getProcessorName($p[0]);
    $title="lm_sensors_db - $arg2 - $arg4";
  }
  else if($a=='s' && $b) {
    $arg1=$b;
    $func='showMainboardAll';
    $arg2=getMainboardName($b);
    $title="lm_sensors_db - $arg2";
  }
  else if($a=='n') {
    $func='showAddMainboard';
    $title="lm_sensors_db - add new mainboard specs - step 1";
  }
  else {
    $func='showIndex';
    $title="lm_sensors_db - mainboards";
  }  
  echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/transitional.dtd\">\n";
  echo "<html>\n";
  echo "<head>\n";
  echo "<meta name=\"robots\" content=\"noindex\">\n";
  echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=ISO-8859-1\">\n";
  echo "<title>$title</title>\n";
  echo "</head>\n";
  echo "<body>\n";
//  echo "$a '". $b[0] . "' '" . $p[0] . "' $func<br />";
  if($arg4)
    $func($arg1, $arg2, $arg3, $arg4);
  else if($arg3)
    $func($arg1, $arg2, $arg3);
  else if($arg2)
    $func($arg1, $arg2);
  else if($arg1)
    $func($arg1);
  else
    $func();

  if($dbconn)
    mysql_close($dbconn);
  echo "</body>\n";
  echo "</html>\n";
?>
