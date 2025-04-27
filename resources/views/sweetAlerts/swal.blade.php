@if(session('success'))
    <script>
        const swalBox = document.createElement('div');
        swalBox.textContent = "{{ session('success') }}";
        swalBox.style.position = 'fixed';
        swalBox.style.bottom = '1rem';
        swalBox.style.right = '1rem';
        swalBox.style.minWidth = '300px';
        swalBox.style.maxWidth = '90%';
        swalBox.style.textAlign = 'center';
        swalBox.style.padding = '0.8rem 1.5rem';
        swalBox.style.backgroundColor = '#dff0d8'; 
        swalBox.style.color = '#3c763d';
        swalBox.style.border = '1px solid #d6e9c6';
        swalBox.style.borderRadius = '8px';
        swalBox.style.fontSize = '0.9rem';
        swalBox.style.zIndex = 9999;
        swalBox.style.boxShadow = '0 4px 10px rgba(0,0,0,0.2)';
        document.body.appendChild(swalBox);

        setTimeout(() => {
            swalBox.remove();
        }, 4000);
    </script>
@endif

@if(session('error'))
    <script>
        const swalBox = document.createElement('div');
        swalBox.textContent = "{{ session('error') }}";
        swalBox.style.position = 'fixed';
        swalBox.style.bottom = '1rem';
        swalBox.style.right = '1rem';
        swalBox.style.minWidth = '300px';
        swalBox.style.maxWidth = '90%';
        swalBox.style.textAlign = 'center';
        swalBox.style.padding = '0.8rem 1.5rem';
        swalBox.style.backgroundColor = '#f2dede'; 
        swalBox.style.color = '#a94442';
        swalBox.style.border = '1px solid #ebccd1';
        swalBox.style.borderRadius = '8px';
        swalBox.style.fontSize = '0.9rem';
        swalBox.style.zIndex = 9999;
        swalBox.style.boxShadow = '0 4px 10px rgba(0,0,0,0.2)';
        document.body.appendChild(swalBox);

        setTimeout(() => {
            swalBox.remove();
        }, 4000);
    </script>
@endif
