<script src="{{asset('vendor/elements/tinymce/tinymce.min.js')}}"></script>

<script>
    function cleanHTML(str){
        if(!str) return '';

        // removes facebook like
        let content = str;
        content = content.replace(/<div/ig, '💚').replace(/<\/[ ]*div>/ig, '💗');
        content = content.replace(/(💚[ ]+class="(fb-like|fb_iframe_widget)[^"]*")([^💚💗]*)(💗)/igm, '');

        // restore
        content = content.replace(/💚/g, '<div').replace(/💗/g, "</div>");
        // removes font-family
        content = content.replace(/font-family[ ]*:[ ]*"[^"]*"/ig, '');
        //removes all style, id, class
        content = content.replace(/[ ]+(style|id|class|width|height|start|sizes|srcset)="[^"]*"/ig, '');
        // removes empty value
        content = content.replace(/[ ]+(title|alt)="[ ]*"/ig, '');
        // removes all data- attribute
        content = content.replace(/[ ]+data\-[a-z\-]+="[^"]*"/ig, '');
        // removes unnecessary space
        content = content.replace(/<(div|p|span|table|td|tr|h1|h2|h3|h4)[ ]+>/ig, "<$1>");
        // removes empty tag
        content = content
            .replace(/<div\s*>\s*<\/\s*div>/ig, '')
            .replace(/<p\s*>\s*<\/\s*p>/ig, '')
            .replace(/<h1\s*>\s*<\/\s*h1>/ig, '')
            .replace(/<h2\s*>\s*<\/\s*h2>/ig, '')
            .replace(/<span\s*>\s*<\/\s*span>/ig, '')
            .replace(/<strong\s*>\s*<\/\s*strong>/ig, '');
        
        // removes multiple spaces whith single space
        content = content.replace(/[ ]{2,}/g, ' ');
        return content;
    }

    function loadTinymce(){
        console.log(`loadTinymce`);
        const tinymceEles = document.querySelectorAll(`.tinymce-editor:not(.initialized-tinymce)`);
        if(tinymceEles.length){
            for(let i = 0; i < tinymceEles.length; i ++){
                const ele = tinymceEles[i];
                if(ele.classList.contains('initialized-tinymce')) return;
                ele.classList.add('initialized-tinymce');
                const editorName = tinymceEles[i].name;
                const inputId = `form-${editorName}-image-file`;
                tinymce.init({ 
                    selector: `#${tinymceEles[i].id}`, 
                    min_height : 350,
                    plugins : [
                        'textcolor colorpicker link image paste'
                    ],
                    paste_preprocess: function(plugin, args) {
                        if(args && args.content){
                            args.content = cleanHTML(args.content);
                        }
                    },
                    convert_urls : true,
                    relative_urls: false,
                    toolbar1: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor | link image',
                    file_picker_callback: function(callback, value, meta) {
                        // Provide image and alt text for the image dialog
                        if (meta.filetype === 'image') {
                            $(`#${inputId}`).click();
                            $(`#${inputId}`).on('change', function(){
                                var fd = new FormData($(`#form-${editorName}`)[0]);
                                var option = {
                                    url: $(`#form-${editorName}`).attr('action'),
                                    type: "POST",
                                    data: fd,
                                    processData: false,
                                    contentType: false,
                                };

                                var ajaxRequest = $.ajax(option).done(function (data) {
                                    if(data.status && data.image){
                                        callback(data.image, data.name);
                                    }
                                    else{
                                        alert('Cannot upload file.');
                                    }
                                }).fail(function (data) {
                                    console.log(data);
                                });
                            });
                        }
                    }
                });
            }
        }
    }
    
    document.addEventListener("DOMContentLoaded", function(){
        loadTinymce();        

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>