document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.description-field').forEach(el => {

    el.addEventListener('click', function () {
      if (this.dataset.editing === '1') return;
      this.dataset.editing = '1';

      const updateUrl = this.dataset.updateUrl;
      const original = this.textContent.trim();

      const textarea = document.createElement('textarea');
      textarea.className = 'form-control mb-2';
      textarea.value = original;

      const fileInput = document.createElement('input');
      fileInput.type = 'file';
      fileInput.multiple = true;
      fileInput.className = 'form-control mb-2';

      const btnSave = document.createElement('button');
      btnSave.className = 'btn btn-primary btn-sm me-2';
      btnSave.textContent = 'Guardar';

      const btnCancel = document.createElement('button');
      btnCancel.className = 'btn btn-secondary btn-sm';
      btnCancel.textContent = 'Cancelar';

      this.replaceWith(textarea);

      const fileListContainer = document.createElement('div');
      fileListContainer.className = 'file-list mt-2';

      textarea.after(fileInput, btnSave, btnCancel, fileListContainer);

      btnCancel.addEventListener('click', () => {
        textarea.replaceWith(el);
        fileInput.remove();
        btnSave.remove();
        btnCancel.remove();
        fileListContainer.remove();
        delete el.dataset.editing;
      });

      btnSave.addEventListener('click', () => {
        const formData = new FormData();
        formData.append('info', textarea.value);

        for (const file of fileInput.files) {
          formData.append('substitute_files[]', file);
        }

        fetch(updateUrl, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-HTTP-Method-Override': 'PATCH'
          },
          body: formData
        })
          .then(res => {
            if (!res.ok) throw new Error('Error al guardar');
            return res.json();
          })
          .then(data => {
            el.textContent = data.info;

            const fileListContainer = document.createElement('div');
            fileListContainer.className = 'file-list mt-2';

            const staticFileElements = textarea.closest('.absence-card__body').querySelectorAll('.substitute-file-link > div');

            staticFileElements.forEach(div => {
              const fileName = div.querySelector('i').nextSibling?.textContent?.trim() ?? '';
              const fileId = div.querySelector('button')?.dataset?.fileId ?? div.dataset.fileId;

              if (fileName && fileId) {
                const fileItem = document.createElement('div');
                fileItem.className = 'd-flex align-items-center justify-content-between mb-1';
                fileItem.innerHTML = `
                  <i class="fa-solid fa-paperclip me-1"></i>${fileName}
                  <button class="btn btn-sm btn-danger" data-file-id="${fileId}">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                `;

                fileItem.querySelector('button').addEventListener('click', () => {
                  fetch(`/teacher/absences/files/${fileId}`, {
                    method: 'DELETE',
                    headers: {
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                  })
                    .then(res => {
                      if (!res.ok) throw new Error('Error al eliminar');
                      return res.json();
                    })
                    .then(() => {
                      fileItem.remove();
                      const swalBox = document.createElement('div');
                      swalBox.textContent = 'La información se borró correctamente';
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
                      }, 1500);
                    })
                    .catch(err => {
                        const swalBox = document.createElement('div');
                        swalBox.textContent = err.message;
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
                    });
                });

                fileListContainer.appendChild(fileItem);
              }
            });


            btnCancel.click();
            const swalBox = document.createElement('div');
            swalBox.textContent = 'La información se guardó correctamente';
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
              window.location.reload();
            }, 1000);

          })
          .catch(err => {
            const swalBox = document.createElement('div');
            swalBox.textContent = err.message;
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

          });
      });
    });
  });
});
