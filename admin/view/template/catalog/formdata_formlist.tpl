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

    </div>

    <div class="content">

      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">

        <table class="list">

          <thead>

            <tr>

              <td class="left"><?php if ($sort == 'name') { ?>

                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>

                <?php } else { ?>

                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>

                <?php } ?></td>
                
              <td class="right"><?php echo $column_total; ?></td>  

              <td class="right"><?php if ($sort == 'sort_order') { ?>

                <a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>

                <?php } else { ?>

                <a href="<?php echo $sort_sort_order; ?>"><?php echo $column_status; ?></a>

                <?php } ?></td>

              <td class="right"><?php echo $column_action; ?></td>

            </tr>

          </thead>

          <tbody>

            <?php if ($forms) { ?>

            <?php foreach ($forms as $form) { ?>

            <tr>

              <td class="left"><?php echo $form['name']; ?></td>
              
              <td class="right"><?php echo $form['total']; ?></td>

              <td class="right"><?php echo $form['status']; ?></td>

              <td class="right"><?php foreach ($form['action'] as $action) { ?>

                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]

                <?php } ?></td>

            </tr>

            <?php } ?>

            <?php } else { ?>

            <tr>

              <td class="center" colspan="4"><?php echo $text_no_results; ?></td>

            </tr>

            <?php } ?>

          </tbody>

        </table>

      </form>

      <div class="pagination"><?php echo $pagination; ?></div>

    </div>

  </div>

</div>

<?php echo $footer; ?>