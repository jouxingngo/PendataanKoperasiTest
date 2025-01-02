<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ $action }}" method="POST">
                    @csrf
                    {{ $slot }}
                    <button type="submit" class="btn ml-auto d-block btn-primary">{{ $buttonText }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
