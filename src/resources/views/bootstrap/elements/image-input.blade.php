<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>{{title_case(isset($title) ? $title : (isset($name) ? $name : ''))}}</label>
            <div class="form-upload-input">
                <div>
                    <img style="max-width: 96px;max-height:96px;" id="preview_image{{isset($name) ? '_' . $name : ''}}" src="{{isset($value) && !empty($value) ? asset($value) : asset('vendor/elements/img/default_image.svg')}}" />
                    <input onchange="readURL(this, '#preview_image{{isset($name) ? '_' . $name : ''}}')" accept="image/*" type="file" name="{{isset($name) ? $name : 'image'}}" />
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    if(!window.readURL){
        window.readURL = function(input, targetSelector) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    let target = document.querySelector(targetSelector);
                    if(target) target.src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    }
</script>