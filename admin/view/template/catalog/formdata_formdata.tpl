<?php echo $header; ?>

<div id="content">

  <div class="breadcrumb">

    <?php foreach ($breadcrumbs as $breadcrumb) { ?>

    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>

    <?php } ?>

  </div>

  <?php if ($error_warning) { ?>

  <div class="warning"><?php echo $error_warning; ?></div>

  <?php } ?>

  <?php if ($success) { ?>

  <div class="success"><?php echo $success; ?></div>

  <?php } ?>

  <div class="box">

    <div class="heading">

      <h1><img src="view/image/shipping.png" alt="" /> <?php echo $heading_title; ?></h1>

      <div class="buttons"><a onclick="location = '<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a><a onclick="$('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>

    </div>

    <div class="content">
        
        <table class="list">

          <thead>

            <tr>

              <td class="left"><?php echo $column_date; ?></td>

              <td class="left"><?php echo $column_form; ?></td>

            </tr>

          </thead>

          <tbody>

            <?php if ($form_data) { ?>
            <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
                <input type="hidden" name="selected[]" value="<?php echo $form_data['form_data_id'];?>" />
                <input type="hidden" name="form_id" value="<?php echo $form_id; ?>" />
            </form>
            <tr>

              <td class="left"><?php echo $form_data['date_added']; ?></td>

              <td class="left"><a href="<?php echo $form; ?>" target="_blank" title="<?php echo $form_name['name']; ?>"><?php echo $form_name['name']; ?></a></td>

            </tr>

            <tr>
                <td colspan="2">
                <?php echo $form_data['form_data']; ?>
                </td>
            </tr>

            <?php } else { ?>

            <tr>

              <td class="center" colspan="4"><?php echo $text_no_results; ?></td>

            </tr>

            <?php } ?>

          </tbody>

        </table>

      </div>

  </div>

</div>

<?php echo $footer; ?>