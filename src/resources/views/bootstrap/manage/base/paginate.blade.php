<div class="paginate">
    <div class="paginate-total">
        @lang('elements::lang.paginate_text', ['length' => $items->count(), 'total' => $items->total(), 'type' => isset($item_type) ? $item_type : trans('lang.object_s')])
    </div>
    <div class="paginate-items">
        @if(isset($params) && !empty($params))
        {{ $items->appends($params)->links() }}
        @else
        {{ $items->links() }}
        @endif
    </div>
    <div class="clear"></div>
</div>