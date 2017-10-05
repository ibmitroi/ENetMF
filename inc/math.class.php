<?php
#----- MATH CLASS File -----#
# Description: Math Functions Class File
# Author: Bianca Mitroi
# Version: 1.0

class MATH{

    public static function set_P($user,$factor){
        global $item;
        $x=round(self::__P_x($user,$factor,$item),4);           
        $gamma=round(self::__P_gamma(),4);                             
        if ($x>0 && abs($x)>$gamma) return ($x-$gamma);
        elseif ($x<0 && abs($x)>$gamma) return ($x+$gamma);
        else return 0;
    }
    
    public static function set_Q($item,$factor){
        global $user;
        $x=round(self::__Q_x($item,$factor,$user),4);
        $gamma=round(self::__Q_gamma(),4);
        if ($x>0 && abs($x)>$gamma) return ($x-$gamma);
        elseif ($x<0 && abs($x)>$gamma) return ($x+$gamma);
        else return 0;
    }
    
    public static function set_RE($a,$b){
        global $matrix_P, $matrix_Q, $factors;
        $sum=0;
        for ($factor=1; $factor<=$factors; $factor++)
            $sum=$sum+self::get_P($a,$factor)*self::get_Q($b,$factor);
        $re=round($sum);
        $re=($re<1)? 1:$re;
        $re=($re>5)? 5:$re;
        return $re;
    }
    
    public static function random_PQ_int($min,$max,$decimals=2){
        $multiplier=10**$decimals;
        $pq_int=mt_rand($min,$max*$multiplier)/$multiplier;
        return $pq_int;
    }
    
    ########## PRIVATE FUNCTIONS ##########

    private static function get_P($x,$y){
        global $matrix_P, $P_init;
        return (!isset($matrix_P[$x][$y]))? $P_init:$matrix_P[$x][$y];
    }
    
    private static function get_Q($x,$y){
        global $matrix_Q, $Q_init;
        return (!isset($matrix_Q[$x][$y]))? $Q_init:$matrix_Q[$x][$y];
    }
    
    private static function __E($user,$item){
        global $rating, $factors;
        $sum=0;
        for ($factor=1; $factor<=$factors; $factor++)
            $sum=$sum+self::get_P($user,$factor)*self::get_Q($item,$factor);
        $e=$rating-$sum;
        return $e;        
    }
    
    private static function __P_x($user,$factor,$item){
        global $alfa, $ro1;
        $x=self::get_P($user,$factor)+$alfa*(self::__E($user,$item)*self::get_Q($item,$factor)-$ro1*self::get_P($user,$factor));
        return $x;    
    }
    
    private static function __P_gamma(){
        global $alfa, $lambda1;
        return ($alfa*$lambda1/2);
    }
    
    private static function __Q_x($item,$factor,$user){
        global $alfa, $ro2;
        $x=self::get_Q($item,$factor)+$alfa*(self::__E($user,$item)*self::get_P($user,$factor)-$ro2*self::get_Q($item,$factor));
        return $x;
    }
    
    private static function __Q_gamma(){
        global $alfa, $lambda2;
        return ($alfa*$lambda2/2);
    }

    
}

?>
