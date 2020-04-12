<div>
    <h4>{{isset($title) ? $title : trans('lang.upload_image')}}</h4>
    <!-- this image will use for album cover -->
    <div imginput>
        <img id="preview_image{{isset($name) ? '_' . $name : ''}}" src="{{isset($value) && !empty($value) ? asset($value) : asset('images/icon_upload_photo.svg')}}" />
        <input onchange="readURL(this, '#preview_image{{isset($name) ? '_' . $name : ''}}')" accept="image/*" type="file" name="{{isset($name) ? $name : 'image'}}" {{isset($validators) && in_array('required', $validators) ? 'required' : ''}}/>
    </div>
</div>