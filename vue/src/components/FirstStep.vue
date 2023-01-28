<template>
  <div class="flex flex-col">
    <span class="text-2xl mb-8">Crea tu primer proyecto</span>
    <div class="flex justify-between sm:flex-col">
      <div class="flex flex-col w-[48%] sm:w-full">

       <!-- <div> placeholder ::: {{placeholder}}</div> -->
       <!-- <div> ubigeo ::: {{ubigeo}}</div> -->
        <!-- <Alert
          v-if="Object.keys(errors).length"
          class="flex-col items-stretch text-sm"
        >
          <div v-for="(field, i) of Object.keys(errors)" :key="i">
            <div v-for="(error, ind) of errors[field] || []" :key="ind">
              * {{ error }}
            </div>
          </div>
        </Alert> -->
        <input
          type="text"
          placeholder="Nombre del proyecto*"
          v-model="projectName"
          :errors="errors"
          class="h-[52px] w-full mb-4 border border-[#8A9CC9] rounded px-4"
        />

        <div class="autocompleteel h-[52px] w-full mb-4 border border-[#8A9CC9] rounded px-4 ">
	  		<div >
          <input type="hidden" v-model="business">
		  		<input type='text' @keyup='loadSuggestions' placeholder='Empresa'
		  			v-model='searchText'
      			>	<br>
		  		<div class="w-[106%] mx-[-3%] rounded bg-white border border-gray-300 px-4 py-2 space-y-1 relative z-50"
          v-if="suggestiondata.length"
          >
		  		<ul>
              <li
              class="px-1 pt-1 pb-2 font-bold border-b border-gray-200"
              >
              Mostrando {{ suggestiondata.length }} resultados
              </li>
              <li
              v-for= 'item in suggestiondata'
              v-bind:key="item.cod_Empresa"
              v-bind:value = "item.cod_Empresa"
              @click='itemSelected(item.cod_Empresa);'
              class="cursor-pointer hover:bg-gray-100 p-1"
              >
                {{ item.des_Empresa }}
              </li>
		  		</ul>
		  		</div>
	  		</div>
	  	</div>


        <input
          type="text"
          placeholder="Plazo"
          v-model="term"
          @keypress="onlyNumber"
          class="h-[52px] w-full mb-4 border border-[#8A9CC9] rounded px-4"
        />
        <div class="flex relative mb-4">
          <input
            type="text"
            placeholder="Área techada"
            v-model="coveredArea"
            @keypress="onlyNumber"
            class="h-[52px] w-full border border-[#8A9CC9] rounded pl-4 pr-[116px]"
          />
          <div
            class="flex absolute right-0 h-full justify-between items-center border-l border-[#8A9CC9] px-4"
          >
            <span class="font-normal text-base text-[#8A9CC9]">m2</span>
            <img
              src="../assets/images/icons/ic_arrow-down.svg"
              @click="handleClick('coveredArea')"
              class="transition"

              alt=""
            />
          </div>
        </div>

        <div class="flex relative mb-4">

          <select
          v-model="projectType"
          :errors="errors"
          class="h-[52px] w-full border border-[#8A9CC9] rounded pl-4 pr-[116px] without_icon"

          >
          <option disabled value="">Tipo de Proyecto</option>
          <option
          v-for="item in listaTiposproyectos" v-bind:key="item.codTipoProyecto" v-bind:value = "item.codTipoProyecto">
            {{ item.desTipoProyecto }}
          </option>

          </select>

          <img
            src="../assets/images/icons/ic_arrow-down.svg"
            alt=""
            class="absolute top-1/2 -translate-y-1/2 right-4 transition "
          />

        </div>


          <!-- <autocomplete
        v-model="ubigeo"

        :items="listaUbigeos"
        permitArbitraryValues
        :returned="'codUbigeo'"
        displayed="desUbigeo"
        :placeholder="placeholder"
        modelValue = ""
        class="h-[52px] w-full border border-[#8A9CC9] rounded pl-4 pr-[116px] mb-4 relative flex"
     /> -->

     <div class="autocompleteel h-[52px] w-full mb-4 border border-[#8A9CC9] rounded px-4  relative ">
	  		<div >
          <input type="hidden" v-model="ubigeo">
		  		<input
                 type='text'
                 placeholder='Ubicación'
		  			     v-model='searchTextUbigeo'
                 @keypress=" searchTextUbigeoFlg = 0"
      			>
          <br>
		  		<div

          v-if="loadSuggestionsUbigeo.length && searchTextUbigeoFlg == 0"
          class="w-[106%] mx-[-3%] rounded bg-white border border-gray-300 px-4 py-2 space-y-1 z-2"
          >
		  		<ul class="z-2">
              <li
              class="px-1 pt-1 pb-2 font-bold border-b border-gray-200"
              >
              Mostrando {{ loadSuggestionsUbigeo.length }} de {{ listaUbigeos.length }} resultados
              </li>

              <li
              v-for= 'item in loadSuggestionsUbigeo'
              v-bind:key="item.codUbigeo"
              v-bind:value = "item.codUbigeo"
              @click='funcionx(item.codUbigeo)'
              class="cursor-pointer hover:bg-gray-100 p-1"
              >
                {{ item.desUbigeo }}
              </li>
		  		</ul>
		  		</div>
	  		</div>
	  	</div>



        <!-- <div class="flex relative mb-4">
          <input
            type="text"
            placeholder="Distrito*"
            v-model="district"
            :errors="errors"
            @keypress="onlyNumber"
            class="h-[52px] w-full border border-[#8A9CC9] rounded px-4"
          />
          <img
            src="../assets/images/icons/ic_arrow-down.svg"
            @click="handleClick('district')"
            :class="districtStatus === true ? 'rotate-180' : ''"
            alt=""
            class="absolute top-1/2 -translate-y-1/2 right-4 transition"
          />
        </div> -->
      </div>
      <div class="flex flex-col w-[48%] sm:w-full">
        <v-date-picker v-model="startDate" mode="date" >
          <template v-slot="{ inputValue, inputEvents }" >
            <input
              class="h-[52px] w-full mb-4 border border-[#8A9CC9] rounded px-4"
              placeholder="Fecha de inicio*"
              :value="inputValue"
              v-on="inputEvents"
            />
          </template>
        </v-date-picker>

        <div class="flex relative mb-4  h-[52px]">
        <input
          type="number"
          placeholder="Monto referencial"
          v-model="referenceAmount"
          @keypress="onlyNumber"
          class="h-[52px] w-full mb-4 border border-[#8A9CC9] rounded px-4"
        />

          <select
          v-model="codMoneda"
          :errors="errors"
          class="flex absolute right-0 h-full justify-between items-center border-l border-[#8A9CC9] w-[80px] without_icon"

          >

          <option
          v-for="item in listaMonedas" v-bind:key="item.codMoneda" v-bind:value = "item.codMoneda">
            {{ item.desSimbolo }}
          </option>

          </select>


          <img
            src="../assets/images/icons/ic_arrow-down.svg"
            alt=""
            class="absolute top-1/2 -translate-y-1/2 right-4 transition "
          />



        </div>
    <div class="flex relative mb-4  h-[52px]">
     <input
          type="text"
          placeholder="Área construída"
          v-model="builtArea"
          @keypress="onlyNumber"
          class="h-[52px] w-full mb-4 border border-[#8A9CC9] rounded px-4"
        />
    <div
      class="flex absolute right-0 h-full justify-between items-center border-l border-[#8A9CC9] px-4"
    >
      <span class="font-normal text-base text-[#8A9CC9]">m2</span>
      <img
        src="../assets/images/icons/ic_arrow-down.svg"
        @click="handleClick('coveredArea')"

        alt=""
      />
    </div>
  </div>
        <input
          type="text"
          placeholder="País*"
          v-model="country"
          :errors="errors"
          readonly="readonly"

          class="h-[52px] w-full mb-4 border border-[#8A9CC9] rounded px-4"
        />
        <input
          type="text"
          placeholder="Dirección"
          v-model="address"
          class="h-[52px] w-full mb-4 border border-[#8A9CC9] rounded px-4"
        />



    </div>



    <!-- <span class="cursor-pointer" @click="openModal({param: 'newproject', id: 1})">Ver</span> -->


    <NewCompany
          v-if="modalName === 'newproject'"
          @closeModal   ="closeModal"
          @crearEmpresa = "datosProyecto"
          v-model="datapasar"

        />

    </div>
  </div>

</template>

<script>
// import autocomplete from 'vue-autocomplete-input-tag'
import NewCompany from './NewCompany.vue'
export default {
  name: "first-step-component",
  components: {
    // autocomplete,
    NewCompany
  },
  data: function () {
    return {
      searchTextUbigeo : "",
      searchTextUbigeoFlg:0,

      status_modal_np : false,
      rowId:0,
      modalName:"",
      datapasar: {},
      userid1: 0,
      searchText:'',
      suggestiondata:[],
      coveredAreaStatus: false,
      projectTypeStatus: false,
      districtStatus: false,
      projectName: "",
      // codEmpresa:"",
      business: "",
      term: "",
      coveredArea: "",
      projectType: "",
      ubigeo: "0",
      startDate: "",
      referenceAmount: "",
      area: "",
      builtArea: "",
      country: "Perú",
      address: "",
      codMoneda:"",
      placeholder:"",
      listaTiposproyectos: [],
      listaUbigeos:[],
      listaMonedas:[],
      attributes: [
        {
          key: 'today',
          // highlight: '##002B6B',
          highlight: true,
          dates: new Date(),
        },
      ],
      errors: {},
      test: {},
      items: [
      ],
    };
  },
  methods: {

    openModal: function (param) {
      if (typeof param !== "string") {
        this.rowId = param.id;
        param = param.param;
      }
      this.modalName = param;
    },
    closeModal: function () {
      this.searchText = ""
      this.business   = ""
      if (this.modalName === "") this.$store.commit("increaseHint");
      else this.modalName = "";
    },
    datosProyecto: function (datos) {

      this.$store.dispatch('save_newempresa', datos)
          .then((response) => {

            this.modalName      = "";
            this.suggestiondata = [];

            if (response["flag"] == 1){

              this.business   = response["registro"]["cod_Empresa"];
              this.searchText = response["registro"]["des_Empresa"];

            }

      });

    },

    loadSuggestions: function(e){
				var el = this;
				this.suggestiondata = [];

				if(this.searchText != ''){
          var enviamos = { buscar : this.searchText }
          this.$store.dispatch('get_buscar', enviamos)
          .then((response) => {
            let data = []
            //console.log(response)
            for (let index = 0; index < response.length; index++) {
              data.push({cod_Empresa:response[index]["cod_Empresa"], des_Empresa: response[index]["des_Empresa"]})
            }
            data.push({cod_Empresa:-999, des_Empresa: '+ Agregar Nueva Empresa'})
            el.suggestiondata  = data
          })

				}

			},
    itemSelectedUbigeo: function(id){
        let buscar            = [id]
        let name              = buscar.map((id) => (this.listaUbigeos.find(x => x.codUbigeo == id).desUbigeo));
        this.searchTextUbigeo = name
        this.ubigeo           = id
    },
		itemSelected: function(id){
        console.log(id)
				var id   = id
				//var name = this.suggestiondata[index].des_Empresa;
        let buscar = [id]
        let name   = buscar.map((id) => (this.suggestiondata.find(x => x.cod_Empresa == id).des_Empresa));

				this.searchText     = name;
				this.suggestiondata = [];
        this.business       = id
        // con esto abrimos el modal de agregar empresa
        if(id == -999){

          this.modalName      = 'newproject';

        }


		},
    handleClick: function(param) {
      if (param === 'coveredArea') this.coveredAreaStatus = !this.coveredAreaStatus;
      else if (param === 'projectType') this.projectTypeStatus = !this.projectTypeStatus;
      else if (param === 'district') this.districtStatus = !this.districtStatus;
    },
    onlyNumber: function ($event) {
      //console.log($event.keyCode); //keyCodes value
      let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
      if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) { // 46 is dot
          $event.preventDefault();
      }
    },

    funcionx: function (cod){
      console.log(cod)

        let buscar               = [cod]
        let name                 = buscar.map((id) => (this.listaUbigeos.find(x => x.codUbigeo == id).desUbigeo));
        // console.log(name[0])
        this.searchTextUbigeo    = name[0]
        this.ubigeo              = cod
        this.searchTextUbigeoFlg = 1

        //console.log("Si se realiza y si cambia ::"+this.searchTextUbigeoFlg.toString())

    },


  },
  computed: {


    loadSuggestionsUbigeo(e){
     let matches              = 0
    //  this.searchTextUbigeoFlg = 0
     console.log("entrando al final :"+this.searchTextUbigeoFlg.toString())

     if (this.searchTextUbigeo == '') {
             return []
     }
     return this.listaUbigeos.filter(ubigeo => {
      if (
             ubigeo.desUbigeo.toLowerCase().includes(this.searchTextUbigeo.toLowerCase())
             &&
             matches < 10
         ){
             matches++
             return ubigeo
          }

     });

    },

  },



};
</script>

<style>

 .ocultar{
      display: none;
 }

 .without_icon {

      background-image: none !important
 }

  .autocompleteel,.autocompleteel{
      width: 100%;


  }

  .autocompleteel input[type=text]{
      width: 105%;
      border: none;
      height: 3em;
      margin-left: -1em;

  }


</style>
