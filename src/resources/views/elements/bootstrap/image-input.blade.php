<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>{{title_case(isset($title) ? $title : (isset($name) ? $name : ''))}}</label>
            <div class="form-upload-input">
                <div>
                    <img id="preview_image{{isset($name) ? '_' . $name : ''}}" src="{{isset($value) && !empty($value) ? asset($value) : asset('images/default_image.svg')}}" />
                    <input onchange="readURL(this, '#preview_image{{isset($name) ? '_' . $name : ''}}')" accept="image/*" type="file" name="{{isset($name) ? $name : 'image'}}" />
                </div>
            </div>
        </div>
    </div>
</div>