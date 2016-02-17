<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta property="fb:app_id" content="179747022387337">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="article">
    <meta property="og:title" content="<?php echo $item['title'];?>" />
    <meta property="og:description" content="<?php echo $item['description'];?>">
    <meta property="og:url" content="<?php echo URL;?>">
    <meta property="og:image" content="<?php echo SITE;?>image.php?file=<?php echo $item['image'];?>">
    <meta property="og:site_name" content="DinchakApps">
    <meta property="article:section" content="Pictures" />
    
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo $item['title'];?>">
    <meta name="twitter:description" content="<?php echo $item['description'];?>">
    <meta name="twitter:url" content="<?php echo SITE;?>">
</head>

<body>
<script type="text/javascript">
redirect();
function redirect () {
    window.location.href = 'http://dinchakapps.com/<?php echo $item["url"];?>';
}
</script>
</body>

</html>