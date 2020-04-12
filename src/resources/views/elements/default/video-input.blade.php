@php
    $validators = isset($validators) ? $validators : [];
    $jsValidators = isset($jsValidators) ? $jsValidators : []; 
    $onChanged = '';
    $onChangeFn = "fn". str_random(8);
    $maxSize = 0;
    
    if(isset($validators['max']) || isset($jsValidators['max'])){
        $onChanged = 'onchange="'.$onChangeFn.'(this)"';
        $maxSize = isset($validators['max']) ? $validators['max'] : (isset($jsValidators['max']) ? $jsValidators['max'] : 0);
    }
    $sRequired = '';
    if(in_array('required', $validators) || isset($jsValidators['required'])){
        $sRequired = 'required';
    }
@endphp

<div>
    <h4>{{isset($title) ? $title : trans('lang.upload_video')}}</h4>
    <!-- this image will use for album cover -->
    <div>
        <input {!! $onChanged !!} type="file" class="pinput" accept=".gif,video/*" name="{{isset($name) ? $name : 'video'}}" {!! $sRequired !!} />
    </div>
</div>

@if(!empty($onChanged))
<script>
    function {!! $onChangeFn !!}(e){
        if(e.files&&e.files.length) {
            var mimes = ['video/mp4', 'video/mpeg', 'video/x-matroska', 'image/gif' , 'video/quicktime'];
            var f = e.files[0],v=document.createElement('video');
            if(mimes.indexOf(f.type)===-1) return (window.notify || alert)(`@lang('message.video_is_valid')`);
            v.onloadedmetadata=(d)=>{
                var kbps= Math.ceil(Math.round(f.size/128/v.duration)/16)*16;
                var mb = (f.size/1024/1024).toFixed(2);
                console.log(`video duration ${v.duration}, size ${f.size}, mb ${mb}, bitrate ${kbps}`);
                var m = {{floor($maxSize/1024)}};
                if(mb > m) {
                    (window.notify || alert)(`@lang('message.video_must_less_than') ${m} mb.`);e.value='';
                }
            };
            v.src=window.URL.createObjectURL(f);
        }
    }
</script>
@endif