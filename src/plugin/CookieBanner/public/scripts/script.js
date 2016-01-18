$(function(){
    var cookiebanner = $("#cookie_banner");
    cookiebanner.find(".button").click(function(e){
        e.preventDefault();
        document.cookie = "cookie_approval=true; expires=Fri, 31 Dec 9999 23:59:59 GMT";
        cookiebanner.remove();
    });
});