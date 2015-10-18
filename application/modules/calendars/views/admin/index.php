<div class="page-header position-relative">
    <h1>Full Calendar <small><i class="icon-double-angle-right"></i> with draggable and editable events</small></h1>
</div><!--/page-header-->

<div class="row-fluid">
<!-- PAGE CONTENT BEGINS HERE -->
<div class="row-fluid">
    <div class="span9">
        <div class="space"></div>
        <div id='calendar'></div>
    </div>
    
    <div class="span3">
        <div class="widget-box transparent">
            <div class="widget-header">
                <h4>Draggable events</h4>
            </div>
            <div class="widget-main">
                <div id='external-events'>
                    <div class='external-event label-important' data-class="label-important"><i class="icon-move"></i> ประชุม</div>
                    <div class='external-event label-success' data-class="label-success"><i class="icon-move"></i> สัมนา</div>
                    <div class='external-event label-info' data-class="label-info"><i class="icon-move"></i> อบรม</div>
                    <div class='external-event label-yellow' data-class="label-yellow"><i class="icon-move"></i> อื่นๆ</div>
                    <label>
                        <input type='checkbox' class="ace-checkbox" id='drop-remove' /> <span class="lbl"> Remove after drop</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- PAGE CONTENT ENDS HERE -->
</div>

<!-- inline scripts related to this page -->
<script type="text/javascript">
$(function() {

    /* initialize the external events
        -----------------------------------------------------------------*/
    
        $('#external-events div.external-event').each(function() {
        
            // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
            // it doesn't need to have a start or end
            var eventObject = {
                title: $.trim($(this).text()) // use the element's text as the event title
            };
            
            // store the Event Object in the DOM element so we can get to it later
            $(this).data('eventObject', eventObject);
            
            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });
            
        });

        

        
        /* initialize the calendar
        -----------------------------------------------------------------*/
        
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        
        var calendar = $('#calendar').fullCalendar({
             buttonText: {
                prev: '<i class="icon-chevron-left"></i>',
                next: '<i class="icon-chevron-right"></i>'
            },
        
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: "<?php echo base_url()?>calendars/admin/calendars/events"
            ,
            editable: true,
            droppable: true, // this allows things to be dropped onto the calendar !!!
            drop: function(date, allDay) { // this function is called when something is dropped
            
                // retrieve the dropped element's stored Event Object
                var originalEventObject = $(this).data('eventObject');
                var $extraEventClass = $(this).attr('data-class');
                
                
                // we need to copy it, so that multiple events don't have a reference to the same object
                var copiedEventObject = $.extend({}, originalEventObject);
                
                // assign it the date that was reported
                copiedEventObject.start = date;
                copiedEventObject.allDay = allDay;
                if($extraEventClass) copiedEventObject['className'] = [$extraEventClass];
                
                // render the event on the calendar
                // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
                
                // เซฟเหตการณ์เมื่อลาก label จากข้างนอกมาวางในปฏิทิน
                $.post('<?php echo base_url()?>calendars/admin/calendars/ajax_save',{
                    title : copiedEventObject.title,
                    start : copiedEventObject.start,
                    end : copiedEventObject.start,
                    className : $extraEventClass
                });
                // console.log(copiedEventObject);
                
                // is the "remove after drop" checkbox checked?
                if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    $(this).remove();
                }
                
            }
            ,
            eventDrop: function(event, start) {
				$.post('<?php echo base_url()?>calendars/admin/calendars/events_move',{id:event.id,start:start,end:start});
			},
			eventResize: function(event, end){
				$.post('<?php echo base_url()?>calendars/admin/calendars/events_move',{id:event.id,end:end});
			}, 
            selectable: true,
            selectHelper: true,
            select: function(start, end, allDay) {
                
                bootbox.prompt("เพิ่มกิจกรรมใหม่ :", function(title) {
                    if (title !== null) {
                        calendar.fullCalendar('renderEvent',
                            {
                                title: title,
                                start: start,
                                end: end,
                                allDay: allDay
                            },
                            true // make the event "stick"
                        );
                        
                        $.post('<?php echo base_url()?>calendars/admin/calendars/ajax_save',{
                            title : title,
                            start : start,
                            end : end
                        });
                    }
                });
                

                calendar.fullCalendar('unselect');
            }
            ,
            eventClick: function(calEvent, jsEvent, view) {

                var form = $("<form class='form-inline'><label>Change event name &nbsp;</label></form>");
                form.append("<input autocomplete=off type=text value='" + calEvent.title + "' /> ");
                form.append("<button type='submit' class='btn btn-small btn-success'><i class='icon-ok'></i> Save</button>");
                
                var div = bootbox.dialog(form,
                    [
                    {
                        "label" : "<i class='icon-trash'></i> Delete Event",
                        "class" : "btn-small btn-danger",
                        "callback": function() {
                            calendar.fullCalendar('removeEvents' , function(ev){
                                // ลบกิจกรรม
                                $.post('<?php echo base_url()?>calendars/admin/calendars/ajax_delete/'+calEvent._id);
                                return (ev._id == calEvent._id);
                            })
                        }
                    }
                    ,
                    {
                        "label" : "<i class='icon-remove'></i> Close",
                        "class" : "btn-small"
                    }
                    ]
                    ,
                    {
                        // prompts need a few extra options
                        "onEscape": function(){div.modal("hide");}
                    }
                );
                
                form.on('submit', function(){
                    calEvent.title = form.find("input[type=text]").val();
                    calendar.fullCalendar('updateEvent', calEvent);
                    div.modal("hide");
                    
                    // แก้ไขกิจกรรม
                    $.post('<?php echo base_url()?>calendars/admin/calendars/ajax_save',{
                        id : calEvent.id,
                        title : calEvent.title,
                        start : calEvent.start,
                        end : calEvent.end
                    });
                    
                    return false;
                });
                
                console.log(calEvent);
                console.log(calEvent.id);
                console.log(jsEvent);
                console.log(view);

                // change the border color just for fun
                //$(this).css('border-color', 'red');

            }
        });
})
</script>