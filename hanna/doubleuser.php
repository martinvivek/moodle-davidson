<html>
<head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
	<table border="1" cellpadding="0" cellspacing="1" width="100%"  >

	<tr >
	 <td >id </td>
	 <td >מספר זהות </td>
     	 <td >שם פרטי</td>
     	 <td >שם משפחה</td>
      	<td >email</td>
      	<td >username</td>  
      	<td >עיר</td>
      	<td >כניסה אחרונה</td>                        	          
	</tr>

<?php
$counter=0;

$dbtype    = 'mysqli';
$dbhost    = 'localhost';
$dbname    = 'moodle29_live';
$dbuser    = 'root';
$dbpass    = 'nhy65tgb';

	
$MConn = @mysql_connect($dbhost, $dbuser, $dbpass);
//mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $MConn);
mysql_set_charset('utf8',$MConn); 


	
if (!$MConn) die("Can't connect to moodle SQL server.");

	if (!@mysql_select_db($dbname, $MConn)) die("Can't select moodle database.");

$Mresult = mysql_query("SELECT * FROM mdl_user WHERE `deleted` = '0' ORDER BY username, email, firstname, lastname ");
//mysql_set_charset('utf8',$Mresult); 
$counter=0;
$found=0;
$mail="";  
$mail_new="";
$first="";
$first_new="";
$last="";
$last_new="";
$username="";
$username_new="";

$nrows = mysql_num_rows($Mresult);
echo $nrows;
for ($i=0; $i < $nrows; $i++) 
{
$row = mysql_fetch_array($Mresult);    
	$mail_new=$row['email'];
	  $first_new=$row['firstname'];
    $last_new = $row['lastname'];
	$username_new = $row['username'];
   $idnumber_new = $row['idnumber'];
   $city_new = $row['city'];
   $id_new = $row['id'];

	 if (($mail_new==$mail) )    // double emails
//	     if (($first_new == $first) and ($last_new == $last))    // double names
  //	if (($username_new==$username) )      // double usernames
	{  
	$found++;
		?>
	   <tr bgcolor="#d2f4fe">
		<td >
      <?php 	     
         echo $row['id']; ?>
	</td>
	<td >
		<?php	   	
      echo $row['idnumber'];  ?>
	</td>			

     	<td >
		<?php	   	
      echo $row['firstname'];  ?>
	</td>			
     	<td >
		<?php		
      echo $row['lastname'];  ?>
	</td>				
	<td >
		<?php 			 
          echo  $row['email'];      
      ?>
	</td>									
	<td ><?php  
	  
          echo  $row['username'];      
      ?>
	</td>	
	<td ><?php  
	  
          echo  $row['city'];      
      ?>
	</td>	
  	<td ><?php  
	  
          echo  $row['lastlogin'];      
      ?>
	</td>			
</tr>
	<tr>
  		<td >
      <?php 	     
         echo $id; ?>
	</td>
	     	<td >
		<?php	   	
      echo $idnumber;  ?>
	</td>			
     	<td >
		<?php	   	
      echo $first;  ?>
	</td>			
     	<td >
		<?php		
      echo $last;  ?>
	</td>				
	<td >
		<?php 			 
          echo  $mail;      
      ?>
	</td>									
	<td ><?php  
	  
          echo  $username;      
      ?>
	</td>	
	<td ><?php  
	  
          echo  $city;      
      ?>
	</td>	
  	<td ><?php  
	  
  //        echo  $lastlogin;      
      ?>
	</td>				
</tr>

<?php
}
  $username=$username_new;
  $mail=$mail_new;
  $first=$first_new;
  $last=$last_new;
  $idnumber=$idnumber_new;
  $city=$city_new;
  $id=$id_new;

$counter++;
 
}   

echo "<br>";
echo $found;	
mysql_close();

	?>

</table>
</body>
</html>