function passwordvalidation()
{
    //password validation for registration
    $('.password_required').slideDown();

$('#password').on('keyup',function()
{
    passValue = $(this).val();

    if(passValue.match(/[a-z]/g))
    {
        $('.lowercase').addClass('active');
    }
    else
    {
        $('.lowercase').removeClass('active');
    }

    if(passValue.match(/[A-Z]/g))
    {
        $('.uppercase').addClass('active');
    }
    else
    {
        $('.uppercase').removeClass('active');
    }

    if(passValue.match(/[0-9]/g))
    {
        $('.number').addClass('active');
    }
    else
    {
        $('.number').removeClass('active');
    }

    if(passValue.match(/[~!@#$%^&*_.]/g))
    {
        $('.special').addClass('active');
    }
    else
    {
        $('.special').removeClass('active');
    }

    if(passValue.length == 8 || passValue.length > 8)
    {
        $('.length').addClass('active');
    }
    else
    {
        $('.length').removeClass('active');
    }

    $('.password_required ul li').each(function(index,el){
        if((passValue.match(/[a-z]/g)) && (passValue.match(/[A-Z]/g)) && (passValue.match(/[0-9]/g)) && (passValue.match(/[~!@#$%^&*_.]/g)) && ((passValue.length == 8 || passValue.length > 8)))
    {
        $('.input_submit').removeClass('disabled')
    }
    });

    

})
}
