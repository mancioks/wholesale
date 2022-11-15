<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#customer-signature-modal" onclick="window.dispatchEvent(new Event('signatureModalOpen'))">
    <i class="bi bi-type-strikethrough"></i> {{ __('Sign') }}
</button>
<div class="modal fade" id="customer-signature-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg text-black">
        <div class="modal-content">
            <form action="{{ route('admin.sign.order', $order) }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Customer signature') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="wrapper w-100">
                        <canvas id="signature-pad" class="signature-pad" width=100 height=300></canvas>
                    </div>
                    <textarea id="signature64" name="signature" class="d-none"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="clear">{{ __('Clear') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Sign') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    window.addEventListener('signatureModalOpen', function () {
        setTimeout(function() {
            var canvas = document.getElementById('signature-pad');
            canvas.width = $(canvas).parent().width();
            document.getElementById('signature64').value = '';

            var signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgba(255, 255, 255, 0)',
                penColor: 'rgba(11, 11, 69, 0.85)',
                throttle: 0
            });

            signaturePad.addEventListener("endStroke", () => {
                document.getElementById('signature64').value = signaturePad.toDataURL('image/png');
            });

            document.getElementById('clear').addEventListener('click', function () {
                signaturePad.clear();
                document.getElementById('signature64').value = '';
            });
        }, 500);
    });
</script>
<style>
    .wrapper {
        width: 100%;
    }
</style>
