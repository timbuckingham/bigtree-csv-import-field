<?php
	ini_set("auto_detect_line_endings", true);

	if ($field["file_input"]["file"]["tmp_name"]) {
		$file_handle = fopen($field["file_input"]["file"]["tmp_name"], "r");
		$field["output"] = array();
		$x = 0;

		while ($row = fgetcsv($file_handle)) {			
			if ($x > 0 || $field["input"]["first_row_titles"] == "no") {
				$data_row = array();

				foreach ($row as $index => $value) {
					if (!empty($field["input"]["column_keys"][$index])) {
						$data_row[$field["input"]["column_keys"][$index]] = $value;
					}
 				}

 				if (array_filter($data_row)) {
 					$field["output"][] = $data_row;
 				}
			}

			$x++;
		}
	} else {
		$field["ignore"] = true;
	}
