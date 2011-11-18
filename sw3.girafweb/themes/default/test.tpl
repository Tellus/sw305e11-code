<html>
<head>
<link rel="stylesheet" type="text/css" href="themes/default/css/girafbase.css" />
</head>
<body>
<div>Function call test.</div>
${FUNC|CLASS:METHOD}
${VREF|GET:message}
<div>Variable declaration.</div>
${VDEC|NAME:VALUE}
<div>Advanced Variable declaration.</div>
<div>Variable reference:<span>${VREF|NAME}</span></div>
${VDEC|REFERENCED_NAME:${VREF|NAME}}
<div>Loop test.</div>
${VDEC|numbers:${FUNC|util:getArrayOfIntegers,1,5,1}}
<div>Loop with variable reference.</div>
${VDEC|list:${VREF|numbers}}
${LOOP|list}
<div>Woooo!</div>
${ENDLOOP}
<div>Conditional</div>
${WHEN|false==false}
	<div>This is visible if true</div>
${ENDWHEN}
</body>
</html>
