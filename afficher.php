<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Simple Calendar Date Picker Example</title>
    <link href="https://www.cssscript.com/wp-includes/css/sticky.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/date-picker.css" />
    <style>
        body { background: #fafafa; }
        .wrapper { margin: 150px auto; }
        h1 { text-align: center; }
    </style>
</head>
<body>
<div class="wrapper">
    <h1>Simple Calendar Date Picker Example</h1>
    <div class="container-calendar">
        <div class="button-container-calendar">
            <button id="previous">&#8249;</button>
            <button id="next">&#8250;</button>
            <h3 id="monthHeader"></h3>
            <p id="yearHeader"></p>
        </div>

        <table class="table-calendar" id="calendar">
            <thead id="thead-month"></thead>
            <tbody id="calendar-body"></tbody>
        </table>

        <div class="footer-container-calendar">
            <label for="month">Jump To: </label>
            <select id="month">
                <option value=0>Jan</option>
                <option value=1>Feb</option>
                <option value=2>Mar</option>
                <option value=3>Apr</option>
                <option value=4>May</option>
                <option value=5>Jun</option>
                <option value=6>Jul</option>
                <option value=7>Aug</option>
                <option value=8>Sep</option>
                <option value=9>Oct</option>
                <option value=10>Nov</option>
                <option value=11>Dec</option>
            </select>
            <select id="year"></select>
        </div>

        <p id="date-picked"></p>
    </div>
</div>
<script src="js/date-picker.js"></script>
</html>



<?php /**
 * <script type="text/javascript">
 * jQuery(function ($){
 * $('.months').hide();
 * $('.months:first').show();
 * $('.months a:first').addClass('active');
 * var current = 1;
 * $('.months a').click(function () {
 * var month = $(this).attr('id').replace('linkMonth'.'');
 * alert(month);
 * return false;
 * })
 * });
 * </script>
 * </head>
 * <body>
 * <?php
 * require("calendrier.php");
 * $date = new Date();
 * $year = date('Y');
 * $dates = $date->getAll($year);
 * ?>
 * <div class="perriods">
 * <div class="year"><?php echo $year; ?></div>
 * <div class="months">
 * <ul>
 * <?php foreach ($date->months as $id => $m):
 * echo '<li><a id="linkMonth';
 * echo $id + 1 . '"></a>' . utf8_encode(substr(utf8_decode($m), 0, 3)) . '</li>';
 * endforeach; ?>
 * </ul>
 * </div>
 * <?php $dates = current($dates);
 * foreach ($dates as $m => $days) {
 * echo '<p>'.$m[1].'</p>';
 * echo '<div class="month" id="month">' . $m . '</div>'; ?>
 * <table>
 * <thead>
 * <tr>
 * <?php foreach ($date->days as $d) {
 * echo '<th>' . substr($d, 0, 3) . '</th>';
 * }
 * ?>
 * </tr>
 * </thead>
 * <tbody>
 * <tr>
 * <?php
 * foreach ($days as $d => $w) {
 * if ($d == 1 and $w != 1) {
 * echo '<td colspan="';
 * echo $w - 1 . '"></td>';
 * }
 * echo '<td>' . substr($d, 0, 3) . '</td>';
 * if ($w == 7) echo '</tr><tr>';
 * }
 * $end = end($days);
 * if ($end != 7) {
 * echo '<td colspan="';
 * echo 7 - $end . '"></td>';
 * }
 * ?>
 * </tr>
 * </tbody>
 * </table>
 * <?php
 * }
 * ?>
 * <pre><?php print_r($dates); ?></pre>
 * </body>
 * </html> **/ ?>