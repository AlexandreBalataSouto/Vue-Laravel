<template>
  <div class="row">
    <div class="col-md-12">
      <button class="btn btn-secondary btn-block" @click="agregar()" v-if="!isAdding && !isEditing">Nuevo Post</button>
    </div>
    <div class="col-md-12 mt-2">
      <form @submit.prevent="editarPost(post)" v-if="isEditing">
        <h3>Editar post</h3>
        <input type="text" placeholder="Titulo" class="form-control mb-2" v-model="post.title"/>
        <textarea class="form-control mb-2" rows="3" v-model="post.body"></textarea>
        <button class="btn btn-warning" type="submit">Editar</button>
      </form>

      <form @submit.prevent="agregarPost()" v-if="isAdding">
        <h3>Agregar post</h3>
        <input type="text" placeholder="Titulo" class="form-control mb-2" v-model="post.title"/>
        <textarea class="form-control mb-2" rows="3" v-model="post.body"></textarea>
        <button class="btn btn-primary" type="submit">Agregar</button>
      </form>

      <hr class="mt-3" />

      <div class="card mb-3 mt-3" v-for="(post, index) in list" :key="index">
        <div class="card-header">
          <router-link :to="{ name: 'post', params: { slug: post.slug } }" v-text="post.title">
          </router-link>
          <span class="badge float-right" v-bind:class="[
              moment(post.updated_at).locale('es').format('L') <=
              moment().locale('es').format('L')
                ? 'badge-primary'
                : 'badge-secondary',
            ]">
            {{ moment(post.updated_at).locale("es").format("L") }}
          </span>
        </div>

        <div class="card-body">
          <p class="card-text" v-text="post.exc"></p>
        </div>
        <div class="card-footer">
          <button class="btn btn-warning btn-sm" @click="editar(post)">
            Editar
          </button>
          <button class="btn btn-danger btn-sm float-right" @click="eliminar(post, index)">
            Eliminar
          </button>
        </div>
      </div>

      <infiniteLoading @infinite="infiniteHandler">
        <div slot="no-more">---</div>
        <div slot="no-result">Sin resultados</div>
      </infiniteLoading>
      <loading v-if="isLoading"></loading>
    </div>
  </div>
</template>

<script>
var moment = require("moment");

export default {
  data() {
    return {
      moment: moment,
      list: [],
      post: { id: "", title: "", body: "" },
      isEditing: false,
      isAdding: false,
      isLoading: false,
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
        });
    },
    agregar(){
      this.isAdding = true;
      this.scrollToTop();
    },
    agregarPost() {
      if (this.post.title.trim() === "" || this.post.body.trim() === "") {
        alert("Debes completar todos los campos antes de guardar");
        return;
      }
      this.isLoading = !this.isLoading;

      let params = {
        title: this.post.title,
        body: this.post.body,
      };
      this.post.title = "";
      this.post.body = "";

      axios
        .post("/api/posts", params)
        .then((result) => {
            this.isLoading = !this.isLoading;
            this.isAdding = false;
            this.list.unshift(result.data);
        })
        .catch((err) => {
          console.log("ERROR AGREGAR");
        });
    },
    eliminar(item, index) {
      this.isLoading = !this.isLoading;
      axios
        .delete(`/api/posts/${item.id}`)
        .then(() => {
          this.isLoading = !this.isLoading;
          this.list.splice(index, 1);
        })
        .catch((err) => {
          console.log("ERROR ELIMINAR");
        });
    },
    editar(item) {
      this.isEditing = true;
      this.post.title = item.title;
      this.post.body = item.body;
      this.post.id = item.id;
      this.scrollToTop();
    },
    editarPost(post) {
      this.isLoading = !this.isLoading;
      let params = {
        id: this.post.id,
        title: this.post.title,
        body: this.post.body,
      };

      axios
        .put(`api/posts/${params.id}`, params)
        .then((result) => {
          this.isLoading = !this.isLoading;
          this.isEditing = false;
          let index = this.list.findIndex((item) => item.id === result.data.id);

          this.list.splice(index, 1);
          this.list.unshift(result.data);
          this.post = { title: "", body: "" };
        })
        .catch((err) => {
          console.log("ERROR EDITAR_POST");
        });
    },
    scrollToTop() {
      window.scrollTo(0, 0);
    },
  },
};
</script>