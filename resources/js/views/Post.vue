<template>
  <div>
    <section class="jumbotron text-center">
      <div class="container">
        <h1 class="jumbotron-heading" v-text="post.title"></h1>
      </div>
    </section>

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <p v-text="post.exc"></p>
          <div v-html="post.body"></div>
          <hr />
          <h3>Otros articulos</h3>
          <posts></posts>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["slug"],
  data() {
    return {
      post: {},
    };
  },
  created() {
    
    axios
      .get(`api/post/${this.slug}`)
      .then((result) => {
        this.post = result.data;
      })
      .catch((err) => {
        console.log("ERROR AXIOS CREATED");
      });
  },
};
</script>