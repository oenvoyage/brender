		<script>
		$(function() {
			var username = $('input#username'),
				password = $('input#password'),
				do_login = $('#do_login');
		
			
			$( "#dialog-form" ).dialog({
				autoOpen: true,
				height: 200,
				width: 350,
				modal: true,
				buttons: {
					"Login": function() { 							
							
							$.post("ajax/login.php", {username: username.val(), password: password.val(), do_login: do_login.val() }, function(data) {
								var obj = jQuery.parseJSON(data);
								//alert(data);
								if(obj.status == true) {
									$("#dialog-form").dialog("close" );
									window.location= 'index.php';
								} else {
									alert(obj.msg);
								}
							}, "Json");				
			    			return false;
    			
			    				    			
			    			/*
			    			$.ajax({
							  type: 'POST',
							  url: 'tpl/temp.php',
							  data: {username: username, password: password, do_login: do_login} ,
							  success: function(data) {var obj = jQuery.parseJSON(data);
								alert(obj.status);
							  window.location='index.php?view=login'},
							  error: function(data) {alert('Epic Fail');},
							  dataType: 'Json'
							});					
							return false;
							*/						
					}
				},
				close: function() {
					//allFields.val( "" ).removeClass( "ui-state-error" );
				}
			});
	
		});
		</script>

		<div id="dialog-form" title="// please login">	 
				<fieldset class="login">
					<div class="col_1">
						<label for="username">Username</label>
						<label for="password">Password</label>
			            
					</div>
					<div class="col_2">
						<input id="username" type="text" name="username" value="brender" />	
						<input id="password" type="password" name="password" value="brender" />
					</div>
					<div class="clear"></div>
					<input id="do_login" type="hidden" name="do_login" value="true" />
				</fieldset>
		</div>
		
