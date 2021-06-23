<template>
  <div>
    <modal :height="200" name="delete-paper">
      <h2>Reminama pozmak</h2>
      <div class="text-center sure">Dowam etmelimi?</div>

      <p class="text-center text-danger" v-if="error">{{ errorMessage }}</p>

      <div class="actions">
        <button class="btn btn-warning" @click="deletePaper">Hawa</button>
        <button class="btn btn-secondary" @click.prevent="$modal.hide('delete-paper')">Ýok</button>
      </div>
    </modal>
  </div>
</template>

<script>
    export default {
        name: "DeletePaperModal",

        props: ['paper'],

        data() {
            return {
                error: null,
                errorMessage: "Näsazlyk ýüze çykdy!"
            };
        },

        methods: {
            deletePaper() {
                this.error = false;
                this.$http.delete('api/papers/' + this.paper.id)
                    .then(response => {
                        this.$emit('deleted');
                        this.$modal.hide('delete-paper');
                    }, err => {
                        this.error = true;
                        this.errorMessage = 'Näsazlyk ýüze çykdy!'
                    })
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