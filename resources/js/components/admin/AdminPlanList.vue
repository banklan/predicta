<template>
    <v-container>
        <v-row class="mt-4">
            <v-col cols="4" md="4" offset-md=8>
                <v-btn dark color="primary" @click="newPlanDial = true"><v-icon left>add</v-icon>New Plan</v-btn>
            </v-col>
        </v-row>
        <v-row class="mt-4 ml-n8">
            <v-col cols="12" md="8">
                <v-progress-circular indeterminate color="primary" :width="7" :size="70" v-if="isLoading" justify="center" class="mx-auto"></v-progress-circular>
                <v-card v-else light raised elevation="8" min-height="200" class="scroll">
                    <v-card-title class="subtitle-1 primary white--text justify-center">Plans <span class="ml-2"><v-chip color="primary lighten-2" v-if="plans.length > 0">{{ plans.length }}</v-chip></span></v-card-title>
                    <v-card-text>
                        <template v-if="plans.length > 0">
                            <table class="table table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Odd</th>
                                        <th>Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(plan, i) in plans" :key="plan.id">
                                        <td>{{ plan.id }}</td>
                                        <td>{{ plan.odd }}</td>
                                        <td>{{ plan.price / 100 | price }}</td>
                                        <td><v-btn small text color="primary" @click="openUpdate(plan)"><v-icon>edit</v-icon></v-btn> &nbsp; <v-btn small text color="red darken-2" @click="confirmDel(plan, i)"><v-icon>delete_forever</v-icon></v-btn></td>
                                    </tr>
                                </tbody>
                            </table>
                        </template>
                        <template v-else>
                            <v-alert type="info" border="left" class="mt-5">
                                There are currently no plans in the database.
                            </v-alert>
                        </template>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-dialog v-model="updateDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title primary white--text justify-center">Update Plan</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                    <v-text-field label="plan (Odd)" v-model="edit.odd" required v-validate="'required|integer'" :error-messages="errors.collect('plan')" name="plan"></v-text-field>
                    <v-text-field label="price" v-model="edit.price" required v-validate="'required|integer'" :error-messages="errors.collect('price')" name="price"></v-text-field>
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="updateDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="updatePlan" width="50%">Update</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="confirmDelDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title justify-center primary darken-2 white--text">Delete this plan?</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                   Are you sure you want to delete this plan from the database? It is an irrecoverable action.
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="confirmDelDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="delPlan" width="50%">Yes, Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="newPlanDial" max-width="480">
            <v-card min-height="150">
                <v-card-title class="sub_title primary white--text justify-center">Add New Plan</v-card-title>
                <v-card-text class="text-center mt-5 subtitle-1">
                    <v-text-field label="Plan(Odd)" v-model="newPlan.odd" required v-validate="'required|integer'" :error-messages="errors.collect('new.plan')" data-vv-scope='new' name="plan"></v-text-field>
                    <v-text-field label="Price" v-model="newPlan.price" required v-validate="'required|integer'" :error-messages="errors.collect('new.price')" data-vv-scope='new' name="price"></v-text-field>
                </v-card-text>
                <v-card-actions class="pb-8 justify-center">
                    <v-btn text color="red darken--2" @click="newPlanDial = false" width="30%">Cancel</v-btn>
                    <v-btn dark color="primary" :loading="isUpdating" @click="createPlan" width="50%">Submit</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-snackbar v-model="newPlanAdded" :timeout="4000" top dark color="green darken-2">
            New plan has been created.
            <v-btn text color="white--text" @click="newPlanAdded = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="updateSuccess" :timeout="4000" top dark color="green darken-2">
            A plan was updated successfully.
            <v-btn text color="white--text" @click="updateSuccess = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="updateFailed" :timeout="6000" top dark color="red darken-2">
            An error occured while updating. Please fill all the fields and try again.
            <v-btn text color="white--text" @click="updateFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="planDeleted" :timeout="4000" top dark color="green darken-2">
            A plan has been deleted.
            <v-btn text color="white--text" @click="planDeleted = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="planDeleteFailed" :timeout="6000" top dark color="red darken-2">
            An error occured while deleting. Please try again.
            <v-btn text color="white--text" @click="planDeleteFailed = false">Close</v-btn>
        </v-snackbar>
        <v-snackbar v-model="createPlanFail" :timeout="6000" top dark color="red darken-2">
            An error occured while creating a new plan. Please ensure the odd doesnt exist before trying again.
          <v-btn text color="white--text" @click="createPlanFail = false">Close</v-btn>
        </v-snackbar>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            plans: [],
            isLoading: false,
            confirmDelDial: false,
            PlanTodel: null,
            PlanTodelIndex: null,
            isDeleting: false,
            planDeleted: false,
            planDeleteFailed: false,
            updateDial: false,
            planToUpdt: null,
            edit: {
                odd: null,
                price: null
            },
            isUpdating: false,
            updateSuccess: false,
            updateFailed: false,
            newPlanDial: false,
            newPlan: {
                odd: null,
                price: null,
            },
            newPlanAdded: false,
            createPlanFail: false
        }
    },
    computed:{
         authAdmin(){
            return this.$store.getters.authAdmin
        },
        api(){
            return this.$store.getters.api
        },
        adminHeaders(){
            let headers = {
                headers: {
                    "Authorization": `Bearer ${this.authAdmin.token}`
                }
            }
            return headers
        },
    },
    beforeRouteLeave (to, from, next) {
        this.$store.commit('resetAdminUpdatedFlashMsgs')
        next()
    },
    methods: {
        getPlans(){
            this.isLoading = true
            axios.get(this.api + '/auth-admin/get_all_plans', this.adminHeaders)
            .then((res) => {
                this.isLoading = false
                this.plans = res.data
            }).catch(() => {
                this.isLoading = false
            })
        },
        confirmDel(plan, i){
            this.planTodel = plan
            this.planTodelIndex = i
            this.confirmDelDial = true
        },
        delPlan(){
            this.isUpdating = true
            axios.post(this.api + `/auth-admin/admin_delete_plan/${this.planTodel.id}`, {}, this.adminHeaders)
            .then((res)=>{
                this.isUpdating = false
                this.confirmDelDial = false
                this.plans.splice(this.planTodelIndex, 1)
                this.planDeleted = true
            }).catch(()=>{
                this.isUpdating = false
                this.planDeleteFailed = true
            })
        },
        openUpdate(plan){
            this.updateDial = true
            this.planToUpdt =  plan
            this.edit.odd = plan.odd
            this.edit.price = plan.price / 100
        },
        updatePlan(){
            this.$validator.validateAll().then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    axios.post(this.api + `/auth-admin/admin_update_plan/${this.planToUpdt.id}`, {
                        plan: this.edit,
                    }, this.adminHeaders).then((res)=>{
                        this.isUpdating = false
                        this.updateDial = false
                        let plan = this.plans.find((item) => item.id === this.planToUpdt.id)
                        plan.odd = res.data.odd
                        plan.price = res.data.price
                        this.updateSuccess = true
                    }).catch((err)=>{
                        this.isUpdating = false
                        this.updateFailed = true
                    })
                }
            })
        },
        createPlan(){
            this.$validator.validateAll('new').then((isValid) => {
                if(isValid) {
                    this.isUpdating = true
                    axios.post(this.api + '/auth-admin/admin_create_plan', {
                        plan: this.newPlan
                    }, this.adminHeaders).then((res)=>{
                        this.isUpdating = false
                        this.plans.unshift(res.data)
                        this.newPlanAdded = true
                        this.newPlanDial = false
                        this.newPlan = {}
                        this.clearValidators()
                    }).catch(()=>{
                        this.isUpdating = false
                        this.newPlan = {}
                        this.clearValidators()
                        this.newPlanDial = false
                        this.createPlanFail = true
                    })
                }
            })
        },
        clearValidators(){
            this.$validator.pause()
            this.$validator.errors.clear()
            this.$validator.fields.items.forEach(field => field.reset())
        }
    },
    created(){
        this.getPlans()
    }
}
</script>

<style lang="css" scoped>
    table .table_list tr td{
        cursor: pointer;
    }
    .v-card.scroll .v-card__text{
        overflow-x: scroll !important;
    }
</style>
