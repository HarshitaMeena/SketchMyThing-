<?php
function redirectWithPOST($POSTData, $targetPage) {
	echo "<form action='$targetPage' method='post' name='frm'>";
	foreach ($POSTData as $a => $b) {
		echo "<input type='hidden' name='".$a."' value='".$b."'>";
	}
	echo "</form>
	<script language='JavaScript'>
		document.frm.submit();
	</script>";
}
?>