<?php
#----- Variables Config File -----#
# Description: Initial Variables Config File
# Author: Bianca Mitroi
# Version: 1.0

return [
    # ALFA interval (min,max,step)
    "alfa"      =>  [0.001,0.001,0.001],
    
    # RO1 interval (min,max,step)
    "ro1"       =>  [0.25,0.25,0.01],
    
    # RO2 interval (min,max,step)
    "ro2"       =>  [0.25,0.25,0.01],
    
    # LAMBDA1 interval (min,max,step)
    "lambda1"   =>  [0.25,0.25,0.1],
    
    # LAMBDA2 interval (min,max,step)
    "lambda2"   =>  [0.25,0.25,0.1],
    
    # Matrix P initial
    "P_init"    =>  0.7,
    
    # Matrix Q initial
    "Q_init"    =>  0.7,
    # Factor
    "factors"   =>  2
    
];


?>
