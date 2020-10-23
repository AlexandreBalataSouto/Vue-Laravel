<template>
  <div class="row">
    <div class="col-md-12">
      <button class="btn btn-secondary btn-block" @click="agregar()" v-if="!isAdding && !isEditing">Nuevo Evento</button>
    </div>
    <div class="col-md-12 mt-2">
      <form @submit.prevent="editarEvento(newEvent)" v-if="isEditing">
        <h3>Editar evento</h3>
        <strong>Titulo</strong>
        <input type="text" class="form-control mb-2" v-model="newEvent.title" />
        <strong>Fecha inicio</strong>
        <input type="date" class="form-control mb-2" v-model="newEvent.start" />
        <strong>Fecha fin</strong>
        <input type="date" class="form-control mb-2" v-model="newEvent.end" />
        <strong>Color</strong>
        <input type="color" class="form-control mb-2" v-model="newEvent.color" />

        <button class="btn btn-warning" type="submit">Editar</button>
        <button class="btn btn-danger" type="button" @click="eliminarEvento(newEvent.id)">Borrar</button>
      </form>

      <form @submit.prevent="agregarEvento" v-if="isAdding">
        <h3>Agregar evento</h3>
        <strong>Titulo</strong>
        <input type="text" class="form-control mb-2" v-model="newEvent.title" />
        <strong>Fecha inicio</strong>
        <input type="date" class="form-control mb-2" v-model="newEvent.start" />
        <strong>Fecha fin</strong>
        <input type="date" class="form-control mb-2" v-model="newEvent.end" />
        <strong>Color</strong>
        <input type="color" class="form-control mb-2" v-model="newEvent.color" />

        <button class="btn btn-primary" type="submit">Agregar</button>
      </form>

      <hr class="mt-3" />

      <FullCalendar :options="calendarOptions" />

      <loading v-if="isLoading"></loading>
    </div>
  </div>
</template>

<script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";

var moment = require("moment");

export default {
  components: {
    FullCalendar, // make the <FullCalendar> tag available
  },
  data() {
    return {
      moment: moment,
      calendarOptions: {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: "dayGridMonth",
        dateClick: this.handleDateClick,
        eventClick: this.handleEventClick,
        eventDrop: this.handleEventDrop,
        eventResize: this.handleEventResize,
        events: [],
        locale: "es",
        editable: true,
        selectable: true,
        height: "auto",
        contentHeight: "auto",
        firstDay: 1,
        buttonText: {
          today: "Hoy",
        },
      },
      newEvent: { title: "", start: "", end: "", color: "" },
      isAdding : false,
      isEditing: false,
      isLoading: false,
    };
  },
  created() {
    this.getEventos();
  },
  methods: {
    handleDateClick: function (arg) {
      alert("Fecha: " + arg.dateStr);
    },
    handleEventClick(clickInfo) {
      this.editar(clickInfo.event);
    },
    handleEventDrop(info) {
      let param = {
        id: info.event.id,
        title: info.event.title,
        start: moment(info.event.start).format("yyyy-MM-DD"),
        end: moment(info.event.end).isValid()
          ? moment(info.event.end).format("yyyy-MM-DD")
          : moment(info.event.start).format("yyyy-MM-DD"),
        color: info.event.color,
      };
      this.editarEvento(param);
    },
    handleEventResize(info) {
      let param = {
        id: info.event.id,
        title: info.event.title,
        start: moment(info.event.start).format("yyyy-MM-DD"),
        end: moment(info.event.end).isValid()
          ? moment(info.event.end).format("yyyy-MM-DD")
          : moment(info.event.start).format("yyyy-MM-DD"),
        color: info.event.color,
      };
      this.editarEvento(param);
    },
    getEventos() {
      this.isLoading = !this.isLoading;
      axios
        .get(`/api/eventos`)
        .then((result) => {
          this.isLoading = !this.isLoading;
          if (result != "") {
            this.calendarOptions.events = result.data;
          }
        })
        .catch((err) => {
          this.isLoading = !this.isLoading;
          console.log("ERROR AXIOS GET");
          console.log(err);
        });
    },
    agregar(){
      this.isAdding = true;
      this.scrollToTop();
    },
    agregarEvento() {
      if (
        this.newEvent.title.trim() === "" ||
        this.newEvent.start.trim() === ""
      ) {
        alert("Debes completar como minimo el titulo y la fecha inicial");
        return;
      }
      this.isLoading = !this.isLoading;

      let params = {
        title: this.newEvent.title,
        start: this.newEvent.start,
        end: this.newEvent.end,
        color:
          this.newEvent.color.trim() === "" ? "#000000" : this.newEvent.color,
      };

      this.newEvent.title = "";
      this.newEvent.start = "";
      this.newEvent.end = "";
      this.newEvent.color = "";

      axios
        .post("/api/eventos", params)
        .then((result) => {
          this.isAdding = false;
          this.isLoading = !this.isLoading;
          this.getEventos();
        })
        .catch((err) => {
          this.isLoading = !this.isLoading;
          console.log("ERROR AGREGAR");
        });
    },
    editar(item) {
      this.isEditing = true;
      this.newEvent.title = item.title;
      this.newEvent.start = moment(item.start).format("yyyy-MM-DD");
      this.newEvent.end = moment(item.end).format("yyyy-MM-DD");
      this.newEvent.color = item.backgroundColor;
      this.newEvent.id = item.id;

      this.scrollToTop();
    },
    editarEvento(event) {
      this.isLoading = !this.isLoading;

      let params = {
        id: event.id,
        title: event.title,
        start: event.start,
        end: moment(event.end).isValid() ? event.end : event.start,
        color: event.color,
      };

      this.newEvent.title = "";
      this.newEvent.start = "";
      this.newEvent.end = "";
      this.newEvent.color = "";

      axios
        .put(`api/eventos/${params.id}`, params)
        .then((result) => {
          this.isEditing = false;
          this.isLoading = !this.isLoading;
          this.getEventos();
        })
        .catch((err) => {
          this.isLoading = !this.isLoading;
          console.log("ERROR EDITAR_EVENTO");
        });
    },
    eliminarEvento(id) {
      this.isLoading = !this.isLoading;

      this.newEvent.title = "";
      this.newEvent.start = "";
      this.newEvent.end = "";
      this.newEvent.color = "";

      axios
        .delete(`/api/eventos/${id}`)
        .then((result) => {
          this.isEditing = false;
          this.isLoading = !this.isLoading;
          this.getEventos();
        })
        .catch((err) => {
          this.isLoading = !this.isLoading;
          console.log("ERROR AGREGAR");
        });
    },
    scrollToTop() {
      window.scrollTo(0, 0);
    },
  },
};
</script>

