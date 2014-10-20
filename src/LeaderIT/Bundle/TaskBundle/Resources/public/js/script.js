var submitTaskForm;
var timers = [];

$(function () {

    (function($){
        $.fn.datepicker.dates['ru'] = {
            days: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье"],
            daysShort: ["Вск", "Пнд", "Втр", "Срд", "Чтв", "Птн", "Суб", "Вск"],
            daysMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"],
            months: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
            monthsShort: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
            today: "Сегодня",
            weekStart: 1
        };
    }(jQuery));

    var datepicker = $("#task-date");
    datepicker.datepicker({
        format: "dd.mm.yyyy",
        language: "ru",
        orientation: "top auto",
        todayHighlight: true,
        autoclose: true
    }).on('changeDate', function(e){
        var date = '/'+ e.format("ddmmyyyy");
        location.href = '/task'+date;
    });


    var dayDiv = $("#day").find("ul");
    var addTask = $("#addTask");
    var taskForm = $("#taskForm");
    var taskItems;

    $("#task-container").mouseleave(function(){
        $(".list-item-buttons").fadeOut();
    });

    submitTaskForm = function(id) {

        return function (e) {
            var postData = $(this).serializeArray();
            postData.push({name: 'leaderit_bundle_taskbundle_task[date]', value: date});
            $(this)[0].reset();
            var slug = id || 0;
            var formURL = "/task/api/task/"+slug;//$(this).attr("action");
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
        }
    }

    $("#pullTasks").click(function () {
        $.ajax({
            url: '/task/api/day/pull/'+date,
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

    $("form").submit(submitTaskForm(0));

    function hideForm() {
        taskForm.slideUp();
        setTimeout(function () {
            addTask.slideDown();
        }, 200);
    }

    $("#send").click(function () {
        hideForm();
        $("form").submit();
        loadTasks(dayDiv);
    });

    $("#cancel").click(function () {
        hideForm();
    });

    addTask.click(function () {
        $(this).hide();
        taskForm.slideDown();
        $('html, body').animate({
            scrollTop: taskForm.offset().top
        }, 500);
    });


    showTasks(dayDiv);




});

function loadTasks(dayDiv) {
    $.ajax({
        url: '/task/api/day/'+date,
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
    var deleteButton = '<button name="'+id+'" type="button" class="delete-task btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></button>';
    var editButton = '<button name="'+id+'" type="button" class="edit-task btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></button>';
    return editButton + deleteButton;
}

function showTasks(dayDiv) {
    $("#no-tasks").remove();
    $(".task-item").remove();
    if (dayData.day.length > 0) {

        dayData.day.sort(function (a, b) {
            if (a.priority > b.priority) {
                return 1;
            }
            if (a.priority < b.priority) {
                return -1;
            }
            return 0;
        });

        dayData.day.forEach(function (current, index, array) {
            var data = current.name + ' - ' + current.description;
            var el = $('<li id="task_' + current.id + '" class="task-item list-group-item" data-type="' + current.type + '" data-priority="' + current.priority + '" data-done="' + current.done + '" data-id="' + current.id + '">' +
            '<span class="list-item-data">' + data + '</span><span class="list-item-buttons">' + getDropdown(current.id) + '</span></li>');
            el.addClass("type" + current.type);
            if (current.done == true) el.addClass("task-item-done");
            dayDiv.append(el);
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

        taskItems.each(function (index, el) {
            _el = $(el);
            if (_el.hasClass("type1")) {
                _el.prepend("<span class='glyphicon glyphicon-circle-arrow-up task-icon'></span>");
            } else if (_el.hasClass("type2")) {
                _el.prepend("<span class='glyphicon glyphicon-circle-arrow-up task-icon'></span>");
            } else {
                _el.prepend("<span class='glyphicon glyphicon-circle-arrow-up task-icon'></span>");
            }
        });


        var listItemButtons = $(".list-item-buttons");
        listItemButtons.hide();
        listItemButtons.find(".delete-task").unbind().click(function () {
            $.ajax({
                url: '/task/api/task/' + $(this).attr("name"),
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
        listItemButtons.find(".edit-task").unbind().click(function () {
            var id = $(this).attr("name");
            var listItem = $("#task_" + id);

            $.ajax({
                url: '/task/api/task/' + id,
                type: 'GET',
                data: {},
                success: function (data, textStatus, jqXHR) {
                    var taskContainer = $("#task-container");
                    var editField = '<div class="edit-task-form" hidden="hidden">' + data +
                        '<button class="send-task" type="button" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-ok"></span>' +
                        '<button class="cancel-edit" type="button" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-remove"></span>'
                    '</div>';

                    taskContainer.sortable({disabled: true});
                    listItem.append(editField);
                    $(".edit-task-form").slideDown();
                    var form = listItem.find("form");
                    form.submit(submitTaskForm(id));
                    listItem.find(".send-task").click(function () {
                        form.submit();
                        listItem.find(".edit-task-form").remove();
                        taskContainer.sortable({disabled: false});
                        loadTasks(dayDiv);
                    });

                    listItem.find(".cancel-edit").click(function () {
                        var editForm = listItem.find(".edit-task-form");
                        editForm.slideUp();
                        setTimeout(function(){editForm.remove();},500);
                        taskContainer.sortable({disabled: false});
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus);
                }
            });
        });

        /*$(".list-item-buttons").hide().unbind().click(function(){
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

         });*/

        $(".task-item").mouseenter(function () {
            _this = $(this);
            timers.push(setTimeout(function () {
                $(".list-item-buttons").hide();
                _this.find(".list-item-buttons").fadeIn();
              }, 500));
        }).mouseleave(function () {
           _this.find(".list-item-buttons").fadeOut();
            timers.forEach(function(el){
                clearTimeout(el);
            })
          });


        $("#task-container").sortable({
            cancel: "#add-task-item",
            distance: 30,
            stop: function (event, ui) {
                var tasks = $(".task-item");

                var prioritize = function (i) {
                    return function (index, item) {
                        //$(item).data("priority", i++);
                        item.dataset.priority = i++;
                    }
                }

                tasks.each(prioritize(1));

                updateTasks(tasks);
            }
        });
    } else {
        // нет задач для отображения
        $("#no-tasks").remove();
        dayDiv.append("<li id='no-tasks' class='list-group-item'>Нажмите <b>+</b> для добавления задач</li>");
    }
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