function deletechecked()
{
    var answer = confirm("Are you sure you want to delete this item?")
    if (answer){
        document.messages.submit();
        return false;
    }
    
    return false;  
} 

$(document).ready(
    function() {
        $('#nav a').hover(
            function() {
                $(this).fadeTo(700,0.6);
            },
            function() {
                $(this).fadeTo(700,1);
            }
        );
    }
);