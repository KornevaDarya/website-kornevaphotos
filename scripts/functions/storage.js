var today = new Date().toJSON().slice(0,10);

export function localStorageOnePerDay(name, func) {
    if (!localStorage.getItem(today + name)) {
        func();
        localStorage.setItem(today + name, "done");
    }
}