<?php

class Format
{
    /**
    * Storage Format
    *
    * @param float  $storage Integer Storage
    * @param string $nivel   Nivel Storage
    *
    * @return string
    */
    public static function storageFormat($storage, $nivel = null)
   {
       $storage = trim($storage);
       if (!is_numeric($storage)) {
           return $storage;
       }
       $sizes = ['KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4, 'PB' => 5];
       if (!is_null($nivel) && array_key_exists(strtoupper($nivel), $sizes)) {
           $multi = 1024 * pow(1000, $sizes[strtoupper($nivel)]);
           $storage = $storage * $multi;
           if ($storage >= $multi) {
               $storage = $storage / 1024;
           }
       }
       $sufix = 'B';
       foreach (array_keys($sizes) as $size) {
           if ($storage >= 1000) {
               $storage = $storage / 1000;
               $sufix = $size;
           }
       }
       return toFixed($storage, 1) . $sufix;
   }

   public static function toFixed($val, $precision)
   {
       $paramns = explode('.', $val);
   
     return $paramns[0].'.'.substr($paramns[1], 0, $precision);
   }
}