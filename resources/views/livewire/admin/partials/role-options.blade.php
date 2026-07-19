@foreach($rolesGrouped as $group => $groupRoles)
    <optgroup label="── {{ $group }} ──" style="color:#17231E;">
        @foreach($groupRoles as $r)
            <option value="{{ $r->key }}">{{ $r->label }}</option>
        @endforeach
    </optgroup>
@endforeach
