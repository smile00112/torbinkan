<div class=" subs">
<div class="box-heading">Получить оптовый прайс-лист</div>
	<div class="box box-content">
		<div id="frm_subscribe3<?php echo $module; ?>">
			<form name="subscribe3" id="subscribe3<?php echo $module; ?>" >
			
							<input type="text" placeholder="<?php echo $entry_email; ?>" value="" name="pricesubscribe_email" id="pricesubscribe_email">		
								<div style="display:none">
						<td>
							<input type="text" placeholder="<?php echo $entry_name; ?>" value="" name="pricesubscribe_name" id="pricesubscribe_name" style="width:95%">
						</td>
					</div>
				  
							<a class="button" style="padding: 9px 12px 11px 12px;" onclick="email_subscribe3<?php echo $module; ?>()"><span>Получить</span></a>
							
				
					
						<div align="center" id="subscribe_result"></div>
					
			
			</form>
		</div>
	</div>

	<script language="javascript">	
		function email_subscribe3<?php echo $module; ?>(){
			$.ajax({
				type: 'post',
				url: 'index.php?route=module/pricesubscribe/subscribe',
				dataType: 'html',
				data:$("#subscribe3<?php echo $module; ?>").serialize(),
				success: function (html) {
					eval(html);
				}
			}); 
		}
		
		function email_unsubscribe(){
			$.ajax({
				type: 'post',
				url: 'index.php?route=module/pricesubscribe/unsubscribe',
				dataType: 'html',
				data:$("#subscribe3<?php echo $module; ?>").serialize(),
				success: function (html) {
					eval(html);
				}
			}); 
		}
	</script>
</div>