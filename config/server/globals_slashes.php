<?
foreach($_GET as $pk=>$pv)
{
	if(is_array($pv))
	{
		foreach($pv as $ppk=>$ppv)
		{
			$_GET[$pk][$ppk] = addslashes($ppv);
			$_REQUEST[$pk][$ppk] = addslashes($ppv);
		}
	}
	else
	{
		$_GET[$pk] = addslashes($pv);
		$_REQUEST[$pk] = addslashes($pv);
	}
}

foreach($_POST as $pk=>$pv)
{
	if(is_array($pv))
	{
		foreach($pv as $ppk=>$ppv)
		{
			$_POST[$pk][$ppk] = addslashes($ppv);
			$_REQUEST[$pk][$ppk] = addslashes($ppv);
		}
	}
	else
	{
		$_POST[$pk] = addslashes($pv);
		$_REQUEST[$pk] = addslashes($pv);
	}
}
?>