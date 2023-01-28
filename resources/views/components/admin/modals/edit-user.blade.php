<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#edit-user-modal">
    <i class="bi bi-pencil-fill me-1"></i>
    {{ __('Edit user') }}
</button>

<div class="modal modal-xl fade" id="edit-user-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            @livewire('admin.edit-user', ['user' => $user])
        </div>
    </div>
</div>
