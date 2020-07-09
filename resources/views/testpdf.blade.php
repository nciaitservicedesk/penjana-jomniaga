<html>

<head>
  <title>Javascript test</title>
</head>
<body>
<p>this is first page</p>
<p>i dont want you in here!.</p>
<label id="testInsert"></label>
<div style="page-break-after: always;"></div>

<p>this here should be 2nd page </p>
<p>blablablablblblablblabllab</p>

<script type="text/javascript">
  document.getElementById('testInsert').innerHTML  = "text set by jquery!";

</script>
</body>
</html>