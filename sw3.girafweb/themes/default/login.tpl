<html>
<head>
<link rel="stylesheet" type="text/css" href="themes/default/css/girafbase.css" />
</head>
<body>
    ${WHEN|("${VREF|POST:username}"!=null && 1 == "1") || "Myass" == DICKPUMP}
    <div class="centerbox">
    <form method="POST" action="index.php?page=login">
    <table>
        <tr>
            <td>Username:</td>
            <td><input type="text" name="username" /></td>
        </tr>
        <tr>
            <td>Password:</td>
            <td><input type="password" name="password" /></td>
        </tr>
        <tr>
            <td><input type="submit" value="Go" /></td>
        </tr>
    </table>
    </form>
    </div>
    ${ELSE}
		<div>I'm not really sure what's going on.</div>
    ${ENDWHEN}
</body>
</html>
