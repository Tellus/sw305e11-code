<html>
<head>
<link rel="stylesheet" type="text/css" href="themes/default/css/girafbase.css" />
</head>
<body>
<div>Function call test.</div>
<div>${FUNC|CLASS:METHOD}</div>
<div>${VREF|GET:message}</div>
<div>Variable declaration.</div>
<div>${VDEC|NAME:VALUE}</div>
<div>Advanced Variable declaration.</div>
<div>Variable reference:<span>${VREF|NAME}</span></div>
<div>${VDEC|NAME:${FUNC|CLASS:METHOD}}</div>
<div>Loop test.</div>
<div>${VDEC|numbers:${FUNC|util:docount,5}}</div>
<div>${LOOP|numbers:item}</div>
<div>${VREF|item}</div>
<div>${ENDLOOP}</div>
<div>Loop with variable reference.</div>
<div>${LOOP|${VREF|NAME}}</div>
<div>Woooo!
<div>${ENDLOOP}</div>
<div>Conditional</div>
<div>${WHEN|false==false}</div>
<div>This is visible if true</div>
<div>${ENDWHEN}</div>
</body>
</html>
