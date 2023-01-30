<template>
  <Breadcrumb
  :paths="['Inicio']"
   />
  <div>Bienvenidos</div>
  <slot></slot>
</template>

<script>

import store from "../store";
import Breadcrumb from "../components/Layout/Breadcrumb.vue";
export default {
  name: "white-project-component",
  components: {
    Breadcrumb,
  },
  data: function () {

  },
  methods: {

  },
  mounted: function() {

   store.dispatch('get_infoPerson');

  }
}



// import { computed, ref } from "vue";
// import { useRoute } from "vue-router";
// import { useStore } from "vuex";

// const route = useRoute();
// const store = useStore();
// if (window.localStorage) {

//   if (!localStorage.getItem('reload')) {
//       localStorage['reload'] = true;
//       window.location.reload();
//   } else {
//       localStorage.removeItem('reload');
//   }
// }
/* store.dispatch('get_project')
.catch((error) => {
  console.log(error)
}); */
</script>
