<section class="booking">
    <div class="container booking__container">
        <div class="booking__content">
            <h2 class="booking__title">Бронирование</h2>
            <p class="booking__subtitle">Свяжитесь со мной, и я с удовольствием отвечу на все интересующие вас вопросы!</p>
            <form class="booking__form request__form" method="post" action="/php/callme2.php">
                <input type="hidden" name="from" class="request__input-from" value="Форма бронирования на главной">
                <input type="hidden" name="comment" class="request__input-comment" value="">

                <label class="visually-hidden" for="booking-name">Имя</label>
                <input class="booking__input request__input-name" type="text" id="booking-name" name="name" placeholder="Имя" required>

                <label class="visually-hidden" for="booking-date">Дата съёмки</label>
                <input class="booking__input" type="date" id="booking-date" name="date">

                <label class="visually-hidden" for="booking-photo">Тип съёмки</label>
                <select class="booking__input booking__input--select" id="booking-photo" name="photo">
                    <option value="">Тип съёмки</option>
                    <option value="express">Экспресс</option>
                    <option value="full">Полный формат</option>
                    <option value="wedding">Свадебная</option>
                </select>

                <label class="visually-hidden" for="booking-message">Сообщение</label>
                <textarea class="booking__input booking__input--message" id="booking-message" name="message" placeholder="Ваше сообщение"></textarea>

                <label class="visually-hidden" for="booking-phone">Телефон</label>
                <input class="booking__input request__input-phone" type="tel" id="booking-phone" name="phone" placeholder="Телефон" required>

                <label class="booking__agree">
                    <input class="booking__agree-checkbox request__checkbox" type="checkbox" name="policy_agree" value="согласен">
                    <span class="booking__agree-text">Согласие на обработку персональных данных</span>
                </label>

                <button class="booking__btn" type="submit">Отправить</button>
            </form>
        </div>
    </div>
</section>
