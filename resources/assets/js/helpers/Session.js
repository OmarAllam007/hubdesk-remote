import is from 'is_js';

export default {
    storage: window.sessionStorage,

    set (key, value) {
        if (is.string(value) || is.number(value)) {
            this.storage.setItem(key, value);
        } else {
            this.storage.setItem(key, JSON.stringify(value));
        }
    },

    get (key) {
        let value = this.storage.getItem(key);

        let objectRegex = /^[\{].*[\}]$/;
        let arrayRegex = /^[\[].*[\]]/;
        let isJSON = arrayRegex.test(value) || objectRegex.test(value);

        if (isJSON) {
            value = JSON.parse(value);
        }

        return value;
    },

    remove(key) {
        return this.storage.removeItem(key);
    }
}
