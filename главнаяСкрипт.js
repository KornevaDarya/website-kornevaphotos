const images = document.querySelector('.images');
    const prevBtn = document.querySelector('.prev');
    const nextBtn = document.querySelector('.next');

    let currentIndex = 1;

    nextBtn.addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % (images.children.length - 4);
        updateGallery();
    });

    prevBtn.addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + images.children.length - 4) % (images.children.length - 4);
        updateGallery();
    });

    function updateGallery() {
        const offset = currentIndex * -32;
        images.style.transform = `translateX(${offset}%)`;
    }