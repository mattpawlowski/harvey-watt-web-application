<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FDCMS</title>

<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="/css/app/admin.css" />
<? echo $styles; ?>

<!-- jQuery and System Scripts -->
<script type="text/javascript" src="/js/app/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="/js/app/admin.js"></script>

<!-- Syntax Highlighter -->
<link rel="stylesheet" href="/plugins/highlight/styles/default.css">
<script type="text/javascript" src="/plugins/highlight/highlight.pack.js"></script>

<!-- Controller Scripts -->
<? echo $scripts; ?>

</head>

<body>
    <div class="fdcms-screen"></div>
    <div class="fdcms-alert-container">
        <div class="fdcms-alert"><div class="fdcms-alert-title"></div><div class="fdcms-alert-content"></div></div>
    </div>

	<div class="header row">
    	<div class="wrapper">
        	<div class="fdcms-logo left"><a href="/admin"><img src="/images/app/core/fdcms-logo.png" /></a></div>
            <div class="fdcms-status right"><? echo $userStatus; ?></div>
            <div class="clear"></div>
        </div>    
    </div>

	<div class="content row noSelect">
        <? if(isset($adminNav)) { ?>
    	<div class="fdcms-sidebar left">
			<? echo $adminNav; ?>
        </div>
        <? } ?>
        
        <div class="fdcms-toolbox right">
            <? if(isset($adminActions) && ($adminActions != '')) { ?>
            <ul class="actions">
            <? echo $adminActions; ?>
            </ul>
            <? } ?>
            
            <? if(isset($adminToolbox) && ($adminToolbox != '')) { ?>
            <ul class="toolbox">
            <? echo $adminToolbox; ?>
            </ul>
            <? } ?>
        </div>
        
        <div class="fdcms-content">
        
            <div class=""><h1><? echo $adminTitle; ?></h1></div>
            <? if($this->session->flashdata('message')) { ?>
            <div class="<? echo $this->session->flashdata('messageClass'); ?>"><? echo $this->session->flashdata('message'); ?></div>
            <? } ?>
        
			<? if(isset($adminContent)) { echo $adminContent; } ?>
            
        </div>
        
        <div class="clear"></div>
	</div>

	<div class="footer row">
    	<div class="footer-wrapper">
        &copy; Copyright <? echo date('Y'); ?> <a href="http://www.firmdesign.com" target="_blank">FirmDesign</a> | <i><a href="jabvascript: void(0);" class="help" data-subject="html5Browser">HTML5 Compatible Browser</a> Required</i>
        </div>
    </div>

</body>
</html>