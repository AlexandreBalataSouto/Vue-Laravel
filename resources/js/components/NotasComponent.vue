<template>
  <div class="row">
    <div class="col-md-12">
      <form @submit.prevent="editarNota(nota)" v-if="isEditing">
        <h3>Editar notas</h3>
        <input type="text" placeholder="Nombre" class="form-control mb-2" v-model="nota.nombre" />
        <input
          type="text"
          placeholder="Descripcion"
          class="form-control mb-2"
          v-model="nota.descripcion"
        />
        <button class="btn btn-warning" type="submit">Editar</button>
      </form>

      <form @submit.prevent="agregar" v-else>
        <h3>Agregar notas</h3>
        <input type="text" placeholder="Nombre" class="form-control mb-2" v-model="nota.nombre" />
        <input
          type="text"
          placeholder="Descripcion"
          class="form-control mb-2"
          v-model="nota.descripcion"
        />
        <button class="btn btn-primary" type="submit">Agregar</button>
      </form>

      <hr class="mt-3" />

      <h3>Listado de notas</h3>
      <ul class="list-group my-2">
        <li class="list-group-item" v-for="(item, index) in notas" :key="index">
            <span class="badge float-right" 
            v-bind:class="[moment(item.updated_at).locale('es').format('L') >=  moment().locale('es').format('L') ? 'badge-primary' : 'badge-secondary']">
            {{moment(item.updated_at).locale('es').format('L')}}
            </span>
          <p>{{item.nombre}}</p>
          <p>{{item.descripcion}}</p>
          <button class="btn btn-danger btn-sm" @click="eliminar(item, index)">Eliminar</button>
          <button class="btn btn-warning btn-sm" @click="editar(item)">Editar</button>
        </li>
      </ul>

      <infiniteLoading @infinite="infiniteHandler">
        <div slot="no-more">---</div>
        <div slot="no-result">Sin resultados</div>
      </infiniteLoading>

    </div>
  </div>
</template>

<script>
var moment = require("moment");

export default {
  data() {
    return {
      moment: moment,
      notas: [],
      nota: { nombre: "", descripcion: "" },
      isEditing: false,
      page: 0
    };
  },
  methods: {
    infiniteHandler($state) {
      this.page++;
      
      axios
        .get(`/api/notas?page=${this.page}`)
        .then((result) => {
          let posts = result.data.data;

          if (posts.length) {
            this.notas = this.notas.concat(posts);
            $state.loaded();
          } else {
            $state.complete();
          }
        })
        .catch((err) => {
          console.log("ERROR AXIOS GET");
        });
    },
    agregar() {
      if (
        this.nota.nombre.trim() === "" ||
        this.nota.descripcion.trim() === ""
      ) {
        alert("Debes completar todos los campos antes de guardar");
        return;
      }

      let params = {
        nombre: this.nota.nombre,
        descripcion: this.nota.descripcion,
      };
      this.nota.nombre = "";
      this.nota.descripcion = "";

      axios
        .post("/api/notas", params)
        .then((result) => {
          this.notas.unshift(result.data);
        })
        .catch((err) => {
          console.log("ERROR AGREGAR");
        });
    },
    eliminar(item, index) {
      axios
        .delete(`/api/notas/${item.id}`)
        .then(() => {
          this.notas.splice(index, 1);
        })
        .catch((err) => {
          console.log("ERROR ELIMINAR");
        });
    },
    editar(item) {
      this.isEditing = true;
      this.nota.nombre = item.nombre;
      this.nota.descripcion = item.descripcion;
      this.nota.id = item.id;
    },
    editarNota(nota) {
      let params = {
        id: this.nota.id,
        nombre: this.nota.nombre,
        descripcion: this.nota.descripcion,
      };

      axios
        .put(`api/notas/${params.id}`, params)
        .then((result) => {
          this.isEditing = false;
          let index = this.notas.findIndex(
            (item) => item.id === result.data.id
          );

          this.notas[index] = result.data;
          this.nota = { nombre: "", descripcion: "" };
        })
        .catch((err) => {
          console.log("ERROR EDITAR_NOTA");
        });
    },
  },
};
</script>