@if(isset($item))
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="control-label">{{isset($title) ? $title : ''}}</label>

            <div id="gallary-images-panel" class="gallary-images-panel">
                @foreach($item->getImages() as $image)
                <div id="gallery-item-{{$image->id}}" class="gallery-item" style="background-image:url({{$image->getThumbnailImage(120)}});">
                    <span data-id="{{$image->id}}" onclick="removeImage(this)"><i class="material-icons">close</i></span>
                </div>
                @endforeach
    
                <div class="bnt-select-file" id="bnt-select-file">
                    <input type="file" id="gallary-input" name="tmp_image" onchange="onImageChange(this)" />
                    <i class="material-icons">add</i>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var btnSelectFile = document.getElementById('bnt-select-file');

    function onImageChange(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
                btnSelectFile.style.backgroundImage = "url(" + e.target.result + ")";
                var fd = new FormData();
                fd.append('image', input.files[0]);
                fd.append('item_type', '{{$item->getType()}}');
                fd.append('item_id', {{$item->getId()}});
                fd.append('_token', $('meta[name="csrf-token"]').attr('content'));
                post('{{route('admin.upload-item-image')}}', fd, (percent) => console.log(percent), (err, res) => {
                    if(res.status){
                        $('#bnt-select-file').before(`<div id="gallery-item-${res.image.id}" class="gallery-item" style="background-image:url(${baseUrl + '/' + res.image.thumbnail_120});"><span data-id="${res.image.id}" onclick="removeImage(this)"><i class="material-icons">close</i></span></div> `);
                        btnSelectFile.style.backgroundImage = '';
                    }
                }, (err) => console.log(err));
			}

			reader.readAsDataURL(input.files[0]);
		}
    }
    
    function removeImage(ele){
        let photoId = ele.dataset['id'];
        var fd = new FormData();
        fd.append('photo_id', photoId);
        fd.append('item_type', '{{$item->getType()}}');
        fd.append('item_id', {{$item->getId()}});
        fd.append('_token', $('meta[name="csrf-token"]').attr('content'));
        post('{{route('admin.delete-item-image')}}', fd, (percent) => console.log(percent), (err, res) => {
            if(res.status){
                var img = document.getElementById('gallery-item-' + photoId);
                if(img){
                    img.parentNode.removeChild(img);
                }
            }
        }, (err) => console.log(err));
    }

    function post(u, fd, p, c, ec){
        
        var r = new XMLHttpRequest();r.upload.onprogress=(e)=>{
            var percen=e.lengthComputable?(Math.floor(e.loaded*100/e.total)):0;p && p(percen);
        };
        r.onload=(e)=>{
            if(r.readyState == 4){
                if(r.status==200){
                    var d = JSON.parse(r.responseText);
                    if(c) c(false, d);
                }
                else ec && ec(statusText);
            }
        };
        r.onerror = (e) => ec && ec(e);
        r.open('POST',u);
        
        r.send(fd);
    }
</script>
@endif
