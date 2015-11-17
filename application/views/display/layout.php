<?
// Template Name: DEFAULT
?>

<!doctype html>
<html>
<head>
<? $this->load->view('display/view-head'); ?>  
</head>

<body>
<div class="page">

	<? $this->load->view('display/view-header'); ?>
    
    <div class="content row">
        <div class="wrapper">
        
        <? $fdcms->html_block("Main Content"); ?>
        
        </div>
    </div>
    
	<? $this->load->view('display/view-footer'); ?>

</div>
</body>
</html>
