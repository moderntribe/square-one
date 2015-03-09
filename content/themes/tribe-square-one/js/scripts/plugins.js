/**
 * @namespace modern_tribe.plugins
 * @since 1.0
 * @desc modern_tribe.plugins is where we add custom jquery plugins we write. Other non jquery functions used globally go in the functions file.
 */

$.extend( verge );

// Function: get height of hidden element
$.fn.get_hidden_height = function() {

    var $this = $(this ),
        zindex = $this.css('z-index'),
        pos = $this.css('position'),
        d_height = '0px',
        t_height = $this.css({
            'visibility': 'hidden',
            'height'    : 'auto',
            'position'  : 'fixed',
            'z-index'   : -1
        }).outerHeight();

    $this.css({
        'visibility': 'visible',
        'height'    : d_height,
        'position'  : pos,
        'z-index'   : zindex
    });

    return t_height;

};
