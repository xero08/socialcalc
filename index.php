<html>
<head>
<title>The Social Calculator</title>
<link rel="stylesheet" href="style.css">
<script src="jquery-1.9.0.min.js"></script>
<script src="actions.js"></script>
</head>
<body>
<div class="box">
	<div class="display"><input type="text" readonly size="18" id="d"></div>
	<div class="keys">
		<p><input type="button" class="button pink" value="&#8730;" onclick='v("sqrt")'><input type="button" class="button pink" value="1/x" onclick='v("inv")'><input type="button" class="button pink" value="&#8592;" onclick='v("bksp")'><input type="button" class="button pink" value="/" onclick='v("/")'></p>
		<p><input type="button" class="button black" value="7" onclick='v("7")'><input type="button" class="button black" value="8" onclick='v("8")'><input type="button" class="button black" value="9" onclick='v("9")'><input type="button" class="button pink" value="X" onclick='v("*")'></p>
		<p><input type="button" class="button black" value="4" onclick='v("4")'><input type="button" class="button black" value="5" onclick='v("5")'><input type="button" class="button black" value="6" onclick='v("6")'><input type="button" class="button pink" value="-" onclick='v("-")'></p>
		<p><input type="button" class="button black" value="1" onclick='v("1")'><input type="button" class="button black" value="2" onclick='v("2")'><input type="button" class="button black" value="3" onclick='v("3")'><input type="button" class="button pink" value="+" onclick='v("+")'></p>
		<p><input type="button" class="button black" value="0" onclick='v("0")'><input type="button" class="button black" value="." onclick='v(".")'><input type="button" class="button black" value="C" onclick='c("")'><input type="button" class="button orange" value="=" onclick='e()'></p>
	</div>
</div>
<div id="logDiv">
<h4>Log<span class="fRight"><input type="button" onclick="clearLog()" value="Clear"></span></h4>
<table id="logInner">

</table>
</div>
</body>
</html>