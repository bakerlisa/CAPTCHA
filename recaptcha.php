<?php if(!function_exists('recaptcha_styles')){
    function recaptcha_styles(){ ?>
		<script>
			//sets a global Session. If its more then var its hidden value
			var interval = 0;
			function myTimer(){interval++;}

			sessionStorage.setItem("blockPopup","0");
			
			var recpErr = parseInt(sessionStorage.getItem("recapthcaError"));
			var holdup = sessionStorage.getItem("recapthcaHoldup");
			
			if(recpErr){
				if(recpErr >= 5){
					holdThePhone();
					//holdPopUp();
				}
			}else{
				sessionStorage.setItem("recapthcaError", "0");
			}

			// Global word choice
			var recpRandomWords = ['advocate','counsel','lawyer','prosecuting','legislator','prosecutor','cocounsel','appellate','bankruptcy','deposition','equitable','fraudulent','jurisdiction','lawsuit','magistrate','redemption','sanction','settlement','testimony','transcript'];

			function findRanWrd(){
				// randow word function
				let piceWrd = Math.floor(Math.random() * recpRandomWords.length);
				return piceWrd;
			}

			function rndlttr(pickedWord){
				// letter to replace
				let letters = recpRandomWords[pickedWord];
				let charLetters = letters.split('');
				let numLetters = Math.floor(Math.random() * charLetters.length);
				let randomletter = charLetters[numLetters];
				return randomletter;
			}

			function randNumber(){
				let number = Math.floor(Math.random() * 10);
				return number;	
			}

			function randomSequence(){
				let sequence = "";
				let possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
				for (let f = 0; f < 5; f++){
				    sequence += possible.charAt(Math.floor(Math.random() * possible.length));
				}
				return sequence;
			}

			//create recaptcha button unless too many times has been tried
			if(holdup == 'holdup'){
				holdThePhone();
			}else{	
				//creates recaptcha button and functionality
				setTimeout(function(){
					let allFormsFooter = document.querySelectorAll('.gform_body');

					for(let v=0; v<allFormsFooter.length;v++){
						var dataID = allFormsFooter[v].parentElement.id;

						// creates notice
						var recpNotice = document.createElement("p");
							recpNotice.className = "notice-wrp";
							recpNotice.innerHTML = "<p class='notice'>**<strong>Must Pass</strong> Recaptcha <u>before</u> submitting form.</p>";
						allFormsFooter[v].appendChild(recpNotice);

						//creates button
						var recpDiv = document.createElement("div");
							recpDiv.innerHTML = 'Recaptcha';
							recpDiv.className = "recaptcha-button button";
							recpDiv.setAttribute('data-id', dataID);
							recpDiv.setAttribute("onclick", "buttonClick('"+dataID+"')");
						allFormsFooter[v].appendChild(recpDiv);

					}
				},3000);
			}

			function buttonClick(dataID){
				sessionStorage.setItem("recapthca", "");
				setInterval(myTimer, 1000);
				
				var boxexsists = document.querySelector('#' + dataID + ' .recaptcha-wrapper');

				if(boxexsists){
					recaptcaReload(dataID);
				}else{
					recaptca();
				}

				document.querySelector('#' + dataID +  ' .recaptcha-wrapper').classList.add('active');
			}

			// creates recaptcha
			function recaptca(){ 
				// recptacha
				let recptchas = document.querySelectorAll('.gform_fields');

				for(let c=0; c < recptchas.length;c++){
					//word picked
					let pickedWord =  findRanWrd();
					// letter to replace
					let removChar = rndlttr(pickedWord);
					let num = randNumber();
					// random sequence
					let randseq = randomSequence();
					//sets the ID to target specific fields
					let elementID = recptchas[c].parentElement.parentElement.id;

					// create the HTML elements, and popup window
					const recpDiv = document.createElement("div");
						recpDiv.className = "recaptcha-wrapper";
					let recaptchaContent = "<div><p class='exit' onclick=\"exit()\">x</p><p class='title'> *Recaptcha</p><p class='message'></p><p class='explain'>Follow <strong>all</strong> the steps before hitting submit</p><ul><li class='step1'>Type <em class='keyword'>" + recpRandomWords[pickedWord] + "</em> in the input below</li><li class='step3'>Replace each <em class='removChar'>"+ removChar +"</em> with a number <em class='num'>"+num+"</em></li><li class='step4'>Paste letters <em class='randseq'>"+randseq+" </em> to the end of sequence (NO SPACES)</li><li class='step6'><label>What is " + Math.floor(Math.random() * 10) + "+" + Math.floor(Math.random() * 10) + "=</label><input type='text' class='addition' placeholder='Complete the simple math problem..'/><input type='submit' class='submit' onclick='submitForm()'/></li><li class='step5'>Validate</li></ul><p class='attention'>**Answer is case sensitive</p><div class='repactcha-sub'><input class='an' type='text' value='' placeholder='Recaptcha Answer...'/><a href='#' onclick='vd()'  data-id='"+elementID+"' class='button'>Validate</a></div></div>";

					recpDiv.innerHTML = recaptchaContent;
					recptchas[c].appendChild(recpDiv);
				}
			}

			function recaptcaReload(dataID){
				//word picked
				var pickedWord =  findRanWrd();
				// letter to replace
				var removChar = rndlttr(pickedWord);
				// random number to replace letters with
				var num = randNumber();
				// random sequence
				var randseq = randomSequence();

				document.querySelector('#'+dataID+' .keyword').innerHTML = recpRandomWords[pickedWord];
				document.querySelector('#'+dataID+' .removChar').innerHTML = removChar;
				document.querySelector('#'+dataID+' .num').innerHTML = num;	
				document.querySelector('#'+dataID+' .randseq').innerHTML = randseq;	
			}

			function vd(){
				event.preventDefault();
				let dataID = event.target.getAttribute('data-id');

				clearInterval(myTimer);
				//if form was filled too fast, shut them down
				if(interval < 2){
					holdThePhone();
					holdPopUp();
				}else{
					//makes sure step 6 is correct
					var step6 = document.querySelector('#'+dataID+' .addition').value;
					if(step6 !== ''){
						holdThePhone();
						holdPopUp();
					}else{
						//this starts the recap validation
						let vldltt = document.querySelector('#'+dataID+' .keyword').innerHTML;
							let validteLetters = vldltt.split(''); 
						let vadRemovChar = document.querySelector('#'+dataID+' .removChar').innerHTML;
						let vadNum = document.querySelector('#'+dataID+' .num').innerHTML;
						let vadRandseq = document.querySelector('#'+dataID+' .randseq').innerHTML;
						
						let check = '';

						// sets the array that will check our answer against the answer
						for(let g=0;g<validteLetters.length;g++){
							//changes the letter to the number
							 if(validteLetters[g] == vadRemovChar){
								check += vadNum;
							}else{
								check += (validteLetters[g]);
							}
						}

						let charLetters = vadRandseq.split('');
						for(let j=0;j< charLetters.length;j++ ){
							check +=(charLetters[j]);
						}

						let check2 = document.querySelector('#'+dataID+' .an').value;
						
						if(check2.trim() == check.trim()){
							document.querySelector('#'+dataID+'  .recaptcha-wrapper').classList.add('complete');
							document.querySelector('#'+dataID+'  .recaptcha-wrapper .title').innerHTML = "Recaptcha Complete!";

							//resets session varibales
							sessionStorage.setItem("recapthcaError", "0");
							sessionStorage.setItem("recapthcaHoldup", "");
							sessionStorage.setItem("recapthca", "complete");
							var recp = sessionStorage.getItem("recapthca");

							setTimeout(function(){
								document.querySelector('#'+dataID+' .recaptcha-wrapper').classList.remove('active');	
							},500);

							if(recp == "complete"){
								document.querySelector('#'+dataID+' .recaptcha-button.button').classList.add('hide');
								document.querySelector('#'+dataID+' .notice').classList.add('hide');

								document.querySelector('#'+dataID+' .gform_footer').classList.add('active');
							}
						}else{
							//adds 1 to the error sv
							recpErr = parseInt(sessionStorage.getItem("recapthcaError")) + 1;
								sessionStorage.setItem("recapthcaError", recpErr);

							if(recpErr >= 5){
								holdThePhone();
								holdPopUp();
							}else{
								var target = document.querySelector('#'+dataID+' .message');
								var tries = 5 - recpErr;
								var message = "Recaptcha Failed. " + tries + " Try(s) remaining."
								target.innerHTML = message;
								target.classList.add('error');

								// clears input fields
								document.querySelector('.an').value = "";
								document.querySelector('.an').placeholder = "Try again with new values..";

								recaptcaReload(dataID);
								//reload values
								setTimeout(function(){
									target.innerHTML = "";
									target.classList.remove('error');	
								},4000);
							}
						}
					}
				}
			}

			function exit(){
				//exits out of recaptcha
				document.querySelector('.recaptcha-wrapper.active').classList.remove('active');
			}

			function submitForm(){
				holdThePhone();
				holdPopUp("Sorry forms are down for a while. Try again later.");	
			}

			function holdThePhone(){
				//hides all and forms, and shows a message saying you failed too many times. Then in a few seconds that is removed too
				var holds = document.querySelectorAll('.holdup');
				for(let j=0;j<holds.length;j++){
					holds[j].remove();
				} 
				sessionStorage.setItem("recapthcaHoldup", "holdup");
			}

			function holdPopUp(customMessage){
					//this is a popup message the user will get
					const popupContainer = document.createElement("div");
						popupContainer.className = "popup-continer active";
						if(customMessage){
							popupContainer.innerHTML = "<div class='wrapper'><p class'sorry'>"+customMessage+"</p></div>";
						}else{
							popupContainer.innerHTML = "<div class='wrapper'><p class'sorry'>Sorry, too many failed attempts. Come back later.</p></div>";	
						}
						
						document.querySelector('footer').appendChild(popupContainer);
						setTimeout(function(){
							document.querySelector('.popup-continer').remove();
						},6000);
						sessionStorage.setItem("blockPopup","1");
				}
		</script>

        <style>
          .recaptcha-wrapper{height: 100%; width: 100%; position: fixed; top: 0; left: 0; z-index: 100000000000; display: none; opacity: 0;}
          	.recaptcha-wrapper.active{opacity: 1; display: block; animation: fadeBoxIn .25s ease-in;}
          	@keyframes fadeBoxIn{
          		0%{display: block; opacity: 0;}
          		100%{display: block; opacity: 1;}
          	}
          	
          .recaptcha-wrapper:before{content: ''; height: 100%; width: 100%;background-color: rgba(0,0,0,.75); position: absolute; top: 0;left: 0; z-index: -10;}
          .recaptcha-wrapper > div{width: 100%; position: fixed; top: 50%; transform: translateY(-50%); background-color: #646464; z-index: 1000000000; left: 0; right: 0; margin: 0 auto; max-width: 700px; padding: 25px;}
          .recaptcha-wrapper .title{font-weight: 600; font-size: 20px; padding: 0;margin: 0; color:white; text-align: left;} 
          .recaptcha-wrapper .explain{padding: 10px 0;margin: 0; color:white; text-align: left;}
          .recaptcha-wrapper ul{padding: 0 !important;margin: 0 0 0 35px !important;}
          .recaptcha-wrapper ul li{list-style-type: disc !important; color:white; margin: 0 !important; background-color: transparent !important; width: 100% !important;}
          	.recaptcha-wrapper ul li:before{display: none;}
          .recaptcha-wrapper .attention{padding: 15px 0 0;margin: 0;  font-weight: 600; color:white; text-align: left;}
          .recaptcha-wrapper em{color: var(--lt-blue); font-weight: 600;}
          .recaptcha-wrapper input{font-size: var(--fsize-default);}
          /*step6*/
          .recaptcha-wrapper .step6{height: 0; overflow: hidden; padding: 0;}
          .recaptcha-wrapper .step6 label{display: block !important;}
          .recaptcha-wrapper .step6 .submit{background: var(--lt-blue) !important; display: block; border: none; color: var(--white) !important; text-transform: uppercase; border: none !important;}
          	.recaptcha-wrapper .submit:hover{background-color: var(--blue);}
          /*answer*/
          .repactcha-sub{display: flex;align-items: stretch; justify-content: start;flex-wrap: wrap; margin-top: 15px;}
          .recaptcha-wrapper .an{width: calc(100% - 150px) !important; display: block; width: 100%; border: none; padding-left: 15px !important; font-family: var(--text-font); margin-bottom: 0;}
          .recaptcha-wrapper .button{width: 150px; text-align: center; padding: 10px;}
          /*message*/
          .recaptcha-wrapper .message{padding: 0;margin: 0; height: 100%; max-height: 0; transition: max-height .25s ease-in;}
          	.message.error{padding: 10px 0 0; font-weight: 900; color: red; max-height: 35px; transition: max-height .25s ease-in;}
          	/*complete*/
          	.recaptcha-wrapper.complete div{background-color: #58B85D;}
          	.recaptcha-wrapper.complete .explain,
          	.recaptcha-wrapper.complete ul,
          	.recaptcha-wrapper.complete .attention,
          	.recaptcha-wrapper.complete .repactcha-sub{display: none;}
          	/*exit*/
          	.recaptcha-wrapper .exit{position: absolute; font-weight: 900; font-size: 24px; top: 0px; right: -25px; color:white; margin: 0; line-height: normal; padding: 0;}
          		.recaptcha-wrapper .exit:hover{cursor: pointer; color:#646464;}
          	/*button*/
          	.gform_footer.active{display: block;}
          	.gform_footer{display: none;}
          	.gform_footer.active{display: block;}
          	.gform_body.gform-body .recaptcha-button.button{margin: 0 !important; position: absolute; bottom: 0; right: 0; width: calc(50% - 10px); text-align: center; padding: 12px 30px;}
          	.gform_body.gform-body .recaptcha-button.button.hide{display: none;}
          	/*notice*/
          	form .notice-wrp{position: absolute; bottom: -50px; right: -10px; width: 50%; margin: 0;padding: 0;}
          	form .notice{margin: 0; padding: 10px 0 !important; color:white;}
          	form .notice.hide{display: none !important;}
          	/*popup*/
          	.popup-continer{position: fixed; background-color: rgba(0,0,0,.5); height: 100%; width: 100%; top: 0; left: 0; z-index: 100000; display: none;}
          		.popup-continer.active{display: block;}
          	.popup-continer .wrapper{position: fixed; z-index: 10000; top: 50%;left: 0; transform: translateY(-50%) ;right: 0;margin: 0 auto; z-index: 10000; background-color: red; max-width: 600px; width: 100%; padding: 25px; text-align: center; font-weight: 900;}

        	@media all and (max-width: 760px){
        		form .notice-wrp{position: initial; width: 100%}
        		.gform_body.gform-body .recaptcha-button.button{position: initial; width: 100%}
        		.recaptcha-wrapper > div{width: 90%}
        		.recaptcha-wrapper .exit{top: -35px; right: 0}
        	}
        	@media all and (max-width: 480px){
        		.recaptcha-wrapper .an{width: 100% !important; padding: 10px;}
        		.recaptcha-wrapper .button{width: 100%}
        	}
        </style>
<?php } add_action( 'wp_footer', 'recaptcha_styles' ); } ?>
