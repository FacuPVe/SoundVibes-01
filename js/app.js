function editUser(id, name, email, role) {
    
    const modalOverlay = document.createElement('div');
    modalOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';

    modalOverlay.innerHTML = `
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 transform transition-all scale-95 hover:scale-100">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Edit User</h2>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="${id}">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-300 focus:border-indigo-500 transition" value="${name}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-300 focus:border-indigo-500 transition" value="${email}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-300 focus:border-indigo-500 transition" required>
                        <option value="user" ${role === 'user' ? 'selected' : ''}>User</option>
                        <option value="admin" ${role === 'admin' ? 'selected' : ''}>Admin</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">Save Changes</button>
                    <button type="button" class="bg-gray-300 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-400 transition" id="cancelButton">Cancel</button>
                </div>
            </form>
        </div>
    `;
    
    document.body.appendChild(modalOverlay);

    document.getElementById('cancelButton').addEventListener('click', () => {
        document.body.removeChild(modalOverlay);
    });
}
