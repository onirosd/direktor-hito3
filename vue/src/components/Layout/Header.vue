<template>
  <div class=" fixed top-0 left-0 right-0 flex justify-between items-center h-16 sm:h-14 bg-main pl-8 z-10">
    <img src="../../assets/images/logo.png" class="sm:content-phoneLogo" />
    <ul class="h-full flex items-center sm:hidden">
      <li
        v-for="route in routes"
        :key="route.id"
        class="h-full flex items-center mx-4 text-white text-lg font-medium cursor-pointer border-y-[6px] border-y-transparent"
        @click="handleRedirect(route.path)"
        :class="{ 'font-semibold border-b-[6px] border-b-orange': route.path === currentPath }"
      >
        {{ route.label }}
      </li>
    </ul>



    <div  class="h-full flex items-center bg-main hover:bg-[#001E4A] ... cursor-pointer" @click="updateisOpen" >
      <div class="flex items-center px-8 sm:px-3 ">
        <span class="text-base font-medium text-white sm:hidden">Hola, {{username}} </span>
        <!-- <img src="../../assets/images/icons/bell.svg" class="mx-4 sm:mx-6 sm:content-phoneBell" alt="" /> -->
        <!-- <img
          src="../../assets/images/user.png"
          class="w-8 h-8 rounded-full sm:mr-6"
          alt=""
        /> -->
        <img src="../../assets/images/icons/phone-search.svg" class="hidden sm:block" alt="">
      </div>
      <TableTooltipPerson v-if="isOpenUser" @selecPersonInfo="selecPersonInfo" />

      <button class="w-16 sm:w-14 h-full flex items-center justify-center bg-[#001E4A]">
        <img src="../../assets/images/icons/bell.svg" alt="" class="sm:hidden" />
        <img src="../../assets/images/icons/phone-menu.svg" alt="" class="hidden sm:block" v-if="!menu" @click="toggleMenu" />
        <img src="../../assets/images/icons/close.svg" alt="" class="hidden sm:block" v-if="menu" @click="toggleMenu" />
      </button>
    </div>



  </div>
</template>

<script>
import TableTooltipPerson from "../TableTooltipPerson.vue";

export default {
  name: "header-component",
  components: {
    TableTooltipPerson,
  },
  props: {
    username: String
  },
  data: function () {
    return {
      isOpenUser: false,
      routes: [
        {
          id: "operations",
          label: "Operaciones",
          path: "/",
        },
        {
          id: "managements",
          label: "Administración",
          path: "/managements",
        },
        {
          id: "security",
          label: "Seguridad",
          path: "/security",
        },
        {
          id: "quality",
          label: "Calidad",
          path: "/quality",
        },
        {
          id: "logistics",
          label: "Logística",
          path: "/logistics",
        },
      ],
    };
  },
  methods: {
    hideUser: function () {
      this.isOpenUser = false;
    },
    openModalHeader: function (payload) {
      console.log(payload)
    //   this.hide()
    //   this.$emit('openModal', { param: payload.param, frontId: this.frontId, phaseId: this.phaseId, exercise: this.restriction_data.codAnaResActividad });
    },

    selecPersonInfo: function (index) {

      this.$router.push({
        name: 'person'
      })

      console.log(index)
      // this.updateisOpen()
      // if (index === 'option') {
      //   this.isoptions = !this.isoptions;
      // } else
      //   this.isOpen = !this.isOpen;
    },
    updateisOpen: function (){
      this.isOpenUser = !this.isOpenUser;
    },
    handleRedirect(path) {
      this.$router.push(path);
    },
    toggleMenu: function() {
      this.$store.commit('toggleMenu');
    }
  },
  computed: {
    currentPath() {
      return this.$route.path;
    },
    menu: function() {
      return this.$store.state.menu;
    }
  },
};
</script>
