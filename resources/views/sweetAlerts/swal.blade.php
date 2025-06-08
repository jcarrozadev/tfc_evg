@if(session('success') || session('error'))
    <script>
        const swalBox = document.createElement('div');
        const isSuccess = "{{ session('success') ? 'true' : 'false' }}" === 'true';
        const message = isSuccess ? "{{ session('success') }}" : "{{ session('error') }}";

        swalBox.style.position = 'fixed';
        swalBox.style.bottom = '2rem';
        swalBox.style.right = '2rem';
        swalBox.style.minWidth = '380px';
        swalBox.style.maxWidth = '90%';
        swalBox.style.textAlign = 'left';
        swalBox.style.padding = '1rem 1.5rem';
        swalBox.style.display = 'flex';
        swalBox.style.alignItems = 'center';
        swalBox.style.gap = '1rem';
        swalBox.style.backgroundColor = isSuccess ? '#e6f9ec' : '#fdecea';
        swalBox.style.color = isSuccess ? '#2e7d32' : '#c62828';
        swalBox.style.border = `2px solid ${isSuccess ? '#81c784' : '#ef9a9a'}`;
        swalBox.style.borderLeftWidth = '6px';
        swalBox.style.borderRadius = '12px';
        swalBox.style.fontSize = '1rem';
        swalBox.style.zIndex = 9999;
        swalBox.style.boxShadow = '0 6px 15px rgba(0,0,0,0.25)';
        swalBox.style.transition = 'opacity 0.3s ease-in-out';

        const icon = document.createElement('i');
        icon.className = isSuccess ? 'fas fa-check-circle' : 'fas fa-times-circle';
        icon.style.fontSize = '1.5rem';
        icon.style.color = isSuccess ? '#2e7d32' : '#c62828';

        const text = document.createElement('span');
        text.textContent = message;

        swalBox.appendChild(icon);
        swalBox.appendChild(text);
        document.body.appendChild(swalBox);

        setTimeout(() => {
            swalBox.style.opacity = '0';
            setTimeout(() => swalBox.remove(), 300);
        }, 4000);
    </script>
@endif
