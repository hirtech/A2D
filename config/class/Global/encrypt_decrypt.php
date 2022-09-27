<?php 

/* GPLed Software.  Use at your own risk.  Only requirement is that you
   understand this statement: "The class Crypto has not been tested or varified
	as to the level of security or encryption it offers.  You are using this
	product at your own risk.   Do not expect highly regarded information to
	be completely safe with this or any encryption program."

   Use of this software confirms understanding of the above statement.
*/

$ralphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890 !,.:;?~@#$%^&*()_+-=][}{/><\"'`|";

$alphabet = $ralphabet . $ralphabet;


class Crypto {

function encrypt ($password,$strtoencrypt) {

$strtoencrypt = str_replace("t","[tab]",$strtoencrypt);
$strtoencrypt = str_replace("n","[new]",$strtoencrypt);
$strtoencrypt = str_replace("r","[ret]",$strtoencrypt);

global $ralphabet;
global $alphabet;

 for( $i=0; $i<strlen($password); $i++ )
 {
   $cur_pswd_ltr = substr($password,$i,1);
   $pos_alpha_ary[] = substr(strstr($alphabet,$cur_pswd_ltr),0,strlen($ralphabet));
  }

$i=0;
$n = 0;
$nn = strlen($password);
$c = strlen($strtoencrypt);

 while($i<$c)
 {
   $encrypted_string .= substr($pos_alpha_ary[$n],strpos($ralphabet,substr($strtoencrypt,$i,1)),1);
 
   $n++;
   if($n==$nn) $n = 0;
   $i++;
  }

return $encrypted_string;

}




function decrypt ($password,$strtodecrypt) {

global $ralphabet;
global $alphabet;

 for( $i=0; $i<strlen($password); $i++ )
 {
   $cur_pswd_ltr = substr($password,$i,1);
   $pos_alpha_ary[] = substr(strstr($alphabet,$cur_pswd_ltr),0,strlen($ralphabet));
  }

$i=0;
$n = 0;
$nn = strlen($password);
$c = strlen($strtodecrypt);

 while($i<$c) {
   $decrypted_string .= substr($ralphabet,strpos($pos_alpha_ary[$n],substr($strtodecrypt,$i,1)),1);
 
   $n++;
   if($n==$nn) $n = 0;
   $i++;
  }

$decrypted_string = str_replace("[tab]","t", $decrypted_string);
$decrypted_string = str_replace("[new]","n", $decrypted_string);
$decrypted_string = str_replace("[ret]","r", $decrypted_string);

return $decrypted_string;


}


function cryption_table ($password) {
 
global $ralphabet;
global $alphabet;

$table = "";
for( $i=0; $i<strlen($password); $i++ ) {
   $cur_pswd_ltr = substr($password,$i,1);
   $pos_alpha_ary[] = substr(strstr($alphabet,$cur_pswd_ltr),0,strlen($ralphabet));
  }

$table .= "<table border=1 cellpadding=\"0\" cellspacing=\"0\">";
$table .= "<tr><td></td>";

for( $j=0; $j<strlen($ralphabet); $j++ ) {

$ltr = substr($ralphabet,$j,1);
$table .= <<<EOF
<td align="center"><font size="2" face="arial">$ltr</td>
EOF;
}

print "</tr>";


for( $i=0; $i<count($pos_alpha_ary); $i++ ) {

$z = $i + 1;
$table .= <<<EOF
<tr><td align="right"><font size="2"><b>$z</b></font></td>
EOF;

for( $k=0; $k<strlen($pos_alpha_ary[$i]); $k++ ) {

$ltr = substr($pos_alpha_ary[$i],$k,1);
$table .= <<<EOF
<td align="center">
<font color="red" size="2" face="arial">$ltr</td>
EOF;

}
$table .= "</tr>";
}

$table .= "</table>n";

return $table;

}

} // end class Crypto

?>

