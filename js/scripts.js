document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.querySelector('.profile-info textarea[name="descripcion"]');
    if (textarea) {
        const autoResize = () => {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        };
        autoResize();
        textarea.addEventListener('input', autoResize);
    }
});
