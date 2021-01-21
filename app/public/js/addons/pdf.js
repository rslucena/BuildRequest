/**
 * Replace all SVG images with inline SVG
 */
function save_pdf() {

    let content = $(document).find('.main-panel');

    if( content === undefined || content === null){
        return;
    }

    window.print();

}