document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.description-field').forEach(el => {

    el.addEventListener('click', function () {
      if (this.dataset.editing === '1') return;
      this.dataset.editing = '1';
      const updateUrl = this.dataset.updateUrl;

      const absenceId = this.dataset.id;
      const original   = this.textContent.trim();

      const textarea = document.createElement('textarea');
      textarea.className = 'form-control mb-2';
      textarea.value = original;

      const btnSave   = document.createElement('button');
      btnSave.className = 'btn btn-primary btn-sm me-2';
      btnSave.textContent = 'Guardar';

      const btnCancel = document.createElement('button');
      btnCancel.className = 'btn btn-secondary btn-sm';
      btnCancel.textContent = 'Cancelar';

      this.replaceWith(textarea);
      textarea.after(btnSave, btnCancel);

      btnCancel.addEventListener('click', () => {
        textarea.replaceWith(el);
        btnSave.remove();
        btnCancel.remove();
        delete el.dataset.editing;
      });

      btnSave.addEventListener('click', () => {
        fetch(updateUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-HTTP-Method-Override': 'PATCH'
          },
          body: JSON.stringify({ info: textarea.value })
        })
        .then(res => {
          if (!res.ok) throw new Error('Error al guardar');
          return res.json();
        })
        .then(data => {
          el.textContent = data.info; 
          btnCancel.click();         
        })
        .catch(err => alert(err.message));
      });
    });

  });
});
