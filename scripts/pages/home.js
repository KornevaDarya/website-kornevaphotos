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

const btnUp = {
  el: document.querySelector('.btn-up'),
  show() {
    // удалим у кнопки класс btn-up_hide
    this.el.classList.remove('btn-up_hide');
  },
  hide() {
    // добавим к кнопке класс btn-up_hide
    this.el.classList.add('btn-up_hide');
  },
  addEventListener() {
    // при прокрутке содержимого страницы
    window.addEventListener('scroll', () => {
      // определяем величину прокрутки
      const scrollY = window.scrollY || document.documentElement.scrollTop;
      // если страница прокручена больше чем на 400px, то делаем кнопку видимой, иначе скрываем
      scrollY > 400 ? this.show() : this.hide();
    });
    // при нажатии на кнопку .btn-up
    document.querySelector('.btn-up').onclick = () => {
      // переместим в начало страницы
      window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'smooth'
      });
    }
  }
}
btnUp.addEventListener();
