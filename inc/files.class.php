<?php
#----- FILES CLASS File  -----#
# Description: Files Functions Class File
# Author: Bianca Mitroi
# Version: 1.0

class FILES{
    
    /** read_file_to_array
    * read all data from file and return array
    * 
    * @param string $filename
    * @param string $delimiter
    */
    public static function read_file_to_array($filename,$delimiter){
        if (!file_exists($filename)) return false;
        $file=fopen($filename,"r");
        if (!$file) return false;
        while (!feof($file)){
            $line=fgets($file);   
            $arr[]=explode($delimiter,$line);
        }
        fclose($file);
        return $arr;
    }
    
    /** write_file_from_array
    * write array data to file
    * 
    * @param string $filename
    * @param array $arr
    */
    public static function write_file_from_array($filename,$arr,$delimiter=','){
        $text="";
        foreach ($arr as $index=>$line)
            $text.=implode($delimiter,$line)."\r\n";
        $file=fopen($filename,"w");
        fwrite($file,$text);
        fclose($file);
    }
    
    /** write_file_from_string
    * write string to file
    * 
    * @param string $filename
    * @param string $string
    */
    public static function write_file_from_string($filename,$string){
        $file=fopen($filename,"w");
        fwrite($file,$string);
        fclose($file);    
    }
    
}
  
?>
