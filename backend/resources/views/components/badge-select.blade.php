@props(['name' => 'badge', 'value' => null])

<x-form-select
    :name="$name"
    label="Badge"
    :value="$value"
    :options="['' => 'None', 'NEW' => 'NEW', 'SPECIAL' => 'SPECIAL']"
    {{ $attributes }}
/>
