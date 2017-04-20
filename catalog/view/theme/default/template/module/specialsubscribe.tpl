<div class=" subs">
<div class="box-heading">Подписка на акции</div>
	<div class="box box-content">
		<div id="frm_subscribe2<?php echo $module; ?>">
			<form name="subscribe2" id="subscribe2<?php echo $module; ?>" >
			
							<input type="text" placeholder="<?php echo $entry_email; ?>" value="" name="specsubscribe_email" id="specsubscribe_email">		
								<div style="display:none">
						<td>
							<input type="text" placeholder="<?php echo $entry_name; ?>" value="" name="specsubscribe_name" id="specsubscribe_name" style="width:95%">
						</td>
					</div>
				  
							<a class="button" style="padding: 9px 12px 11px 12px;" onclick="email_subscribe2<?php echo $module; ?>()"><span><?php echo $entry_button; ?></span></a>
							
				
					
						<div align="center" id="subscribe_result"></div>
					
			
			</form>
		</div>
	</div>

	<script language="javascript">	
		function email_subscribe2<?php echo $module; ?>(){
			$.ajax({
				type: 'post',
				url: 'index.php?route=module/specialsubscribe/subscribe',
				dataType: 'html',
				data:$("#subscribe2<?php echo $module; ?>").serialize(),
				success: function (html) {
					eval(html);
				}
			}); 
		}
		
		function email_unsubscribe(){
			$.ajax({
				type: 'post',
				url: 'index.php?route=module/specialsubscribe/unsubscribe',
				dataType: 'html',
				data:$("#subscribe2<?php echo $module; ?>").serialize(),
				success: function (html) {
					eval(html);
				}
			}); 
		}
	</script>
</div>