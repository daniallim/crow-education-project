document.addEventListener('DOMContentLoaded', function () {
    // DOM Elements
    const profileView = document.getElementById('profileView');
    const editForm = document.getElementById('editForm');
    const editProfileBtn = document.getElementById('editProfileBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const saveProfileBtn = document.getElementById('saveProfileBtn');
    const successMessage = document.getElementById('successMessage');

    // View Mode Elements
    const viewName = document.getElementById('viewName');
    const viewAccount = document.getElementById('viewAccount');
    const viewEmail = document.getElementById('viewEmail');
    const viewCategory = document.getElementById('viewCategory');
    const viewStartDate = document.getElementById('viewStartDate');
    const viewQuote = document.getElementById('viewQuote');
    const profileAvatar = document.getElementById('profileAvatar');

    // Edit Mode Elements
    const editName = document.getElementById('editName');
    const editAccount = document.getElementById('editAccount');
    const editEmail = document.getElementById('editEmail');
    const editCategory = document.getElementById('editCategory');
    const editStartDate = document.getElementById('editStartDate');
    const editQuote = document.getElementById('editQuote');
    const avatarPreview = document.getElementById('avatarPreview');
    const avatarInput = document.getElementById('avatarInput');
    const uploadBtn = document.getElementById('uploadBtn');
    const removeBtn = document.getElementById('removeBtn');
    const fileInfo = document.getElementById('fileInfo');

    // Store the current avatar state
    let currentAvatarFile = null;
    const defaultAvatar = 'https://i.pravatar.cc/150?img=12';

    // Switch to Edit Mode
    editProfileBtn.addEventListener('click', function () {
        profileView.style.display = 'none';
        editForm.style.display = 'block';

        // Populate form fields with current values
        editName.value = viewName.textContent;
        editAccount.value = viewAccount.textContent;
        editEmail.value = viewEmail.textContent;
        editCategory.value = viewCategory.textContent;
        editQuote.value = viewQuote.textContent.replace(/^"|"$/g, ''); // Remove quotes for editing

        // Set avatar preview to current avatar
        avatarPreview.src = profileAvatar.src;
        currentAvatarFile = null;
        fileInfo.textContent = 'No file chosen';

        // Format date for input field
        const dateParts = viewStartDate.textContent.split(' ');
        const formattedDate = `${dateParts[2]}-${getMonthNumber(dateParts[1])}-${dateParts[0].padStart(2, '0')}`;
        editStartDate.value = formattedDate;
    });

    // Cancel Editing
    cancelBtn.addEventListener('click', function () {
        editForm.style.display = 'none';
        profileView.style.display = 'block';
        // Reset any selected file
        currentAvatarFile = null;
        avatarInput.value = '';
    });

    // Save Profile Changes
    saveProfileBtn.addEventListener('click', function () {
        // Update view with new values
        viewName.textContent = editName.value;
        viewAccount.textContent = editAccount.value;
        viewEmail.textContent = editEmail.value;
        viewCategory.textContent = editCategory.value;
        
        // Update quote - add quotes if not present
        let quoteText = editQuote.value.trim();
        if (quoteText && !quoteText.startsWith('"')) {
            quoteText = `"${quoteText}"`;
        }
        viewQuote.textContent = quoteText || '"Add your motivation quote here!"';

        // Format date for display
        const dateObj = new Date(editStartDate.value);
        const formattedDate = `${dateObj.getDate()} ${getMonthName(dateObj.getMonth())} ${dateObj.getFullYear()}`;
        viewStartDate.textContent = formattedDate;

        // Update avatar if a new file was selected
        if (currentAvatarFile) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profileAvatar.src = e.target.result;
                avatarPreview.src = e.target.result;
            };
            reader.readAsDataURL(currentAvatarFile);
        } else {
            // Keep current avatar preview
            profileAvatar.src = avatarPreview.src;
        }

        // Show success message
        successMessage.style.display = 'block';

        // Hide success message after 3 seconds
        setTimeout(function () {
            successMessage.style.display = 'none';
        }, 3000);

        // Switch back to view mode
        editForm.style.display = 'none';
        profileView.style.display = 'block';
        
        // Reset file input
        avatarInput.value = '';
        currentAvatarFile = null;
    });

    // Avatar Upload Functionality
    uploadBtn.addEventListener('click', function () {
        avatarInput.click();
    });

    avatarInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            if (!file.type.match('image.*')) {
                alert('Please select a valid image file (JPEG, PNG, GIF, etc.)');
                return;
            }

            // Validate file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Please select an image smaller than 5MB');
                return;
            }

            currentAvatarFile = file;
            fileInfo.textContent = file.name;

            // Preview the image
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove Avatar
    removeBtn.addEventListener('click', function () {
        avatarPreview.src = defaultAvatar;
        avatarInput.value = '';
        currentAvatarFile = null;
        fileInfo.textContent = 'No file chosen';
    });

    // Helper functions for date formatting
    function getMonthNumber(monthName) {
        const months = {
            'Jan': '01', 'Feb': '02', 'Mar': '03', 'Apr': '04',
            'May': '05', 'Jun': '06', 'Jul': '07', 'Aug': '08',
            'Sep': '09', 'Oct': '10', 'Nov': '11', 'Dec': '12'
        };
        return months[monthName];
    }

    function getMonthName(monthIndex) {
        const months = [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ];
        return months[monthIndex];
    }
});