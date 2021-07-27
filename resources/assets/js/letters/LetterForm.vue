<template>
  <div>
    <div class="row">
      <div class="col-md-6">
<!--        <label for="group">Type</label>-->
        <div class="form-group">
          <v-select :options="groups" label="name" v-model="group" placeholder="Select Type"
                    class="selection-list bg-white" @change="getSubGroups"></v-select>
<!--          <select name="group" id="group" @change="getSubGroups" v-model="group" class="select2">-->
<!--            <option value="" disabled>Letter Type</option>-->
<!--            <option v-for="group in groups" :value="group.id"> {{ group.name }}</option>-->
<!--          </select>-->
        </div>
      </div>
    </div>
    <br>
    <div class="row" v-if="subgroups.length">
      <div class="col-md-6">
        <label for="subgroup">SubType</label>
        <v-select id="subgroup" name="subgroups" :options="subgroups" label="name" v-model="subgroup" placeholder="Letter subtype"
                  class="selection-list bg-white" ></v-select>

      </div>
    </div>
    <br>
    <div class="row" v-if="letters.length">
      <div class="col-md-6">
        <label for="letters">Letter</label>
        <v-select id="letters" name="letters" :options="letters" label="name" v-model="letter" placeholder="Select Letter"
                  class="selection-list bg-white" ></v-select>
      </div>
    </div>
    <br>
  </div>
</template>

<script>
import 'vue-select/dist/vue-select.css';
import vSelect from "vue-select";

export default {
  name: "LetterForm",
  props: ['item', 'groups'],
  components: {vSelect},

  data() {
    return {
      group: '',
      subgroup: '',
      letter: '',
      group_id: '',
      subgroup_id: '',
      letter_id: '',
      //
      subgroups: [],
      letters: [],
    }
  },
  watch:{
    group(){
      this.getSubGroups();
      this.group_id = this.group.id;
    },
    subgroup(){
      this.getLetters();
      this.subgroup_id = this.subgroup.id;
    }
  },

  methods: {
    getSubGroups() {
      this.subgroup = '';
      this.subgroups = [];
      this.letters = [];

      axios.get(`/letters/list/subgroups/${this.group.id}`).then((response) => {
        if (!response.data.length) {
          this.getLetters();
        } else {
          this.subgroups = response.data;
        }

      });
    },
    getLetters() {
      this.group_id = this.subgroups.length ? this.subgroup.id : this.group.id;

      axios.get(`/letters/list/letters?group_id=${this.group_id}`).then((response) => {
        this.letters = response.data;
      });
    }
  }
}
</script>

<style scoped>
.selection-list {
  width: 500px !important;
}
</style>