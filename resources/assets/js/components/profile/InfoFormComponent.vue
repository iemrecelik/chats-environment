<template>
  <form method="POST" :action="routes.updateProfile">
    
    <succeed-msg-component :ppcomponent="'infoFormComp'">
    </succeed-msg-component>
    <error-msg-list-component :ppcomponent="'infoFormComp'">
    </error-msg-list-component>

    <error-msg-component v-if="isActiveTab()" :ppfieldname="'name'">
    </error-msg-component>
    <div class="form-group row">
      <label for="inputName" class="col-sm-2 col-form-label">Name</label>
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm"  
        id="inputName" name="name" placeholder="Name"
          :value="inputVal('name')"
        />
      </div>
    </div>
    
    <error-msg-component v-if="isActiveTab()" :ppfieldname="'surname'">
    </error-msg-component>
    <div class="form-group row">
      <label for="inputSurname" class="col-sm-2 col-form-label">Surname</label>
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" id="inputSurname" name="surname" placeholder="Surname"
          :value="inputVal('surname')"
        />
      </div>
    </div>
    
    <error-msg-component v-if="isActiveTab()" :ppfieldname="'nickname'">
    </error-msg-component>
    <div class="form-group row">
      <label for="inputNickname" class="col-sm-2 col-form-label">Nickname</label>
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" 
        id="inputNickname" name="nickname" placeholder="Nickname"
          :value="inputVal('nickname')"
        />
      </div>
    </div>
    
    <error-msg-component v-if="isActiveTab()" :ppfieldname="'brief'">
    </error-msg-component>
    <div class="form-group row">
      <label for="inputBrief" class="col-sm-2 col-form-label">Brief</label>
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" 
        id="inputBrief" name="brief" placeholder="Brief"
          :value="inputVal('brief')"
        />
      </div>
    </div>
    
    <error-msg-component v-if="isActiveTab()" :ppfieldname="'email'">
    </error-msg-component>
    <div class="form-group row">
      <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" 
        id="inputEmail" name="email" placeholder="Email"
          :value="inputVal('email')"
        />
      </div>
    </div>
    
    <error-msg-component v-if="isActiveTab()" :ppfieldname="'mobile'">
    </error-msg-component>
    <div class="form-group row">
      <label for="inputMobile" class="col-sm-2 col-form-label">Mobile</label>
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" 
        id="inputMobile" name="mobile" placeholder="Mobile"
          :value="inputVal('mobile')"
        />
      </div>
    </div>
    
    <error-msg-component v-if="isActiveTab()" :ppfieldname="'password'">
    </error-msg-component>
    <div class="form-group row">
      <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" 
        id="inputPassword" name="password" placeholder="Password">
      </div>
    </div>
    
    <error-msg-component v-if="isActiveTab()" :ppfieldname="'hideAccount'">
    </error-msg-component>
    <div class="form-group row">
      <label class="col-sm-2 col-form-label"></label>
      <div class="col-sm-10">        
        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" 
          id="checkHideAccount" name="hide_account"
            :checked="inputVal('hide_account') > 0"
          />
          <label class="custom-control-label" for="checkHideAccount">
            Hide account info (Hesap bilgilerini gizle)
          </label>
        </div>
      </div>
    </div>
    
    <input type="hidden" name="_token" :value="token"/>

    <div class="form-group row">
      <label class="col-sm-2 col-form-label"></label>
      <div class="col-sm-10">
        <button type="submit" class="btn btn-sm btn-primary profile-update">
          Update
        </button>
      </div>
    </div>
  </form>
</template>

<script>
import { mapState } from 'vuex';

export default {
  name: 'InfoFormComponent',
  computed: {
    ...mapState([
      'routes',
      'token',
      'old',
      'authUser',
    ]),
  },
  methods: {
    inputVal: function (name) {
      return this.old[name] || this.authUser[name];
    },
    isActiveTab: function(){
      return this.$store.state.chats.activeTab === 'settings'
    }
  },
}
</script>