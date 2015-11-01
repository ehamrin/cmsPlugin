$(document).ready(function(){
    //http://t4t5.github.io/sweetalert/
    $('.delete').click(function(e){
        e.preventDefault();

        swal({
            title: "Are you sure?",
            text: "You will not be able to recover it if you do!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: false,
            closeOnCancel: false
        }, function (isConfirm) {
            if (isConfirm) {
                var element = $(e.target).parent('a');
                $.get(element.attr('href'), function( data ) {
                    element.parentsUntil('tr').parent().remove();
                    swal("Deleted!", "Your item has been deleted.", "success");
                });

            } else {
                swal("Cancelled", "Your item is safe :)", "error");
            }
        });
    });

    $('.logout-button').click(function(e){
        e.preventDefault();
        swal({
            title: "Logout",
            text: "Are you sure you want to log out?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#5dc2f1",
            confirmButtonText: "Yes!",
            cancelButtonText: "No!",
            closeOnConfirm: false,
            closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                var element = $(e.target);
                window.location.href = element.closest('a').attr('href');

            }
        });
    });

    $('a.confirmed-add').each(function(index, element){
        element = $(element);
        element.remove();
        swal({
                title: "Good job!",
                text: element.html(),
                type: "success",
                confirmButtonText: "Ok!"
            },
            function(){
                window.location.href = element.attr("href");
            });
    });



    $('.checkbox-submit').on('change', function(e){
        var target = $(e.target);
        if(!target.prop('checked')){
            swal({
                title: "Do you also want to delete all data?",
                text: "You will not be able to recover it if you do!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#5dc2f1",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, keep it please!",
                closeOnConfirm: true,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {


                }else{

                }
            });
        }


        //$(e.target).closest('form').trigger('submit');
    })

});
