<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Offline</title>
    <meta name="viewport" content="width=device-width, initial-scale=1,  maximum-scale=1.0, user-scalable=false">
    <style>
        html, body{
            height: 100%;
            min-height: 100%;
        }
        body{
            padding: 30px;
            /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#606c88+0,3f4c6b+100;Grey+3D+%232 */
            background: #606c88; /* Old browsers */
            background: -moz-linear-gradient(top,  #606c88 0%, #3f4c6b 100%); /* FF3.6-15 */
            background: -webkit-linear-gradient(top,  #606c88 0%,#3f4c6b 100%); /* Chrome10-25,Safari5.1-6 */
            background: linear-gradient(to bottom,  #606c88 0%,#3f4c6b 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#606c88', endColorstr='#3f4c6b',GradientType=0 ); /* IE6-9 */

        }
        h1{margin-top: 40px;}
        h1,p {
            color:#fff;
            text-align: center;
            font-family: Arial, sans-serif;
        }

        a{
            color: #f7f7f7;
        }
    </style>
</head>
<body>
<h1>Sorry, you appear to be offline!</h1>
<p>That means we can only display content you've seen when you had an internet connection :(</p>
<p><a href="/" onClick="window.history.back()">Go back to where you came from</a> or <a href="/">go back to front page</a></p>
</body>
</html>