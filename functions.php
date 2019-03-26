<?php			
	function price_format ($price) {
		return number_format(ceil($price), 0, "." , " ")." ₽";		
	}

	function include_template($name, $data) {
		$name = 'templates/' . $name;
		$result = '';

		if (!file_exists($name)) {
			return $result;
		}
		ob_start();
		extract($data);
		require $name;

		$result = ob_get_clean();

		return $result;
	}
// показывает сколько осталось часов и минут без учета дней и месяцев
	function time_remaining ($end_date){
		date_default_timezone_set("Asia/Jerusalem");  // Такой часовой пояс, так как я нахожусь в Израиле
		$cur_date = date_create("now");
		$tom_mid = date_create((string)$end_date);
		$diff = date_diff($cur_date, $tom_mid);
		$interval = date_interval_format($diff, "%H:%I");
	
		return $interval;
						
	}

function include_component($name, $data) {
    $name = 'components/' . $name;
    $result = '';

    if (!file_exists($name)) {
        return $result;
    }
    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

?>