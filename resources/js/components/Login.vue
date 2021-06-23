<template>
  <div>
    <modal :height="300" :draggable="true" name="login-user" class="text-center">
      <h2>Login</h2>

      <input type="text" class="form-control w-75 mb-3" v-model="username" placeholder="Ulanyjy ady">
      <input type="password" class="form-control w-75" v-model="password" placeholder="Açar sözi">

      <p class="text-center text-danger mt-4" v-if="error">{{ errorMessage }}</p>

      <div class="actions">
        <button class="btn btn-primary" @click="authenticate">Içeri gir</button>
      </div>
    </modal>

    <div class="text-center">
      <button class="btn btn-lg btn-primary" id="login" style="margin-top: 150px;" @click="$modal.show('login-user')">Içer gir</button>
    </div>

  </div>
</template>

<script>
    export default {
        name: "Login",

        data() {
            return {
                username: null,
                password: null,
                error: null,
                errorMessage: "Näsazlyk ýüze çykdy"
            }
        },

        mounted() {
            this.$modal.show('login-user');
        },

        methods: {
            authenticate() {
                let data = {
                    username: this.username,
                    password: this.password,
                };

                this.error = false;
                this.$http.post('api/login', data)
                    .then(response => {
                        this.$auth.setToken(response.body.api_token);
                        this.$router.push('/panel');
                    }, err => {
                        this.error = true;
                    });
            }
        }
    }
</script>

<style scoped>
  h2 {
    text-align: center;
    padding: 8px;
    margin: 12px 0;
  }

  input {
    margin-left: 12%;
  }

  .actions {
    text-align: center;
    margin-top: 24px;
  }

  .actions button {
    width: 20%;
  }
</style>