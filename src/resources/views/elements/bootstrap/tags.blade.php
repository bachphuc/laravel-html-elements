<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            @php
                $checked = isset($value) ? $value : [];
                $showImage = isset($showImage) ? $showImage : false;
                $showLink = isset($showLink) ? $showLink : false;
            @endphp
            <label>{{title_case(isset($title) ? $title : (isset($name) ? $name : ''))}}</label>
            <div tags id="{{isset($id) ? $id : (isset($name) ? $name.'Tags' : '')}}" data-name="{{isset($name) ? $name : ''}}" data-allowaddnew={{isset($allowAddNew) ? $allowAddNew : false}}>
                <input type="text" placeholder="{{isset($placeholder) ? $placeholder : ''}}" class="form-control" />
                <div>
                    @if(!empty($checked))
                        @if($dataType == 'model')
                            @foreach($items as $item)
                            @if(in_array($item->id, $checked))
                            <a {{$showLink ? 'href='. $item->getAdminHref() : ''}}> 
                                @if($showImage)<img src="{{$item->getThumbnailImage(120)}}" />@endif
                                <span>{{$item->getTitle()}}</span><input type="hidden" name="{{isset($name) ? $name : ''}}[]" value="{{$item->getId()}}" /> <i class="fa fa-times" aria-hidden="true"></i></span>
                            </a>
                            @endif
                            @endforeach
                        @elseif($dataType == 'array')
                            @foreach($items as $item)
                            @if(in_array($item->id, $checked))
                            <a><span>{{$item['title']}}</span><input type="hidden" name="{{isset($name) ? $name : ''}}[]" value="{{$item['value']}}" /> <i class="fa fa-times" aria-hidden="true"></i></span></a>
                            @endif
                            @endforeach
                        @endif
                    @endif
                </div>
                <ul class="hide">
                    @if(isset($dataType) && isset($items) && !empty($items))
                        @if($dataType == 'model')
                            @foreach($items as $item)
                            <li data-value="{{$item->getId()}}">{{$item->getTitle()}}</li>
                            @endforeach
                        @elseif($dataType == 'array')
                            @foreach($items as $item)
                            <li data-value="{{$item['value']}}">{{$item['title']}}</li>
                            @endforeach
                        @endif
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>