export default function (Vue) {
    Vue.auth = {

        setToken(token, expiration) {
            localStorage.setItem('token', token);
            localStorage.setItem('expiration', expiration);

            // TODO: this is not good, find better place
            Vue.http.headers.common['Authorization'] = 'Bearer ' + token
        },

        getToken() {
            let token = localStorage.getItem('token');

            if (! token ) {
                return null;
            }

            return token;
        },

        destroyToken() {
            localStorage.removeItem('token');
        },

        isAuthenticated () {
            return !!this.getToken();
        },
    };

    Object.defineProperties(Vue.prototype, {
        $auth: {
            get: () => {
                return Vue.auth
            }
        }
    });
}
