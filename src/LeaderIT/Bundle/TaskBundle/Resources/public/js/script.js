$(function () {

    var dayDiv = $("#day").find("li");
    var addTask = $("#addTask");
    var taskForm = $("#taskForm");
    var taskItems;

    $("#pullTasks").click(function () {
        $.ajax({
            url: '/task/api/day/pull',
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
                    //console.log(textStatus);
                    loadTasks(dayDiv);
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
        $("form").submit();

    });

    addTask.click(function () {
        $(this).hide();
        taskForm.slideDown();
    });


    showTasks(dayDiv);




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

function getDropdown(id) {
    return '<button name="'+id+'" type="button" class="btn btn-xs btn-default">X</button>'
}

function showTasks(dayDiv) {
    $(".task-item").remove();
    if (dayData.day.length > 0) {

        dayData.day.sort(function(a, b){
            if(a.priority > b.priority) { return 1; }
            if(a.priority < b.priority) { return -1; }
            return 0;
        });

        dayData.day.forEach(function (current, index, array) {
            var data = current.name + ' - ' + current.description;
            var el = $('<li class="task-item list-group-item" data-type="'+current.type+'" data-priority="'+current.priority+'" data-done="' + current.done + '" data-id="' + current.id + '">' +
            '<span class="list-item-data">' + data + '</span><span class="list-item-buttons">'+getDropdown(current.id)+'</span></li>');
            el.addClass("type"+current.type);
            if (current.done == true) el.addClass("task-item-done");
            dayDiv.before(el);
        });
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
    }

    $(".list-item-buttons").hide().unbind().click(function(){
        var id = /\d+/.exec(/name="\d+/.exec(this.innerHTML))[0];
        $.ajax({
            url: '/task/api/task/'+id,
            type: 'DELETE',
            data: {delete: 'delete'},
            success: function (data, textStatus, jqXHR) {
                loadTasks(dayDiv);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
            }
        });

    });

    $(".task-item").mouseover(function(){
        $(this).find(".list-item-buttons").fadeIn();
    }).mouseleave(function(){
        $(this).find(".list-item-buttons").fadeOut();
    });

    $("#task-container").sortable({
       cancel: "#add-task-item",
       distance: 30,
        stop: function(event, ui){
            var tasks = $(".task-item");

            var prioritize = function(i) {
                return function(index, item){
                    //$(item).data("priority", i++);
                    item.dataset.priority = i++;
                }
            }

            tasks.each(prioritize(1));

            updateTasks(tasks);
        }
    });
}

function updateTasks(tasks) {

    var data = $.map(tasks, function(obj, index){
        return {
            id: obj.dataset.id,
            priority: obj.dataset.priority,
            done: obj.dataset.done,
            name: obj.textContent
        };
    });

    $.ajax({
        url: '/task/api/day',
        type: 'POST',
        data: {
            tasks: data
        },
        success: function (data, textStatus, jqXHR) {
            console.log(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus);
        }
    });
}