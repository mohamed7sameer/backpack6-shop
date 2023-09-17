{{-- checklist --}}
@php
    $field['number_of_columns'] = $field['number_of_columns'] ?? 2;
    $field['options'] = \PragmaRX\Countries\Package\Countries::all()->sortBy('name.common')->pluck('name.common', 'name.common');

    // calculate the value of the hidden input
    $field['value'] = old_empty_or_null($field['name'], []) ??  $field['value'] ?? $field['default'] ?? [];
    if(!empty($field['value'])) {
        $field['value'] = is_array($field['value']) ? $field['value'] : json_decode($field['value']);
    }

    // define the init-function on the wrapper
    $field['wrapper']['data-init-function'] = $field['wrapper']['data-init-function'] ?? 'bpFieldInitCountries';
@endphp

<div class="p-3">
    <label>{{ __('backpack-shop::backpack-shop.crud.shipping-region.countries.filter-label') }}</label>
    <div>
        <a class="btn btn-secondary select-all-countries" data-select-targets='.{{ $field['name'] }}-filter-value input[type="checkbox"]'>{{ __('backpack-shop::backpack-shop.crud.shipping-region.countries.select-all-caption') }}</a>
        <a class="btn btn-secondary select-no-countries" data-select-targets='.{{ $field['name'] }}-filter-value input[type="checkbox"]'>{{ __('backpack-shop::backpack-shop.crud.shipping-region.countries.select-none-caption') }}</a>
        <a class="btn btn-secondary select-unselected-countries" data-skip='@json(bpshop_mapped_countries('skip'))' data-select-targets=".{{ $field['name'] }}-filter-value">{{ __('backpack-shop::backpack-shop.crud.shipping-region.countries.select-unselected-caption') }}</a>
    </div>
    <div class="pt-2 pb-2"></div>
    <input type="text" class="form-control bp-countries-filter" data-filter-targets=".{{ $field['name'] }}-filter-value">
    <small>{{ __('backpack-shop::backpack-shop.crud.shipping-region.countries.filter-hint') }}</small>
</div>

@include('crud::fields.inc.wrapper_start')
<label>{!! $field['label'] !!}</label>
@include('crud::fields.inc.translatable_icon')

<input type="hidden" value='@json($field['value'])' name="{{ $field['name'] }}">

<div class="row">
    @foreach ($field['options'] as $key => $option)
        <div class="col-sm-{{ intval(12/$field['number_of_columns']) }} {{ $field['name'] }}-filter-value" data-filter-value="{{ strtolower($option) }}" data-skip-value="{{ bpshop_country_slug($option) }}">
            <div class="checkbox">
                <label class="font-weight-normal">
                    <input type="checkbox" value="{{ $key }}"> {{ $option }}
                </label>
            </div>
        </div>
    @endforeach
</div>

{{-- HINT --}}
@if (isset($field['hint']))
    <p class="help-block">{!! $field['hint'] !!}</p>
@endif
@include('crud::fields.inc.wrapper_end')


{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
{{-- FIELD JS - will be loaded in the after_scripts section --}}
@push('crud_fields_scripts')
    @loadOnce('bpFieldInitCountries')
    <script>
        function bpFieldInitCountries(element) {
            var hidden_input = element.find('input[type=hidden]');
            var selected_options = JSON.parse(hidden_input.val() || '[]');
            var checkboxes = element.find('input[type=checkbox]');
            var container = element.find('.row');

            // set the default checked/unchecked states on checklist options
            checkboxes.each(function(key, option) {
                var id = $(this).val();

                if (selected_options.map(String).includes(id)) {
                    $(this).prop('checked', 'checked');
                } else {
                    $(this).prop('checked', false);
                }
            });

            // when a checkbox is clicked
            // set the correct value on the hidden input
            checkboxes.click(function() {
                var newValue = [];

                checkboxes.each(function() {
                    if ($(this).is(':checked')) {
                        var id = $(this).val();
                        newValue.push(id);
                    }
                });

                hidden_input.val(JSON.stringify(newValue)).trigger('change');

            });

            hidden_input.on('CrudField:disable', function(e) {
                checkboxes.attr('disabled', 'disabled');
            });

            hidden_input.on('CrudField:enable', function(e) {
                checkboxes.removeAttr('disabled');
            });

        }

        $('.bp-countries-filter').keyup(function() {
            let searchVal = $(this).val().toLowerCase(),
                filterClass = $(this).attr('data-filter-targets');
            if(searchVal === '') {
                $(`${filterClass}`).removeClass('d-none');
            } else {
                $(`${filterClass}`).addClass('d-none');
                $(`${filterClass}[data-filter-value*="${searchVal}"]`).removeClass('d-none');
            }
        });

        $('.select-unselected-countries').click(function() {
            let selectedCountries = $.parseJSON($(this).attr('data-skip')),
                countryCheckboxes = $(this).attr('data-select-targets');
            $(countryCheckboxes).each(function(index, element) {
                if(selectedCountries.indexOf($(element).attr('data-skip-value')) > -1) {
                    $(element).find('input[type="checkbox"]').prop('checked',true).trigger('click');
                } else {
                    $(element).find('input[type="checkbox"]').prop('checked',false).trigger('click');
                }
            });
        });

        $('.select-all-countries').click(function() {
            $($(this).attr('data-select-targets')).prop('checked', false).trigger('click');
        });

        $('.select-no-countries').click(function() {
            $($(this).attr('data-select-targets')).prop('checked', true).trigger('click');
        });
    </script>
    @endLoadOnce
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
