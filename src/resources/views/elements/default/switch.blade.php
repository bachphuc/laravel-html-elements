<label class="switch--round">
    <input {!! isset($id) ? 'id="' . $id . '"' : '' !!} type="checkbox" {!! isset($checked) && $checked ? 'checked' : '' !!} {!! isset($value) ? 'value="' . $value . '"' : '' !!}>
    <span class="switch__slider"></span>
</label>