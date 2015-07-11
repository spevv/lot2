<?php

namespace common\models;

class Plural 
{

	public static function downcounter($date)
	{
        $check_time = strtotime($date) - time();
        if($check_time <= 0){
            return false;
        }
 
        $days = floor($check_time/86400);
        $hours = floor(($check_time%86400)/3600);
        $minutes = floor(($check_time%3600)/60);
        $seconds = $check_time%60; 
 
        //$str = '';
        $array = [];
        if($days > 0) $array['days'] = Plural::declension($days,array('день','дня','дней')).' ';
        if($hours > 0) $array['hours'] = Plural::declension($hours,array('час','часа','часов')).' ';
        if($minutes > 0) $array['minutes'] = Plural::declension($minutes,array('минута','минуты','минут')).' ';
        //if($seconds > 0) $str .= Plural::declension($seconds,array('секунда','секунды','секунд'));
        
        if($array['days'] and $array['hours'])
        {
			$str = $array['days'].$array['hours'];
		}
		elseif($array['days'] and $array['minutes'])
		{
			$str = $array['days'].$array['minutes'];
		}
		elseif($array['hours'])
		{
			$str = $array['hours'];
		}
		elseif($array['minutes'])
		{
			$str = $array['minutes'];
		}
        return $str;
    }



	public static function plural($n, $forms)
	{
		
	    $plural = 0;
	    if ($n % 10 == 1 && $n % 100 != 11) {
	      $plural = 0;
	    } else {
	      if (($n % 10 >= 2 && $n % 10<=4) && ($n % 100 < 10 || $n % 100 >= 20)) {
	        $plural = 1;
	      } else {
	        $plural = 2;
	      }
	    }
	    return $forms[$plural];
	}
	
	
	public static function declension($digit,$expr,$onlyword=false){
        if(!is_array($expr)) $expr = array_filter(explode(' ', $expr));
        if(empty($expr[2])) $expr[2]=$expr[1];
        $i=preg_replace('/[^0-9]+/s','',$digit)%100;
        if($onlyword) $digit='';
        if($i>=5 && $i<=20) $res=$digit.' '.$expr[2];
        else
        {
            $i%=10;
            if($i==1) $res=$digit.' '.$expr[0];
            elseif($i>=2 && $i<=4) $res=$digit.' '.$expr[1];
            else $res=$digit.' '.$expr[2];
        }
        return trim($res);
    }


}