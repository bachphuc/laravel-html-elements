{{-- <script type="text/javascript" src="{{asset('tinymce_4.6.5/tinymce/js/tinymce/tinymce.min.js')}}"></script> --}}
<script src="{{asset('vendor/elements/tinymce/tinymce.min.js')}}"></script>

@php 
    $editorType = isset($type) ? $type : 'inline1';
@endphp
@if($editorType === 'inline')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        if(typeof tinymce === 'undefined') return log('no tinymce detect');
        console.log('init tinymce');
        tinymce.init({
            selector: '#{{isset($elementId) ? '#'. $elementId : 'mytextarea'}}',
            theme: 'inlite',
            plugins: 'image table link paste contextmenu textpattern autolink textcolor colorpicker',
            insert_toolbar: 'quickimage quicktable',
            // strikethrough
            selection_toolbar: 'bold italic underline | alignleft, aligncenter, alignright, alignjustify | forecolor backcolor | quicklink h1 h2 h3 blockquote',
            inline: true,
            paste_data_images: true,
            images_upload_url: '/articles/tinymceUpload',
            relative_urls: false,
            remove_script_host: false,
            setup: function (editor) {
                editor.addButton('mybutton', {
                    text: 'My button',
                    icon: false,
                    onclick: function () {
                        editor.insertContent('<img src="http://hinhanhdepvip.com/wp-content/uploads/2016/08/songoku-super-saiyan-cap-8.jpg" />');
                    }
                });
            },
            paste_preprocess: function(plugin, args) {
                if(args && args.content){
                    args.content = cleanHTML(args.content);
                }
            }
        });
    })
</script>
@else
<script>
    document.addEventListener("DOMContentLoaded", () => {
        if(typeof tinymce === 'undefined') return log('no tinymce detect');
        console.log('init tinymce');
        tinymce.init({
            selector: '#{{isset($elementId) ? $elementId : 'mytextarea'}}',
            plugins: 'paste',
            height: 300,
            paste_preprocess: function(plugin, args) {
                if(args && args.content){
                    args.content = cleanHTML(args.content);
                }
            }
        });
    })
</script>
@endif

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
        content = content.replace(/[ ]+(style|id|class|width|height)="[^"]*"/ig, '');
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
</script>