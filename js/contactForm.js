var contact = function(){
    
    var email,
    nameel = $('#contact_name'),
    emailel = $('#contact_email'),
    msgel = $('#contact_message'),
    form = $('#contact_form'),
    button = $('#contact_submit'),
    status = $('#contact_status'),
    valid = false;

    button.on('click', function(){
        console.log('submit clicked');
        check();
        submitForm();
        return false;
    });
    //sets the status

    
    function setStatus(txt) {
        status.show().html(txt).delay(4000).fadeOut(1000);
    }

    //submits the email address
    function submitForm() {

        //If everything is correct
        if (valid) {
            //unbind event and clear interval to prevent false status
            nameel.unbind('keyup');
            emailel.unbind('keyup');
            msgel.unbind('keyup');
				
            //disable button
            button.attr('disabled','disabled');
            setStatus('validating...');
            //ajax call
            $.post("/contact", {
                name: $.trim(nameel.val()),
                email: $.trim(emailel.val().toLowerCase()),
                msg: $.trim(msgel.val())
            }, function (data) {
                console.log(data);
                //enable the input
                button.removeAttr('disabled');
                if (data.success) {
                    //set status and clear the inputs
                    setStatus('email sent!');
                    nameel.val('').blur();
                    emailel.val('').blur();
                    msgel.val('').blur();
                    //now its invalid again
                    valid = false;
                } else {
                    //set status and rebind the keyupHandler
                    setStatus("sorry email didn't send.");
                    nameel.bind('keyup', keyupHandler);
                    emailel.bind('keyup', keyupHandler);
                    msgel.bind('keyup', keyupHandler);
                    //now its invalid again
                    valid = false;
                }
            }, "json");
        } else {
            //some fields are invalid
            var email = (emailel.val() == emailel.attr('placeholder')) ? '' : emailel.val();
            if(email != '' && !verify(email)){
                
                setStatus('invalid email address');
            }else{
                setStatus('Please verify the fields are correct.');
            }
        }
        return false;
    }

    //checks the inputs an change status + color of button
    function check() {
        email = emailel.val();
        if (verify(email) && nameel.val() != '' && msgel.val() != '' && nameel.val() != nameel.attr('placeholder') && msgel.val() != msgel.attr('placeholder')) {
            //valid inputs 
            button.removeClass('btn-danger').addClass('btn-success');
            valid = true;
        } else {
            //invalid inputs
            button.removeClass('btn-success').addClass('btn-danger');
            valid = false;
        }
    }

    //verify the syntax of an email
    function verify(email) {
        email = $.trim(email.toLowerCase());
        return (email && /^([\w-]+(?:\.[\w-]+)*)\@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$|(\[?(\d{1,3}\.){3}\d{1,3}\]?)$/.test(email))
    }
}