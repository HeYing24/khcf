// Xác nhận khi xóa
function confirmDelete() {
    return confirm("Bạn có chắc chắn muốn xoá không?");
}

// Toggle menu (nếu có menu ẩn/hiện)
function toggleMenu() {
    const menu = document.getElementById("sidebar");
    menu.classList.toggle("hidden");
}
