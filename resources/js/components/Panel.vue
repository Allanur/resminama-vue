<template>
  <div>
    <div class="loading text-center" v-if="loading">
      <div class="loading-body">
        <div class="card bg-info border-0">
          <div class="card-body">
            <vue-loading spinner="circles"></vue-loading>
          </div>
        </div>
      </div>
    </div>

    <div class="loading text-center" v-if="error">
      <div class="loading-body">
        <div class="card bg-info border-0">
          <div class="card-body">
            <span class="text-danger">Näsazlyk ýüze çykdy</span>
          </div>
        </div>
      </div>
    </div>

    <input type="file" name="media" id="template" class="d-none" @change="onFile($event)">

    <transition-group tag="div" name="fade" mode="out-in" class="papers-wrapper clearfix">
      <ul v-for="(list,key) in papers" class="papers" :key="key">
        <li class="clearfix" v-for="(paper, i) in list.data" :key="paper.id" @click="fetchData(paper.id, list.depth, paper)"
            :class="{opened: openedPaperIds.includes(paper.id)}">

          <div class="edit-delete">
            <span class="red" @click.stop="deletePaper(paper, list.depth, i)"><feather type="x"/></span>
            <span class="blue" @click.stop="editPaper(paper, list.depth, i)"><feather type="edit-2"/></span>
          </div>

          {{ paper.name }}

          <span v-if="!paper.leaf" class="chevron" :class="{down: openedPaperIds.includes(paper.id)}"/>
        </li>
        <li class="media-upload">
            <div v-if="key>0" class="label">
              <span class="">{{ openedPapers[key].name }} üçin elektron maglumat</span>
            </div>
            <div>
              <button class="btn btn-sm btn-primary btn-upload" @click.prevent="downloadMedia(key)"><feather type="download-cloud"/></button>
              <input type="text" class="form-control" :class="{'is-invalid': media[key].error}" placeholder="ýüklenjek faýlyň ady" v-model="media[key].name">
              <button class="btn btn-sm btn-success btn-upload" @click="fileUploadClicked(key)"><feather type="upload"/></button>
              <button class="btn btn-sm btn-danger btn-upload" @click="fileDeleteClicked(key)"><feather type="x"/></button>
              <span class="invalid-feedback" style="margin-left: 42px;">{{ media[key].errorMessage }}</span>
            </div>
        </li>
        <li>
          <div class="input-group">
            <input type="text" class="form-control" placeholder="doldur" v-model="newPapers[key].value" :class="{'is-invalid': newPapers[key].error}">
            <div class="input-group-append">
              <button class="btn btn-primary btn-sm" @click="addPaper(key)">
                <feather type="plus-circle"/>
              </button>
            </div>
            <div class="invalid-feedback">{{ newPapers[key].errorMessage }}</div>
          </div>
        </li>
      </ul>
    </transition-group>

    <delete-paper-modal :paper="deletedPaper.paper" @deleted="removePaperFromList"/>
    <edit-paper-modal :paper="editedPaper.paper" :callback="editPaperCallback"/>

    <button class="btn btn-sm btn-outline-light" id="logout" @click="logout">
      <feather type="log-out"/>
    </button>
  </div>
</template>

<script>
    import DeletePaperModal from "./DeletePaperModal";
    import EditPaperModal from "./EditPaperModal";

    export default {
        name: "Panel",

        components: {
            DeletePaperModal,
            EditPaperModal,
        },

        data() {
            return {
                papers: [],
                media: [],
                newMedia: [],
                changedMediaKey: null,
                openedPaperIds: [],
                openedPapers: [],
                newPapers: [],
                deletedPaper: {
                    paper: null,
                    depth: null,
                    index: null
                },
                editedPaper: {
                    paper: null,
                    depth: null,
                    index: null
                },
                show: false,
                loading: false,
                error: false,
                fileUploadKey: 0
            };
        },

        created() {
            this.fetchData();
        },

        methods: {
            fetchData(parentId = null, depth = -1, paper = null) {
                this.papers = this.papers.slice(0, depth + 1);
                this.media = this.media.slice(0, depth + 1);
                this.newPapers = this.newPapers.slice(0, depth + 1);
                this.newPapers.push({
                    value: "",
                    error: false,
                    errorMessage: "",
                });
                this.openedPaperIds = this.openedPaperIds.slice(0, depth + 1);
                this.openedPaperIds.push(parentId);
                this.openedPapers = this.openedPapers.slice(0, depth + 1);
                this.openedPapers.push(paper);

                this.loading = true;
                this.error = false;
                this.$http.get('api/papers?parent_id=' + parentId).then(response => {
                    this.loading = false;
                    if (response.body.media == null) {
                      this.media.push({});
                    } else {
                      this.media.push(response.body.media);
                    }
                    this.papers.push({data: response.body.data, depth: depth + 1});
                }, response => {
                    this.loading = false;
                    this.error = true;
                    console.log('failed');
                });
            },

            logout() {
                if (confirm('Siz çyndanam çykmakçymy?')) {
                  this.$auth.destroyToken();
                  this.$router.push('/');
                }
            },

            addPaper(key) {
                let name = this.newPapers[key].value;
                let parentId = null;
                if (this.openedPaperIds.length > 0) {
                    parentId = this.openedPaperIds[key];
                }

                let data = {
                    parent_id: parentId,
                    name: name
                };

                this.newPapers[key].error = false;
                this.$http.post('api/papers', data)
                    .then(response => {
                        this.papers[key].data.push(response.body);
                        this.newPapers[key].value = "";
                    }, err => {
                        this.newPapers[key].error = true;
                        this.newPapers[key].errorMessage = err.body.errors.name.join('\n');

                        console.log(this.newPapers[key]);
                    });
            },
            deletePaper(p, d, i) {
              this.deletedPaper.paper = p;
              this.deletedPaper.depth = d;
              this.deletedPaper.index = i;
              this.$modal.show('delete-paper');
            },
            removePaperFromList() {
              let depth = this.deletedPaper.depth;
              this.papers[depth].data.splice(this.deletedPaper.index, 1);

              if (this.openedPaperIds.includes(this.deletedPaper.paper.id)) {
                this.papers = this.papers.slice(0, depth + 1);
                this.media = this.media.slice(0, depth + 1);
                this.openedPaperIds = this.openedPaperIds.slice(0, depth + 1);
                this.openedPapers = this.openedPapers.slice(0, depth + 1);
                this.newPapers = this.newPapers.slice(0, depth + 1);
                this.newPapers.push("");
              }
            },
            editPaper(p, d, i) {
              this.editedPaper.paper = p;
              this.editedPaper.depth = d;
              this.editedPaper.index = i;
              this.$modal.show('edit-paper');
            },
            editPaperCallback(p) {
              this.papers[this.editedPaper.depth].data[this.editedPaper.index] = p;
            },

            fileUploadClicked(key) {
              $('#template').trigger('click');
              this.changedMediaKey = key;
              this.media[key].paper_id = key > 0 ? this.openedPaperIds[key] : null;
            },

            fileDeleteClicked(key) {
              let m = this.media[key];

              if(! m.id) {
                alert('Faýl ýok');
                return;
              }
              
              if (! confirm('Tassyklaýaňyzmy?')) {
                return;
              }
              
              this.loading = true;
              this.$http.delete('api/media/' + parseInt(m.id))
                  .then(response => {
                    this.loading = false;
                    this.media[key] = {};
                  }, err => {
                    this.loading = false;
                    this.error = true;
                    this.errorMessage = 'Näsazlyk döredi';
                  });
            },

            downloadMedia(key) {
              let m = this.media[key];
              if (! m.id) {
                alert('Faýl ýok');
                return;
              }
              window.open('api/media/' + m.id + '/download');
            },

            onFile(event) {
              this.media[this.changedMediaKey].media = event.target.files[0];
              let m = this.media[this.changedMediaKey];
              
              let data = new FormData();
              if (m.name) {
                data.append('name', m.name);
              }
              if (m.paper_id) {
                data.append('paper_id', m.paper_id);
              }
              data.append('media', m.media);

              this.loading = true;
              this.$http.post('api/media', data)
                  .then(response => {
                    this.loading = false;
                    this.media[this.changedMediaKey] = response.body;
                    this.media[this.changedMediaKey].error = false;
                    this.media[this.changedMediaKey].errorMessage = "";
                    alert('Üstünlikli ýüklendi');
                  }, err => {
                    this.loading = false;
                    let msg = "";
                    let e = err.body.errors;
                    if (e.name) {
                      msg += e.name.join('\n');
                      msg += '\n';
                    }
                    if (e.media) {
                      msg += e.media.join('\n');
                      msg += '\n';
                    }
                    this.media[this.changedMediaKey].error = true;
                    this.media[this.changedMediaKey].errorMessage = msg;
                  });
            },
        }
    }
</script>

<style scoped>
  .input-group input {
    height: 39px;
  }

  .input-group button {
    padding-top: 0.35rem;
    padding-bottom: 0.15rem;
  }

  .input-group-append button {
    border-top-right-radius: 0.25rem !important;
    border-bottom-right-radius: 0.25rem !important;
  }

  .papers li:last-of-type {
    padding-right: 12px;
  }

  .papers li {
    position: relative;
    min-height: 48px;
    padding-left: 30px;
  }

  .papers li:last-of-type {
    padding-left: 12px;
  }
</style>
