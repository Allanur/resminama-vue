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

    <transition-group tag="div" name="fade" mode="out-in" class="papers-wrapper clearfix">
      <ul v-for="(list,key) in papers" class="papers" :key="key">
        <li v-for="paper in list.data" @click.stop="fetchData(paper.id, list.depth, paper.leaf)" :class="{opened: openedPaperIds.includes(paper.id)}">
          
          <feather type="download" class="download" v-if="paper.media" @click.stop="downloadMedia(paper.media)"/>

          {{ paper.name }} <span v-if="!paper.leaf" class="chevron" :class="{down: openedPaperIds.includes(paper.id)}"/>
          </li>
      </ul>
    </transition-group>

    <button class="btn btn-sm btn-outline-light" v-if="isAuth" id="logout" @click="logout">
      <feather type="log-out"/>
    </button>
  </div>
</template>

<script>
    export default {
        name: "Home",

        data() {
            return {
                papers: [],
                media: [],
                openedPaperIds: [],
                show: false,
                isAuth: null,
                loading: false,
                error: false,
            };
        },

        created() {
            this.isAuth = this.$auth.isAuthenticated();
            this.fetchData();
        },

        methods: {
            fetchData(parentId = null, depth = -1, isLeaf = false) {
                if (isLeaf) {
                    return;
                }

                this.papers = this.papers.slice(0, depth + 1);
                this.media  = this.media.slice(0, depth + 1);
                this.openedPaperIds = this.openedPaperIds.slice(0, depth + 1);
                this.openedPaperIds.push(parentId);

                this.loading = true;
                this.error = false;
                this.$http.get('api/papers?parent_id=' + parentId).then(response => {
                    this.loading = false;
                    this.papers.push({
                        data: response.body.data,
                        depth: depth + 1
                    });
                }, response => {
                    this.loading = false;
                    this.error = true;
                });
            },

            downloadMedia(media) {
              window.open('api/media/' + media.id + '/download');
            },

            logout() {
                if (confirm('Siz çyndanam çykmakçymy?')) {
                    this.$auth.destroyToken();
                    location.reload();
                }
            }
        }
    }
</script>
