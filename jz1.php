<?php

function BuildHelixMatrix($length)
{
	$i=0;$j=0;$k=0;
	$a = 1;
	
	$i = $k = 0;
	$j = $length-1;
	$Matri = array();
	
	for (; $k<($length+1)/2; $k++)
	{
		while ($i < $length-$k)
		{
			$Matrix[$i++*$length + $j] = $a++;
		}
		$i--;
		$j--;
		while ($j > $k-1)
		{
			$Matrix[$i*$length + $j--] = $a++;
		}
		$i--;
		$j++;
		
		while ($i> $k-1)
		{
			$Matrix[$i--*$length + $j] = $a++;
		}
		$i++;
		$j++;		
		while ($j<$length-$k-1)
		{
			$Matrix[$i*$length + $j++] = $a++;
		}
		$i++;
		$j--;	
	}
	ShowMatrix($Matrix, $length);

	echo "<br>";
}

function ShowMatrix($Matrix, $length)
{
	$i;$j;
	
	for ($i = 0; $i<$length; $i++)
	{
		for ($j=0; $j<$length; $j++)
		{ 
			echo  '&nbsp;&nbsp;&nbsp;&nbsp;'.$Matrix[$i*$length + $j].'&nbsp;&nbsp;&nbsp;&nbsp;';
		}
		echo "<br>";
	}	
}

BuildHelixMatrix(15);