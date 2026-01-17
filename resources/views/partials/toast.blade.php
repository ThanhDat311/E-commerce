<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Cấu hình mixin màu sắc
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-right',
            iconColor: 'white',
            customClass: {
                popup: 'colored-toast'
            },
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        const sessionData = {
            success: {!! json_encode(session('success')) !!},
            error: {!! json_encode(session('error')) !!},
            validationErrors: {!! json_encode($errors->any() ? implode('. ', $errors->all()) : null) !!}
        };

        if (sessionData.success) {
            Toast.fire({
                icon: 'success',
                title: sessionData.success,
                background: '#a5dc86', // Nền xanh
                color: '#fff'
            });
        }
        if (sessionData.error) {
            Toast.fire({
                icon: 'error',
                title: sessionData.error,
                background: '#f27474', // Nền đỏ
                color: '#fff'
            });
        }
        if (sessionData.validationErrors) {
            Toast.fire({
                icon: 'warning',
                title: "Có lỗi xảy ra",
                text: sessionData.validationErrors,
                background: '#f8bb86', // Nền cam
                color: '#fff'
            });
        }
    });
</script>