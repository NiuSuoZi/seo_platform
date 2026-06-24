<!DOCTYPE html>
<html>
<head>
    <title>正在转向...</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="x-ua-compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" href="__PUBLIC__/statics/loading/waf_style.css">
</head>
<body>
<div class="box">
    <div class="logo">
        <img src="__PUBLIC__/statics/loading/waf_logo.gif" width="100">
    </div>
    <div class="tip">
        <small>
            <div class="ipinfo">
                <b id="cip"></b><span id="cname"></span>
            </div>
            <div>
                <?php if(empty($message)) : ?>
                <?php echo($error);?>
                <?php else:?>
                <?php echo($message);?>
                <?php endif;?>
            </div>
        </small>
    </div>
    <div class="progress">
        <div id="progress-bar" class="progress-bar progress-bar-success" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%">
        </div>
    </div>
</div>
<script>
    var url='{$jumpUrl}';
    function progress(p){document.getElementById("progress-bar").style.width=p+"%"}
    setTimeout(function(){progress("5");setTimeout(function(){progress("60");setTimeout(function(){progress("95");window.location.href=url;},600);},800);},300);
</script>
</body>
</html>