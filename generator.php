<?php
#----- Variables Generator File -----#
# Description: Variables Generator File
# Author: Bianca Mitroi
# Version: 1.0

# PHP Script Configuration
error_reporting(E_ALL);             # report all errors
ini_set('max_execution_time',0);    # unlimited execution time
ini_set('memory_limit', '-1');      # unlimited memory to use

# get VARIABLES configuration data
$config_variables=require_once __DIR__."/config/variables.php";    
$config_general=require_once __DIR__."/config/general.php";

# set datetime zone
date_default_timezone_set($config_general["timezone"]); 

##### START #####

$result=variables_generator();
$file=fopen(__DIR__."/results/parameters.csv","w");
fwrite($file,$result);
fclose($file);

# if Ajax call, send Ajax response
if (isset($_POST['ajax'])&&$_POST['ajax']==1){
    $json=["response"=>"ok"];
    echo json_encode($json);
    exit;
}

display_html();


##### FUNCTIONS #####

/** variables_generator
* generate all variables combinations
* return MATRIX string to be written to file (CSV format)
* COLUMNS:  ALFA  RO1  RO2  LAMBDA1  LAMBDA2  (1 if generated values match first option)  (1 if generated values match second option)  (1 if generated values match third option)  
* 
*/
function variables_generator(){
    global $config_variables;
    $result="";
    # ALFA interval
    for ($alfa=$config_variables["alfa"][0];$alfa<=$config_variables["alfa"][1];$alfa=$alfa+$config_variables["alfa"][2]){
        # RO1 interval
        for ($ro1=$config_variables["ro1"][0];$ro1<($config_variables["ro1"][1]+$config_variables["ro1"][2]);$ro1=$ro1+$config_variables["ro1"][2]){     
            # RO2 interval
            for ($ro2=$config_variables["ro2"][0];$ro2<=$config_variables["ro2"][1];$ro2=$ro2+$config_variables["ro2"][2]){
                # LAMBDA1 interval
                for ($lambda1=$config_variables["lambda1"][0];$lambda1<=$config_variables["lambda1"][1];$lambda1=$lambda1+$config_variables["lambda1"][2]){
                    # LAMBDA2 interval
                    for ($lambda2=$config_variables["lambda2"][0];$lambda2<=$config_variables["lambda2"][1];$lambda2=$lambda2+$config_variables["lambda2"][2]){
                        $generated=[$alfa,$ro1,$ro2,$lambda1,$lambda2,1,0,0]; # generated variables
                        # CONDITIONS for the second and third option
                        if ($ro1==$ro2 && $lambda1==0 && $lambda2==0) $generated[6]=1; # generated variables match second option
                        if ($ro2==0 && $lambda1==0) $generated[7]=1; # generated variables match third option   
                        $result.=implode(",",$generated)."\r\n"; 
                    }
                }
            }
        }  
    }
    return $result;
}

/** display_html
* display html
* 
*/
function display_html(){
    print '
    <!DOCTYPE html>
    <html>
    <head>
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Lang" content="en">
    <meta name="author" content="Bianca Mitroi">
    <meta name="description" content="Variables Generator">
    <meta name="keywords" content="Variables General">
    <title>Variables Generator</title>
    </head>
    <body>
        <br><br><br>
        <a href="/results/parameters.csv?'.time().'">Get generated variables file</a>  
    </body>
    </html>
    ';
}

?>
