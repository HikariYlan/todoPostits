input = document.getElementById('imageInput');
preview = document.getElementById('imagePreview');
previewBig = document.getElementById('imagePreviewBig');
previewLittle = document.getElementById('imagePreviewLittle');
input.addEventListener("change", () => {
    preview.classList.remove('hidden');
    previewBig.src = URL.createObjectURL(input.files[0]);
    previewLittle.src = URL.createObjectURL(input.files[0]);
})
