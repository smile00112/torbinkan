<?php
/*
#################################################################################
#  Module TOTAL IMPORT PRO for Opencart 2.0 From HostJars opencart.hostjars.com #
#################################################################################
*/
?>

<?php echo $header; ?><?php echo $menu; ?>
<div id="content">

<div class="page-header">
<div class="container-fluid">
    <div class="pull-right">
        <a type="button" data-toggle="tooltip" title="Download Log" class='btn btn-primary download-log'><i class="fa fa-download"></i></a>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="Home" class="btn btn-primary"><i class="fa fa-home"></i></a>
    </div>
    <h1><?php echo $heading_title; ?></h1>
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-question-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>

        <div class="panel panel-default">
        <div class="panel-body">
            <ul id="tabs" class="nav nav-tabs">
                <?php $counter = count($log);
                for($i = 0; $i < $counter; $i++) {  ?>
                    <li class="<?php if($i === 0) {echo 'active';} ?>" >
                    <a href="#log<?php echo $i; ?>" data-toggle="tab"  aria-controls="#log['<?php echo $i; ?>']" role="tab"><?php echo $log[$i]['tab_name'];?></a></li>
                <?php } ?>
            </ul>
            <div class="tab-content">
                <?php for($x = 0; $x < $i; $x++) {   ?>
                    <div class="tab-pane  <?php if($x === 0) {echo 'active';} ?>" id="log<?php echo $x; ?>" > <textarea wrap="off" rows="15" readonly="readonly" class="form-control"><?php echo $log[$x]['content']; ?></textarea></div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    $('.btn.btn-primary.download-log').on('click',function() {
        window.location= "<?php echo $download_log; ?>" + '&selected_id=' +  $('.tab-pane.active').attr('id');
});


</script>
<?php echo $footer; ?>