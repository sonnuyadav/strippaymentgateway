//for signupmodel
//for check Register url 
$(document).ready(function() {
    jQuery.validator.addMethod("alphaNumeric", function (value, element) {
        return this.optional(element) || /^[0-9a-zA-Z]+$/.test(value);
    }, "Fullname must contain only letters, numbers.");
    $('#payform').validate({
        rules: {
            fname: { 
                // minlength: 2,
                required: true,
                //alphaNumeric: true
            },
            email: {
                required: true,
                email: true,
                
            },
           
        },


        messages: {
            'fname': {
                required: 'This field is required.'
            },
            
            'email': {
                required: 'E-mail is required',
                email: 'Enter a valid Email',
                remote: "Email already register with us"
            },
            
        },
        submitHandler:function(){
            $('.stripe-button-el').prop('disabled',true);
         
        }


    });

     $(document).on('change keyup', '#payform', function(event) {
        var isEmpty = 0;
        $.each($(this).serializeArray(), function(index, val) { if (val.value == '') { ++isEmpty; } });
       
        if (isEmpty === 0) {
            if ($('#payform').valid()) {                   // checks form for validity
                $('button.stripe-button-el').prop('disabled', false);        // enables button
            } else {
                $('button.stripe-button-el').prop('disabled', true);   // disables button
            }
        } else{
            $('button.stripe-button-el').prop('disabled',false);
        };
    });
   
   });


/////////////////////////////////////////////////////////////