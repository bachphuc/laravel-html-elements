<script src="{{ asset('vendor/elements/js/jquery-3.1.0.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendor/elements/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendor/elements/js/material.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendor/elements/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('vendor/elements/bootstrap/plugins/datetimepicker/js/bootstrap-datetimepicker.js') }}" type="text/javascript"></script>


<!--  Charts Plugin -->
{{-- <script src="{{ asset('vendor/elements/js/chartist.min.js') }}"></script> --}}

<!--  Notifications Plugin    -->
<script src="{{ asset('vendor/elements/js/bootstrap-notify.js') }}"></script>

<!--  Google Maps Plugin    -->
{{-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script> --}}

<!-- Material Dashboard javascript methods -->
<script src="{{ asset('vendor/elements/js/material-dashboard.js') }}"></script>

<!-- Material Dashboard DEMO methods, don't include it in your project! -->
<script src="{{ asset('vendor/elements/js/demo.js') }}"></script>

<script type="text/javascript">
    function readURL(input, target) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(target).attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function closestParent(el, selector, stopSelector) {
        if(!el) return null;
        if(!(el instanceof Element)) return null;
        let retval = null;
        while (el) {
            if (el.matches(selector)) {
                retval = el;
                break
            } else if (stopSelector && el.matches(stopSelector)) {
                break
            }
            el = el.parentElement;
        }
        return retval;
    }

    document.addEventListener("DOMContentLoaded", function(){
        tinymce.init({ 
            selector:'.tinymce-editor' , 
            min_height : 350,
            plugins : [
                'textcolor colorpicker link image'
            ],
            relative_urls : false,
            remove_script_host : false,
            convert_urls : true,
            toolbar1: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor | link image',
            file_picker_callback: function(callback, value, meta) {
                // Provide image and alt text for the image dialog
                if (meta.filetype == 'image') {
                    // callback('myimage.jpg', {alt: 'My alt text'});
                    $('#image_upload').click();
                    $('#image_upload').on('change', function(){
                        var fd = new FormData($('#upload-image-form')[0]);

                        var option = {
                            url: '{{url('image/upload')}}',
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

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    $(document).ready(function(){
        // Javascript method's body can be found in assets/js/demos.js
        $('.datetimepicker').datetimepicker({
            // keepOpen : true,
            // showClose : true,
            // debug : true,
            allowInputToggle : false,
            keepOpen : false
        });
    });

    function success(message){
        if(!message) return;
        $.notify({
        	icon: "notifications",
        	message: message

        },{
            type: 'success',
            timer: 4000,
            placement: {
                from: 'top',
                align: 'right'
            }
        });
    }

    function error(message){
        if(!message) return;
        $.notify({
        	icon: "notifications",
        	message: message

        },{
            type: 'danger',
            timer: 4000,
            placement: {
                from: 'top',
                align: 'right'
            }
        });
    }

    window.addEventListener('popstate', (event) => {
        console.log(`onpopstate location: ${document.location}, state: ${JSON.stringify(event.state)}`);
        renderPage(event.state);
    });

    window.addEventListener('load', () => {
        window.originalUrl = window.location.href;
        document.querySelectorAll('.fast-link').forEach(a => {
            a.addEventListener('click', fastLinkClicked);
        })

        $('#page-modal').on('hidden.bs.modal', () => {
            history.back();
        })
    });
    
    function hidePageModal(){
        $('#page-modal').modal('hide');
    }

    function renderPage(state){
        if(state === null){
            hidePageModal();
            return;
        }
        const href = state.href;
        if(state.type === 'modal'){
            try {
                Api.fetchPage(href).then((html) => {
                    $('#page-modal-title').text('');
                    $('#page-modal-content').html(html);
                    $.material.init();
                    $('#page-modal').modal('show');
                })
            } catch (error) {
                console.log(error)
            }
        }
        else if(window.originalUrl === href){
            // original URL
            hidePageModal();
        }
        else{
            window.location.href = href;
        }
    }

    function navigatePage(href){
        const state = {href: href, type: 'modal'};
        history.pushState(state, "", href);
        renderPage(state)
    }

    function fastLinkClicked(event){
        if(window.innerWidth < 960) return true;
        try{
            const href = event.target.tagName.toLowerCase() === 'a' ? event.target.href : event.target.closest('a').href;
            console.log(`fast link click: ${href}`)
            navigatePage(href)
        }
        catch(err){
            console.log(err);
        }
        event.preventDefault();
        event.stopPropagation();
        return false;
    }
</script>