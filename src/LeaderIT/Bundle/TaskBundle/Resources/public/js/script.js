$(function () {

    var dayDiv = $("#day").find("li");
    var addTask = $("#addTask");
    var taskForm = $("#taskForm");
    var taskItems;

    $("#pullTasks").click(function () {
        $.ajax({
            url: '/task/api/day',
            type: 'POST',
            data: {},
            success: function (data, textStatus, jqXHR) {
                loadTasks(dayDiv);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
            }
        });
    });

    $("form").submit(function (e) {
        var postData = $(this).serializeArray();
        var formURL = "/task/api/task/0";//$(this).attr("action");
        $.ajax(
            {
                url: formURL,
                type: "POST",
                data: postData,
                success: function (data, textStatus, jqXHR) {
                    //data: return data from server
                    console.log(textStatus);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //if fails
                    console.log(textStatus);
                }
            });
        e.preventDefault(); //STOP default action
        //e.unbind(); //unbind. to stop multiple form submit.
    });

    $("#send").click(function () {
        taskForm.slideUp();
        setTimeout(function () {
            addTask.slideDown();
        }, 200);
        //$("form").submit();

    });

    addTask.click(function () {
        $(this).hide();
        taskForm.slideDown();
    });


    showTasks(dayDiv);


    taskItems = $(".task-item");
    taskItems.dblclick(function () {
        $(this).toggleClass("task-item-done");

        var done = $(this).hasClass("task-item-done");
        var taskId = $(this).data("id");
        var postData = {done: done ? 'yes' : 'no'};

        $.ajax({
            url: "/task/api/task/" + taskId,
            type: "POST",
            data: postData
        });
    });

});

function loadTasks(dayDiv) {
    $.ajax({
        url: '/task/api/day',
        type: 'GET',
        data: {},
        success: function (data, textStatus, jqXHR) {
            dayData = JSON.parse(data);
            showTasks(dayDiv);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus);
        }
    });
}

function showTasks(dayDiv) {
    $(".task-item").remove();
    if (dayData.day.length > 0) {
        dayData.day.forEach(function (current, index, array) {
            var data = current.name + ' - ' + current.description;
            var el = $('<li class="task-item list-group-item" data-done="' + current.done + '" data-id="' + current.id + '">' + data + '</li>');
            if (current.done == true) el.addClass("task-item-done");
            dayDiv.before(el);
        });
    }
}