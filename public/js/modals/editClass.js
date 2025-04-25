function fillEditModal(data) {
    document.getElementById('num_class').value = data.num_class;
    document.getElementById('course').value = data.course;
    document.getElementById('code').value = data.code;

    document.getElementById('editClassForm').action = `/class/${data.id}`;
}
