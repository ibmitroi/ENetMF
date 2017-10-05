<?php
#----- MSC THESIS CALCULATE File -----#
# Description: calculate file
# Author: Bianca Mitroi
# Version: 1.0

# Execution time start
$time_start=microtime(true); 

# PHP Script Configuration
error_reporting(E_ALL);             # report all errors
ini_set('max_execution_time',0);    # unlimited execution time
ini_set('memory_limit', '-1');      # unlimited memory to use

# get VARIABLES configuration data
$config_variables=require_once __DIR__."/config/variables.php";    
$config_general=require_once __DIR__."/config/general.php";

# load classes
require_once __DIR__."/inc/math.class.php";
require_once __DIR__."/inc/files.class.php";

# set datetime zone
date_default_timezone_set($config_general["timezone"]); 

# set factor
$factors=$config_variables["factors"];

# get variables matrix
$matrix_vars=FILES::read_file_to_array(__DIR__."/results/parameters.csv",",");

# get U.base and U.test
for ($key=1; $key<=5; $key++){
    $matrix_U["base"][$key]=FILES::read_file_to_array(__DIR__."/import_data/u".$key.".base","\t");
    $matrix_U["test"][$key]=FILES::read_file_to_array(__DIR__."/import_data/u".$key.".test","\t");    
}

# results first row
$results[]=["MAE","N_0","N_1","N_2","N_3","N_4","ALPHA","RHO_1","RHO_2","LAMBDA_1","LAMBDA_2","P_init","Q_init","ENetMF","KBV","GM"];

##### START #####

$P_init=$config_variables["P_init"];
$Q_init=$config_variables["Q_init"];

foreach ($matrix_vars as $index=>$variables){
    if (!is_array($variables) || count($variables)<8) continue;
    list($alfa,$ro1,$ro2,$lambda1,$lambda2,$v1,$v2,$v3)=$variables;    
    $alfa=floatval($alfa); $ro1=floatval($ro1); $ro2=floatval($ro2); $lambda1=floatval($lambda1); $lambda2=floatval($lambda2); $v3=intval($v3);
    $MAE_FINAL=0; $N_FINAL=[0,0,0,0,0];
    for ($key=1; $key<=5; $key++){    
        # empty P and Q
        $matrix_P=[];
        $matrix_Q=[];
        # calculate matrix P and matrix Q
        foreach ($matrix_U["base"][$key] as $index2=>$row_data){   
            if (!is_array($row_data) || count($row_data)<4) continue;
            list($user,$item,$rating,$code)=$row_data;                 
            $user=intval($user); $item=intval($item); $rating=intval($rating);
            for ($factor=1; $factor<=$factors; $factor++){
                $matrix_P[$user][$factor]=MATH::set_P($user,$factor);
                $matrix_Q[$item][$factor]=MATH::set_Q($item,$factor);
            }
        }
        # calculate MAE($key), N($key,0), N($key,1), N($key,2), N($key,3), N($key,4)
        $MAE[$key]=0; $N[$key]=[0,0,0,0,0];
        foreach ($matrix_U["test"][$key] as $index2=>$row_data){
            if (!is_array($row_data) || count($row_data)<4) continue;
            list($test_user,$test_item,$test_rating,$test_code)=$row_data;   
            $test_user=intval($test_user); $test_item=intval($test_item); $test_rating=intval($test_rating);
            $dif=abs($test_rating-MATH::set_RE($test_user,$test_item));
            $MAE[$key]=$MAE[$key]+$dif;
            $N[$key][$dif]++;
        }
        $MAE[$key]=$MAE[$key]/20000;    $MAE_FINAL=$MAE_FINAL+$MAE[$key];
        $N[$key][0]=$N[$key][0]/20000;  $N_FINAL[0]=$N_FINAL[0]+$N[$key][0];
        $N[$key][1]=$N[$key][1]/20000;  $N_FINAL[1]=$N_FINAL[1]+$N[$key][1];
        $N[$key][2]=$N[$key][2]/20000;  $N_FINAL[2]=$N_FINAL[2]+$N[$key][2];
        $N[$key][3]=$N[$key][3]/20000;  $N_FINAL[3]=$N_FINAL[3]+$N[$key][3];
        $N[$key][4]=$N[$key][4]/20000;  $N_FINAL[4]=$N_FINAL[4]+$N[$key][4];
    }
    # calculate FINAL MAE and N(0->4)
    $MAE_FINAL=$MAE_FINAL/5;
    $N_FINAL[0]=$N_FINAL[0]/5;
    $N_FINAL[1]=$N_FINAL[1]/5;
    $N_FINAL[2]=$N_FINAL[2]/5;
    $N_FINAL[3]=$N_FINAL[3]/5;
    $N_FINAL[4]=$N_FINAL[4]/5;
    
    # save results vor this variables
    $results[]=[$MAE_FINAL,$N_FINAL[0],$N_FINAL[1],$N_FINAL[2],$N_FINAL[3],$N_FINAL[4],$alfa,$ro1,$ro2,$lambda1,$lambda2,$P_init,$Q_init,$v1,$v2,$v3];
}

FILES::write_file_from_array(__DIR__."/results/results.csv",$results,",");
  
# Execution time end
$time_end=microtime(true);
$execution_time=($time_end-$time_start);

# if Ajax call, send Ajax response
if (isset($_POST['ajax'])&&$_POST['ajax']==1){
    $json=["response"=>"ok","execution_time"=>$execution_time];
    echo json_encode($json);
    exit;
}

display_html();

/** display_html
* display html
* 
*/
function display_html(){
    global $execution_time;
    print '
    <!DOCTYPE html>
    <html>
    <head>
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Lang" content="en">
    <meta name="author" content="Bianca Mitroi">
    <meta name="description" content="Calculate">
    <meta name="keywords" content="Calculate">
    <title>Calculate</title>
    </head>
    <body>
        <br><br><br>
        <b>Total Execution Time:</b> '.$execution_time.'
        <br>
        <a href="/results/results.csv?'.time().'">Get results file</a>  
    </body>
    </html>
    ';
}   
  
?>
