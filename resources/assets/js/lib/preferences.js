/**
 * This file stores the preferences in the local/session storage,
 * so at page (force) refresh there is no logout.
 *
 */

// a private collection
const storage = {
    lang: '_rgl',
    token: '_rgk',
    load(key, val, session) {
        const stored = (session ? window.sessionStorage.getItem(key) : window.localStorage.getItem(key));
        if (stored === null || stored === undefined) {
            return val;
        }

        return stored;
    },
    save(key, val, session) {
        let s = (session ? window.sessionStorage : window.localStorage);
        if (val === null) {
            s.removeItem(key);
        } else {
            s.setItem(key, val);
        }

    }
};

class Preferences {
    constructor() {
        this._lang = storage.load(storage.lang, 'en', false);
        this._token = storage.load(storage.token, null, true);
    }

    get lang() {
        return this._lang;
    }

    set lang(value) {
        storage.save(storage.lang, value, false);
        this._lang = value;
    }

    get token() {
        return this._token;
    }

    set token(value) {
        storage.save(storage.token, value, true);
        this._token = value;
    }
}

export default new Preferences();
