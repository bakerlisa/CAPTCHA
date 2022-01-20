![](https://github.com/lisabroadhead/Recaptcha/blob/main/recaptcha.png)

# Recaptcha
Fully Functioning spam filter that has 10 levels of security, but is slim and rpackaged to integrate with any Gravity Forms form

WORKING EXAMPLE: https://www.rovepestcontrol.com/

** Installation Instructions **

How to add recaptcha:
**built around and for gravity forms forms

1. Add the recaptcha file - on section (I do it for each)
	<?php include(locate_template( '/template-parts/forms.php' )); ?>
	<?php include(locate_template( '/template-parts/recaptcha.php' )); ?>
2. On all contact form sections add class “holdup”
3. Remove old form scripts (theme.ph, footer.php, or the section you’re on might have it)
4. Make necessary style changes
