<?php

// DEFINE RETURN VALUES
define('R_ELEGAL_INPUT', -1);
define('R_NOT_VALID', -2);
define('R_VALID', 1);

function ValidateID($str)
{
   //Convert to string, in case numeric input
   $IDnum = strval($str);

   //validate correct input
   if(! ctype_digit($IDnum)) // is it all digits
      return R_ELEGAL_INPUT;
   if((strlen($IDnum)>9) || (strlen($IDnum)<7))
      return R_ELEGAL_INPUT;

   //If the input length less then 9 and bigger then 5 add leading 0
   while(strlen($IDnum)<9) 
   {
      $IDnum = '0'.$IDnum;
   }

   $mone = 0;
   //Validate the ID number
   for($i=0; $i<9; $i++)
   {
      $char = mb_substr($IDnum, $i, 1);
      $incNum = intval($char);
      $incNum*=($i%2)+1;
      if($incNum > 9)
         $incNum-=9;
      $mone+= $incNum;
   }

   if($mone%10==0)
      return R_VALID;
   else
      return R_NOT_VALID;
}

?>