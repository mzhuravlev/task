

$(function(){
    var dayDiv = $("#day");

    $("form").submit(function(e)
    {
        var postData = $(this).serializeArray();
        var formURL = "/task/api/task/0";//$(this).attr("action");
        $.ajax(
            {
                url : formURL,
                type: "POST",
                data : postData,
                success:function(data, textStatus, jqXHR)
                {
                    //data: return data from server
                    console.log(textStatus);
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    //if fails
                    console.log(textStatus);
                }
            });
        e.preventDefault(); //STOP default action
        e.unbind(); //unbind. to stop multiple form submit.
    });

    $("#send").click(function(){
        $("form").submit();
    });


    dayData.day.forEach(function(current, index, array){
        var data = current.name+' - '+current.description;
        dayDiv.append('<li>'+data+'</li>');
    });
});