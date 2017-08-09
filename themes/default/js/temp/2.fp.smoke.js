$('[data-smoke]').on('click', function(){
    var type = $(this).data('smoke');
    var msg = $(this).data('message');
    var classname = $(this).data('classname');
    var duration = $(this).data('duration') ? $(this).data('duration') : 3000;
    switch(type){
        case 'alert':
            smoke.alert(msg, function(e){
            }, {
                classname : classname
            });
            break;
        case 'confirm':
            smoke.confirm(msg, function(e){
                //if(e){ }else{ }
            }, {
                ok             : "Yes",
                cancel         : "No",
                classname      : classname,
                reverseButtons : true
            });
            break;
        case 'prompt':
            smoke.prompt(msg, function(e){
                //if(e){ }else{ }
            }, {
                ok             : "Yes",
                cancel         : "No",
                classname      : classname,
                reverseButtons : true,
                value          : ""
            });
            break;
    }
});