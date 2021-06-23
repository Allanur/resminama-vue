<template>
  <div>
    <modal :height="200" name="edit-paper" class="text-center">
      <h2>Resminama üýtgetmek</h2>

      <input type="text" class="form-control w-75" :value="paperName" v-on:input="newValue = $event.target.value" style="margin-left: 12%;">

      <p class="text-center text-danger" v-if="error">{{ errorMessage }}</p>

      <div class="actions">
        <button class="btn btn-primary" @click="editPaper">Üýget</button>
        <button class="btn btn-secondary" @click.prevent="$modal.hide('edit-paper')">Aýyr</button>
      </div>
    </modal>
  </div>
</template>

<script>
    export default {
        name: "EditPaperModal",

        props: ['paper', 'callback'],

        data() {
            return {
                newValue: null,
                error: null,
                errorMessage: "Näsazlyk ýüze çykdy"
            };
        },

        computed: {
            paperName() {
                if (this.paper == null) {
                    return "";
                }
                return this.paper.name;
            }
        },

        methods: {
            editPaper() {
                if (this.newValue === null) {
                    this.$modal.hide('edit-paper');
                    return;
                }

                this.paper.name = this.newValue;

                let data = {
                    name: this.paper.name
                };

                this.error = false;
                this.$http.patch('api/papers/' + this.paper.id, data)
                    .then(response => {
                        this.$emit('updated');
                        this.callback(this.paper);
                        this.$modal.hide('edit-paper');
                    }, err => {
                        console.log(err);
                        this.error = true;
                        this.errorMessage = 'Näsazlyk ýüze çykdy'
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

  .sure {
    font-size: 18px;
  }

  .actions {
    text-align: center;
    margin-top: 24px;
  }

  .actions button {
    width: 20%;
  }
</style>