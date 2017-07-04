<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title>提示信息</title>
<style type="text/css">
*{ padding:0; margin:0; font-size:12px}
body{ background:#f1f1f1;}
a:link,a:visited{text-decoration:none;color:#666}
a:hover,a:active{color:#333;text-decoration: underline}
.showMsg{ zoom:1; width:450px; padding-bottom:10px; position:absolute;top:44%;left:50%;margin:-87px 0 0 -225px; background:#FFFFFF;box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);}
.showMsg h5{ height:25px; line-height:26px;*line-height:28px; overflow:hidden; font-size:24px; text-align:center; padding-top:30px; font-family:微软雅黑,'宋体'; color:#01A0F4;}
.showMsg .content{ padding:20px 10px 30px 10px; font-size:16px; font-family:微软雅黑,'宋体';}
.showMsg .bottom{ margin: 0 20px 1px 20px;line-height:40px; *line-height:40px; height:40px; text-align:center; border-top:1px solid #EDEDED;}
.showMsg .ok,.showMsg .guery{}
.showMsg .guery{background-position: left -460px;}
</style>
</head>
<body>
<div class="showMsg" style="text-align:center">
	<h5>操作提示</h5>
    <div class="content guery" style="color: red;display:inline-block;display:-moz-inline-stack;zoom:1;*display:inline;max-width:330px"><?php echo $result['msg'];?></div>
    <div class="bottom">
    <?php if($result['url']=='admin_parent'){?>
     <a href="javascript:admin_close_dialog();">如果您的浏览器没有自动跳转，请点击这里 </a>
	  <?php }elseif($result['url']=='check_priv'){?>
  <a href="javascript:history.back(-1);">如果您的浏览器没有自动跳转，请点击这里 </a>
    <?php }else{?>
        <a href="<?php echo $result['url'];?>">如果您的浏览器没有自动跳转，请点击这里 </a>
   <?php }?>
        </div>
</div>
 <?php if($result['url']=='admin_parent'){?>
 <script language="javascript">
setTimeout("admin_close_dialog();",<?php echo $result['wait'];?>);
function admin_close_dialog(){
parent.location.reload();
window.location.href="<?php echo $result['loginurl'];?>";
}
</script>
 <?php }elseif($result['url']=='check_priv'){?>
 <script language="javascript">
function redirect(href){
 location.href = href;
}
setTimeout("redirect('javascript:history.back(-1);');",<?php echo $result['wait'];?>);
</script>
<?php }else{?>
     <script language="javascript">
 setTimeout("redirect('<?php echo $result['url'];?>');",<?php echo $result['wait'];?>);
  function redirect(url) {
		location.href = url;
	}
</script>
   <?php }?>

</body>
</html>