<?php
	// Load in PapaParse for CSV parsing
	if (!in_array("*/com.timbuckingham.csv-import/js/papaparse/papaparse.min.js", $bigtree["js"])) {
		$bigtree["js"][] = "*/com.timbuckingham.csv-import/js/papaparse/papaparse.min.js";
	}

	$current_data = false;
	$keyed_columns = array();

	foreach ($field["options"]["columns"] as $item) {
		$keyed_columns[$item["key"]] = $item["column"];
	}
?>
<fieldset>
	<label><?=$field["title"]?><small><?=$field["subtitle"]?></small></label>
	<input<? if ($field["required"] && !$current_data) { ?> class="required"<? } ?> type="file" tabindex="<?=$field["tabindex"]?>" id="<?=$field["id"]?>" name="<?=$field["key"]?>[file]" accept=".csv" />
	<?php
		if (is_array($field["value"]) && count($field["value"])) {
			$current_data = true;
	?>
	<p class="note">Current data set has <?=count($field["value"])?> rows. Uploading a new CSV file will replace this data.</p>
	<?php
		}
	?>
</fieldset>

<div id="<?=$field["id"]?>_table"></div>

<fieldset id="<?=$field["id"]?>_first_row" style="display: none;">
	<label>First Row Contains Column Titles</label>
	<select name="<?=$field["key"]?>[first_row_titles]">
		<option></option>
		<option value="no">No</option>
		<option value="yes">Yes</option>
	</select>
</fieldset>

<style>
	#<?=$field["id"]?>_table {
		overflow-x: auto;
		width: 898px;
	}
</style>

<script>
	(function(field, columns, container) {
		var CSVData;

		function checkUpload() {
			Papa.parse(field.get(0).files[0], { complete: function(results) {
				if (results.errors.length) {
					var html = "";

					for (var i in results.errors) {
						html += '<p class="form_error">' + results.errors[i] +'</p>';
					}

					container.html(html);
				} else {
					renderWidget(results.data.slice(0, 5));
				}
			}});
		}

		function renderWidget(rows) {
			var html = '<fieldset><label>Sample Data</label><table id="<?=$field["id"]?>_table"><thead><tr>';
			var x = 0;

			for (var i = 0; i < rows[0].length; i++) {
				html += '<th><select name="<?=$field["key"]?>[column_keys][' + i + ']"><option></option>';
				x = 0;

				for (var key in columns) {
					html += '<option value="' + key + '"';

					if (x == i) {
						html += ' selected="selected"';
					}

					html += '>' + columns[key] + '</option>';
					x++;
				}

				html += '</select></th>';
			}

			html += '</tr></thead><tbody>';

			for (i = 0; i < rows.length; i++) {
				html += '<tr>';

				for (x = 0; x < rows[i].length; x++) {
					html += '<td>' + rows[i][x] + '</td>';
				}

				html += '</tr>';
			}

			html += '</tbody></table></fieldset>';
			container.html(html);

			$("#<?=$field["id"]?>_first_row").show().find("select").addClass("required");
		}

		field.on("change", checkUpload);

	})($("#<?=$field["id"]?>"), <?=json_encode($keyed_columns)?>, $("#<?=$field["id"]?>_table"));
</script>