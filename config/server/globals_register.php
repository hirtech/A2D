<?
$glob=array("SERVER");

if(isset($GPC_vars))
{ foreach($GPC_vars as $var)
   { foreach(array("GET","POST","COOKIE") as $avar)
       if(isset($GLOBALS["HTTP_".$avar."_VARS"][$var]))
         { $$var=$GLOBALS["HTTP_".$avar."_VARS"][$var];
         }
   }               
}
else
{ $glob=array_merge(array("GET","POST","COOKIE"),$glob);
}

foreach($glob as $avar)
{ $arr=$GLOBALS["HTTP_".$avar."_VARS"];
	if(is_array($arr)){
		foreach($arr as $var => $res)
	   $$var=$res;}
}
if(is_array($HTTP_POST_FILES)){
foreach ($HTTP_POST_FILES as $name => $value) {
	$$name = $value["tmp_name"];
	foreach($value as $k=>$v) {
		$varname_ = $name."_".$k;
		$$varname_ = $v;
	}
}
reset($HTTP_POST_FILES);
}
?>