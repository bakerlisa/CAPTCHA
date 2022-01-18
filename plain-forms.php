<?php if(!function_exists("form_script_styles")) {
    function form_script_styles(){ ?>
		<script>
			// gather all forms
			let allForms = document.querySelectorAll('.form-container');
			for(forms=0;forms < allForms.length;forms++ ){  
			    let formNum = allForms[forms].getAttribute('data-formNum');
			    let form = allForms[forms].getAttribute('data-form');
			    let ajaxPage = allForms[forms].getAttribute('data-ajaxPage');

			    fetch("<?= home_url(); ?>/" + ajaxPage)
			    .then(response => response.text())
			    .then(data => {
			        document.querySelector("."+form + " .form-container").innerHTML = data;
			        document.querySelector("."+form + " form").setAttribute("action", "<?= home_url(); ?>/thank-you");
			        let newInput = document.createElement("input");
			        newInput.setAttribute("class", "over-submit over-submit-"+formNum);
			        newInput.setAttribute('data-form',formNum);
			        newInput.setAttribute("onclick", "checkLabels("+formNum+")");
			        document.querySelector("."+form+" .gform_footer.top_label").appendChild(newInput);

		            // create message field
		        	var messageInfo = document.createElement("div");
		        	messageInfo.className = "form-message";
		        	document.querySelector('#gform_'+formNum).prepend(messageInfo);
			    });
			}

			function checkLabels(formNum){
			    let alerted = 0;
			    document.querySelectorAll('#gform_'+formNum+' input[aria-required="true"], #gform_'+formNum+' textarea[aria-required="true"]').forEach(item=>{
			            if(item.value == ""){
			                alerted++;
			                item.classList.add('error');
			            }else{
			            	if(item.classList.contains('error')){
			            		item.classList.remove('error');	
			            	}
			            }
			});
			    
			//checks email address to be the right format
                document.querySelectorAll('#gform_'+formNum+' .ginput_container_email input').forEach(items=>{
                    var inputValue = items.value;
                    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(inputValue)){
                        if(items.classList.contains('error')){
                            items.classList.remove('error'); 
                        }  
                    }else{
                        items.classList.add('error');
                        items.placeholder = "The format for an email name@extension.com";
                    }
                });

                //checks phone numbers to be 10 digits
                document.querySelectorAll('#gform_'+formNum+' .ginput_container_phone input').forEach(itemed=>{
                    var inputPhone = itemed.value;

                    if(/^[+]*[(]{0,1}[0-9]{1,3}[)]{0,1}[-\s\./0-9]*$/g.test(inputPhone) && inputPhone.length == 10){
                        if(itemed.classList.contains('error')){
                            itemed.classList.remove('error'); 
                        }  
                    }else{
                        itemed.classList.add('error');
                        itemed.value = "";
                        itemed.placeholder = "The format for the phone number are 10 plain digits";
                    }
                });

			    if(alerted > 0){
			    	var message = "Please fill out all required fields. \n Required fields are marked with an asterisk ( * )";
			    	var errorMessage = document.querySelector("#gform_"+formNum + " .form-message");
			    	errorMessage.inerHTML = message;
			    	errorMessage.classList.add('active');
			    	scrollLink(formNum);

			    	setTimeout(function(){
			    		errorMessage.classList.remove('active');
			    	},5000);

			    	errorMessage.classList.add('error');
			    	errorMessage.innerHTML = message;
			    }else{
			        document.querySelector(".over-submit").remove();
			        document.querySelector("#gform_submit_button_"+formNum).click();
			    }
			}

			function scrollLink(formNum){
				var scro = 'gform_'+formNum;
				var headerOffset = 150;  
				
				var div = document.getElementById(scro);
				var divOffset = offset(div);
				var offsetTotal = parseInt(divOffset.top) - parseInt(headerOffset);

			    window.scrollTo({
			         top: offsetTotal,
			         behavior: "smooth"
			    });
			}	

			function offset(el) {
			    var rect = el.getBoundingClientRect(),
			    scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
			    scrollTop = window.pageYOffset || document.documentElement.scrollTop;
			    return { top: rect.top + scrollTop, left: rect.left + scrollLeft }
			}	
		</script>

		<style>
			input.error, textarea.error, select.error{border: solid 1px #ff0000 !important; background-color: rgba(255, 199, 199, 1) !important; }
			.form-message{max-height: 0; overflow: hidden; font-weight: 900; color: red; line-height: normal;}
			.form-message.active{padding-bottom: 15px;  max-height: 85px; transition:max-height .25s ease-in;}
			/*recap message*/
			.form-message.active.recap{position: fixed; top: 50%; transform: translateY(-50%); z-index: 1000000; background-color: red; color:var(--white); font-weight: 900; line-height: normal; max-width: 600px; width: 100%; margin: 0 auto; left: 0; right: 0; text-align: center; padding: 25px;}
		</style>
<?php } add_action('wp_footer', 'form_script_styles', 100); } ?>
