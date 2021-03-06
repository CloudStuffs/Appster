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
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-74931565-1', 'auto');
  ga('send', 'pageview');

</script>
<script type="text/javascript">
(function (window) {
  window.location.href = 'http://dinchakapps.com/<?php echo $item["url"];?>';
}(window));
</script>
</body>

</html>