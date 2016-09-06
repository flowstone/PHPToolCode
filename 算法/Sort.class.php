<?php 

header('content-type:text/html;charset=utf-8');
/**
 * 排序方法
 */
class Sort {
	/**
	 * 插入排序   从小到大
	 * @Author         YaoXue
	 * @DateTim        2016-07-27
	 * @param   array &$arr      
	 * @return  void           
	 */
	public function insertSort(&$arr) {
		//数组的大小
		$arr_size = count($arr);
		//确定大循环的次数
		for ($i = 1; $i < $arr_size; $i++) {
			//保存要插入的数
			$insert_val = $arr[$i];
			//确定要进行比较的数的下标
			$bj_index = $i -1;

			while ($bj_index >= 0 && $arr[$bj_index] > $insert_val) {
				$arr[$bj_index + 1]  = $arr[$bj_index];
				$bj_index--;
			}

			$arr[$bj_index + 1] = $insert_val;
		}
	}

	/**
	 * 冒泡排序   从小到大
	 * @Author         YaoXue
	 * @DateTim        2016-07-27
	 * @param   array &$arr      
	 * @return  void           
	 */
	public function bubbleSort(&$arr) {

		$arr_size = count($arr);
		$is_flag = 0;

		for ($i = 0; $i < $arr_size - 1; $i++) {
			for ($j = 0; $j< $arr_size -1 - $i; $j++) {
				$temp = 0;
				if ($arr[$j] > $arr[$j + 1]) {
					$temp = $arr[$j];
					$arr[$j] = $arr[$j + 1];
					$arr[$j + 1] = $temp;
					$is_flag = 1;
				}
			}
			if ($is_flag == 0) {
				return;
			} else {
				$is_flag = 0;
			}

		}
	}

	/**
	 * 选择排序   从小到大
	 * @Author         YaoXue
	 * @DateTim        2016-07-27
	 * @param   array &$arr      
	 * @return  void           
	 */
	public function selectSort(&$arr) {
			//数组长度
			$arr_size = count($arr);
			for ($i = 0; $i < $arr_size - 1; $i++) {
				//默认为数组第一个数为最小数
				$min = $arr[$i];
				$min_index = $i;
				for($j = $i + 1; $j< $arr_size ; $j++) {
					if ($min > $arr[$j]) {
						$min = $arr[$j] ;
						$min_index = $j;
					}
				}

				$arr[$min_index] = $arr[$i];
				$arr[$i] = $min;
			}
	}


}

//测试代码
//$arr = array(10, 7, -1);
//$sort = new Sort;
//$insert_sort->insertSort($arr);
//$sort->bubbleSort($arr);
//$sort->selectSort($arr);
//echo '<pre>';
//var_dump($arr);