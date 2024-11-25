<div class="modal" id="M{{ $id }}" style="z-index: {{ $zIndex }}">
    <div {{ $attributes->merge(['class' => 'modal-dialog']) }}>
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-left" {{ $title }}></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <form id="F{{ $id }}" data-parsley-errors-messages-disabled onsubmit="return false">
                {{ $slot }}
            </form>
        </div>
    </div>
</div>
