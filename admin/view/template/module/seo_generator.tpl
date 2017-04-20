<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
    <div class="container-fluid">
        <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
        </ul>
    </div>
    <div class="container-fluid">
        
        <div id="message"<?php if (!$success && !$error){ ?> style="display: none;"<?php } ?>>
            <?php foreach($success as $item) { ?>
            <div class="alert alert-success"><i class="fa fa-check-circle"></i><?php echo $item; ?> <button type="button" class="close" data-dismiss="alert">×</button></div>
            <?php } ?>
            <?php foreach($error as $item) { ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i><?php echo $item; ?> <button type="button" class="close" data-dismiss="alert">×</button></div>
            <?php } ?>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
                <div class="pull-right btn-group" role="group">
                    <button form="form" data-toggle="tooltip" title="<?php echo $button_generate; ?>" class="btn btn-primary"><i class="fa fa-cogs"></i> <?php echo $button_generate; ?></button>
                    <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a>
                </div>
                <div style="clear: both;"></div>
            </div>
            <div class="panel-body">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab-general" data-toggle="tab"><i class="fa fa-cogs"></i> <?php echo $tab_general; ?></a></li>
                    <li><a href="#tab-addist" data-toggle="tab"><i class="fa fa-wrench"></i> <?php echo $tab_addist; ?></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-general">
                        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" onsubmit="return confirm('<?php echo $text_confirm; ?>')"class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="input-save"><?php echo $entry_save; ?></label>
                                <div class="col-sm-10">
                                    <select name="seo_generator[save]" id="input-save" class="form-control radio">
                                        <option value="0"><?php echo $text_no; ?></option>
                                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="input-translit"><?php echo $entry_translit; ?></label>
                                <div class="col-sm-10">
                                    <select name="seo_generator[translit]" id="input-translit" class="form-controll radio">
                                        <option value="0"><?php echo $text_no; ?></option>
                                        <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="input-product_template"><?php echo $entry_product_template; ?></label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"><input type="checkbox" name="seo_generator[product][status]" value="1" /></span>
                                        <input type="text" name="seo_generator[product][template]" id="input-product_template" value="<?php echo $product_template; ?>" class="form-control question" />
                                        <label class="input-group-addon"><input type="checkbox" name="seo_generator[product][overwrite]" value="1" /> <?php echo $text_overwrite; ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-10">
                                    <div class="well">
                                        <span class="kbd"><kbd>[id]</kbd> - <?php echo $help_product_id; ?>,</span>
                                        <span class="kbd"><kbd>[name]</kbd> - <?php echo $help_product_name; ?>,</span>
                                        <span class="kbd"><kbd>[model]</kbd> - <?php echo $help_product_model; ?>,</span>
                                        <span class="kbd"><kbd>[category_id]</kbd> - <?php echo $help_product_category_id; ?>,</span>
                                        <span class="kbd"><kbd>[category_name]</kbd> - <?php echo $help_product_category_name; ?>,</span>
                                        <span class="kbd"><kbd>[manufacturer_id]</kbd> - <?php echo $help_product_manufacturer_id; ?>,</span>
                                        <span class="kbd"><kbd>[manufacturer_name]</kbd> - <?php echo $help_product_manufacturer_name; ?>.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="input-category_template"><?php echo $entry_category_template; ?></label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"><input type="checkbox" name="seo_generator[category][status]" value="1" /></span>
                                        <input type="text" name="seo_generator[category][template]" id="input-category_template" value="<?php echo $category_template; ?>" class="form-control" />
                                        <label class="input-group-addon"><input type="checkbox" name="seo_generator[category][overwrite]" value="1" /> <?php echo $text_overwrite; ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-10">
                                    <div class="well">
                                        <span class="kbd"><kbd>[id]</kbd> - <?php echo $help_category_id; ?>,</span>
                                        <span class="kbd"><kbd>[name]</kbd> - <?php echo $help_category_name; ?>.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="input-manufacturer_template"><?php echo $entry_manufacturer_template; ?></label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"><input type="checkbox" name="seo_generator[manufacturer][status]" value="1" /></span>
                                        <input type="text" name="seo_generator[manufacturer][template]" id="input-manufacturer_template" value="<?php echo $manufacturer_template; ?>" class="form-control" />
                                        <label class="input-group-addon"><input type="checkbox" name="seo_generator[manufacturer][overwrite]" value="1" /> <?php echo $text_overwrite; ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-10">
                                    <div class="well">
                                        <span class="kbd"><kbd>[id]</kbd> - <?php echo $help_manufacturer_id; ?>,</span>
                                        <span class="kbd"><kbd>[name]</kbd> - <?php echo $help_manufacturer_name; ?>.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="input-information_template"><?php echo $entry_information_template; ?></label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"><input type="checkbox" name="seo_generator[information][status]" value="1" /></span>
                                        <input type="text" name="seo_generator[information][template]" id="input-information_template" value="<?php echo $information_template; ?>" class="form-control" />
                                        <label class="input-group-addon"><input type="checkbox" name="seo_generator[information][overwrite]" value="1" /> <?php echo $text_overwrite; ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-10">
                                    <div class="well">
                                        <span class="kbd"><kbd>[id]</kbd> - <?php echo $help_information_id; ?>,</span>
                                        <span class="kbd"><kbd>[title]</kbd> - <?php echo $help_information_title; ?>.</span>
                                    </div>
                                </div>
                            </div>
                            <?php if (OC_VERSION != '2.0.x') { ?>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="input-news_template"><?php echo $entry_news_template; ?></label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"><input type="checkbox" name="seo_generator[news][status]" value="1" /></span>
                                        <input type="text" name="seo_generator[news][template]" id="input-news_template" value="<?php echo $news_template; ?>" class="form-control" />
                                        <label class="input-group-addon"><input type="checkbox" name="seo_generator[news][overwrite]" value="1" /> <?php echo $text_overwrite; ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-10">
                                    <div class="well">
                                        <span class="kbd"><kbd>[id]</kbd> - <?php echo $help_news_id; ?>,</span>
                                        <span class="kbd"><kbd>[name]</kbd> - <?php echo $help_news_name; ?>,</span>
                                        <span class="kbd"><kbd>[category_id]</kbd> - <?php echo $help_news_category_id; ?>,</span>
                                        <span class="kbd"><kbd>[category_name]</kbd> - <?php echo $help_news_category_name; ?>,</span>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </form>
                    </div>
                    <div class="tab-pane" id="tab-addist"><form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal"><?php echo $addist_tab; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>