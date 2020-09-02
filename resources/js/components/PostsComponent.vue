<template>
  <div class="row">
    <div class="col-md-12">
      <div class="card mb-3 mt-3" v-for="item in list" v-bind:key="item.id">
        <!--Pasamos un parametro por la ruta-->
        <router-link class="card-header" :to="{name: 'post', params: {slug: item.slug}}" v-text="item.title"></router-link>

        <div class="card-body">
          <p class="card-text" v-text="item.exc"></p>
        </div>
      </div>

      <infiniteLoading @infinite="infiniteHandler">
        <div slot="no-more">---</div>
        <div slot="no-result">Sin resultados</div>
      </infiniteLoading>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      list: [],
      page: 0,
    };
  },
  methods: {
    infiniteHandler($state) {
      this.page++;
     
      axios
        .get(`/api/posts?page=${this.page}`)
        .then((result) => {

          let posts = result.data.data;

          if (posts.length) {
            this.list = this.list.concat(posts);
            $state.loaded();
          } else {
            $state.complete();
          }
        })
        .catch((err) => {
          console.log("ERROR AXIOS GET");
          console.log(err);
        });
    },
  },
};
</script>
