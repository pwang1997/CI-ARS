$(document).ready(() => {
    arr_color = ['#ff0000', '	#ffff00', '#80ff00', '#00bfff', '	#0080ff', '	#0040ff', '#ff8000', '	#0000ff', '#ff00ff', '#ff00bf', '#ff0080', '#ff0040'];
    i = 0;
    $(".square").each(function() {
        if (i == arr_color.length) {
            i = 0;
        }
        $(this).css('background-color', arr_color[i++])
    });

    $('#add_course').click(() => {
        window.location.replace('../courses/create');
    })
});