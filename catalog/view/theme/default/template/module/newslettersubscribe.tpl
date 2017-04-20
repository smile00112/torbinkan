<div class=" subs">
<div class="box-heading">Хотите быть в курсе последних новостей? Подписывайтесь!</div>
	<div class="box box-content">
		<div id="frm_subscribe<?php echo $module; ?>">
			<form name="subscribe" id="subscribe<?php echo $module; ?>" >
			
							<input type="text" placeholder="<?php echo $entry_email; ?>" value="" name="subscribe_email" id="subscribe_email">		
								<div style="display:none">
						<td>
							<input type="text" placeholder="<?php echo $entry_name; ?>" value="" name="subscribe_name" id="subscribe_name" style="width:95%">
						</td>
					</div>
				  
							<a class="btn_buy ppp" onclick="email_subscribe<?php echo $module; ?>()"><span><?php echo $entry_button; ?></span></a>
							
				
					
						<div align="center" id="subscribe_result"></div>
					
			
			</form>
		</div>
	</div>

	<script language="javascript">	
		function email_subscribe<?php echo $module; ?>(){
			$.ajax({
				type: 'post',
				url: 'index.php?route=module/newslettersubscribe/subscribe',
				dataType: 'html',
				data:$("#subscribe<?php echo $module; ?>").serialize(),
				success: function (html) {
					eval(html);
				}
			}); 
		}
		
		function email_unsubscribe(){
			$.ajax({
				type: 'post',
				url: 'index.php?route=module/newslettersubscribe/unsubscribe',
				dataType: 'html',
				data:$("#subscribe<?php echo $module; ?>").serialize(),
				success: function (html) {
					eval(html);
				}
			}); 
		}
	</script>
</div>