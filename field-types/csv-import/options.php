<div id="csv_import_columns"></div>

<script>	
	BigTreeListMaker("#csv_import_columns", 
					 "columns", 
					 "Columns", 
					 ["Data Key", "Column Title"], 
					 [{ key: "key", type: "text" }, { key: "column", type: "text" }],
					 <?=json_encode($data["columns"])?>
					);
</script>