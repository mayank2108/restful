<?php
session_start();
if(isset($_POST['Username']))
{
    require(dirname(__FILE__).'/server/remote/lib/db.php');
    $dbObj = new myDB();
    if(!$dbObj->doLogin(htmlentities($_POST['Username']), htmlentities($_POST['Password'])))
    {
        //echo "<script>alert('Wrong Username or Password!');</script>";
    }
    //echo "<script>document.location='index.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<?php
if(isset($_SESSION['uid']))
{
    ?>
    <head>
        <meta charset="UTF-8"/>
        <title>Client :: Rest Proxy</title>
        <link rel="stylesheet" href="http://cdn.sencha.io/try/extjs/4.0.7/resources/css/ext-all-gray.css" />
        <link rel="stylesheet" href="resources/css/app.css" />
        <script src="client/ext/ext-all.js"></script>
        <script src="client/ext/ext-all-sandbox.js"></script>
        <script src="client/app.js"></script>
        <script src="client/ext/SearchField.js"></script>
    </head>
    <body>
    </body>
<?php
}
else
{
    ?>
    <head>
        <title>Client :: Rest Proxy</title>
    </head>
    <body>
    <center>
        <form action="" method="post">
            <table align="center" style="margin-top:25%;">
                <tr align="center">
                    <td><input type="text" required=true placeholder="Username" name="Username"></td>
                </tr>
                <tr align="center">
                    <td><input type="password" required=true placeholder="Password" name="Password"></td>
                </tr>
                <tr align="center">
                    <td><input type="submit" value="login"></td>
                </tr>
            </table>
        </form>
    </center>
    </body>
<?php
}
?>
</html>
