$(function () {
     $("#scd_dupefinder button").on('click', function (e) {
        e.preventDefault();
        $("#scd_dupefinder div:hidden").slice(0, 4).slideDown();
     });
});