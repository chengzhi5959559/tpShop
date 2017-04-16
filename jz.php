<?php
/**
 * array(
	 * 	array(0,0,0),
	 * 	array(0,0,0),
	 * 	array(0,0,0),
 * )
 * 
 * $arr[$x][$y]
 * 右向移动 ->  $y++
 * 向下移动 ->  $x++
 * 初始化一个变量 $direct='e' 代表方向，n s w e
 * 什么时候改变方向：
 * 	  1. $y >= $n（向右移动）
 * 	  2. 如果下一个位置被占(<>0)了就要改变方向
 */

jz($_GET['num']);

function jz($num)
{
	// 构造二维数组
	$arr = array();
	// 行
	for($i=0; $i<$num; $i++)
	{
		// 列
		for ($j=0; $j<$num; $j++)
		{
			$arr[$i][$j] = 0;
		}
	}
	// 计算最后一数
	$max = $num * $num + $num - 1;
	// 初始化几个变量
	$direction = 'e';  // 方向
	$x = $y = 0;  // 当前应该放的位置
	// 循环向数组中添加第个数
	for($i=$num; $i<=$max; $i++)
	{
		if($arr[$x][$y] == 0)
			$arr[$x][$y] = $i;
		else 
		{
			// 选择下一个位置
			if($direction == 'e')
			{
				// 下一个位置在格内，并且没有被占用
				if(($y+1)<$num && $arr[$x][$y+1] == 0)
				{
					$y++;
					$arr[$x][$y] = $i;
				}
				else 
					$direction = 's';
			}
			if($direction == 's')
			{
				if(($x+1)<$num && $arr[$x+1][$y] == 0)
				{
					$x++;
					$arr[$x][$y] = $i;
				}
				else 
					$direction = 'w';
			}
			if($direction == 'w')
			{
				if(($y-1)>=0 && $arr[$x][$y-1] == 0)
				{
					$y--;
					$arr[$x][$y] = $i;
				}
				else 
					$direction = 'n';
			}
			if($direction == 'n')
			{
				if(($x-1)>=0 && $arr[$x-1][$y] == 0)
				{
					$x--;
					$arr[$x][$y] = $i;
				}
				else 
				{
					$direction = 'e';
					if(($y+1)<$num && $arr[$x][$y+1] == 0)
					{
						$y++;
						$arr[$x][$y] = $i;
					}
					else 
						$direction = 's';
				}
			}
		}
	}
	// 根据二维数组输出图片
	$html = "<table border='1'>";
	// 循环行
	for($i=0; $i<$num; $i++)
	{
		$html .= "<tr>";
		// 循环列
		for($j=0; $j<$num; $j++)
		{
			$html .= "<td>";
			$html .= $arr[$i][$j];
			$html .= "</td>";
		}
		$html .= "</tr>";
	}
	$html .= "</table>";
	echo $html;
}