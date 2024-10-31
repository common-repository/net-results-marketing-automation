<?php
include_once('../../../wp-blog-header.php');
?>
<script type="text/javascript">

	function getAccessToken() {
		var	queryString = window.location.hash.substr(1),
			queries = queryString.split("&"),
			params = {},
			i= 0,
			pair;
		for (;i<queries.length;i++) {
			pair = queries[i].split('=');
			params[pair[0]] = pair[1];
		}
		return params.access_token;
	};

	window.opener.setAccessToken(getAccessToken());
	window.close();
</script>
