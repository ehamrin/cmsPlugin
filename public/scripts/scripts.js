$(document).ready(function(){

    $(".fancybox").fancybox({
        openEffect	: 'elastic',
        closeEffect	: 'elastic',

        helpers : {
            title : {
                type : 'inside'
            }
        }
    });

    var fancyPDF = $(".fancybox-pdf");
    fancyPDF.each(function(index, element)
    {
        $(element).attr('data-fancybox-type', 'iframe');
    });

    fancyPDF.fancybox({
        openEffect	: 'elastic',
        closeEffect	: 'elastic',
        iframe : {
            scrolling : 'no',
            preload: false
        },
        scrolling : 'no',
        scrollOutside: false
    });
});