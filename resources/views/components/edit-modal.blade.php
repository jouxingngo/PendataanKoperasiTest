<div class="modal fade" id="EditModal-{{ $id }}" tabindex="-1" aria-labelledby="EditModal-{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="EditModal-{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ $action }}" method="POST">
                    @csrf
                    @method('PUT')
                    {{ $slot }}
                    <button type="submit" class="btn ml-auto d-block btn-primary">{{ $buttonText }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
