<template>
  <Modal
    :header="'Tipo de restricción'"
    :paragraphs="paragraphs"
  >
    <div v-for="i in items" class="flex mb-4 relative justify-space">
          <h1 class=" m-4">restricción</h1>
          <div class="relative">
            <input
              type="text"
              v-model=i.value
              class="h-[52px] w-full px-4 rounded border border-[#8A9CC9] font-normal text-base text-activeText"
              placeholder="Selecciona un frente"
            />
          </div>
      </div>
      <div class="flex cursor-pointer mb-8" @click="addRow">
          <img
            src="../assets/images/icons/tooltip-person-add-active.svg"
            class="mr-2"
            alt=""
          />
          <span class="text-base leading-4 text-orange">Agregar miembro</span>
        </div>
      <div class="flex justify-between">
        <button
        class="h-14 sm:w-full rounded px-8 text-base leading-4 m-10 bg-orange text-white"
        @click="$emit('addRestriction', items)"
      >
        Ok
      </button>
      <button
        class="h-14 sm:w-full rounded px-8 text-base leading-4 mt-10 bg-orange text-white"
        @click="$emit('closemodal')"
      >
        Cancel
      </button>
      </div>
      
  </Modal>
</template>

<script>
import Modal from "./Modal.vue";

export default {
  name: "add-front-component",
  components: {
    Modal,  
  },
  props: {
    rows: Array,
  },
  data: function () {
    return { 
      frontName: '',
      isOpen: false,
      items:[
        { value: ""}
      ]
    };
  },
  methods: {
    handleClick: function () {
      this.isOpen = !this.isOpen;
    },
    addRow: function () {
      this.items.push({value:""})
    }
  },
};
</script>
