function fillEditModal(data) {
    document.getElementById('editId').value = data.id;
    document.getElementById('editName').value = data.name;
    document.getElementById('editEmail').value = data.email;
    document.getElementById('editPhone').value = data.phone;
    document.getElementById('editDNI').value = data.dni;

    document.getElementById('editTeacherForm').action = `/teacher/${data.id}`;
}
