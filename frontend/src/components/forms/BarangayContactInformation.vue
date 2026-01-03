<script setup>
import Separator from '../ui/separator/Separator.vue';
import { Info } from 'lucide-vue-next';
import {
  Field,
  FieldDescription,
  FieldGroup,
  FieldLabel,
  FieldSeparator,
} from '@/components/ui/field'
import Input from '../ui/input/Input.vue';
import Button from '../ui/button/Button.vue';
import { reactive } from 'vue';


const formData = reactive({
    contactPerson: 'Juan Dela Cruz',
    contactNumber: '(053) 123-4567',
    officialEmail: 'barangay1@tacloban.gov.ph'
})




</script>





<template>
    <div class="bg-white  rounded-md  shadow-sm p-4">
        <div class="py-4">
            <h1 class="font-semibold text-xl border-s-4 pl-2 border-green-200">Contact  Information</h1>
           
        </div>
        <Separator/>
        <form action="" @submit.prevent="console.log(formData)" class="mt-4">
            <div class="h-15 bg-blue-400/20 rounded-md flex items-center  p-4 gap-2 text-blue-500">
                <Info />
                <p> You can only edit contact information. Other barangay details are managed by LGU administrators.</p>
            </div>

            <!-- editable fields -->
            <div class="mt-4">
                <FieldGroup>
                   <div class="grid gird-cols-1 sm:grid-cols-2 gap-4">  
                        <Field>
                            <FieldLabel for="contact_person">
                            Contact Person
                            </FieldLabel>
                            <Input id="contact_person" type="text" v-model="formData.contactPerson" />
                            <!-- <FieldError v-if="errors.email" :errorMessage="errors.email[0]"/> -->
                        </Field>
                        <Field>
                            <FieldLabel for="contact_number">
                            Contact Number
                            </FieldLabel>
                            <Input id="contact_number" type="text" v-model="formData.contactNumber" />
                            <!-- <FieldError v-if="errors.email" :errorMessage="errors.email[0]"/> -->
                        </Field>
                   </div>

                   <Field>
                        <FieldLabel for="official_email">
                        Official Email
                        </FieldLabel>
                        <Input id="contact_number" type="text"  v-model="formData.officialEmail" />
                        <!-- <FieldError v-if="errors.email" :errorMessage="errors.email[0]"/> -->
                    </Field>

                  

                </FieldGroup>

                <Button  type="submit"   class="mt-4">Save</Button>
            </div>

        </form>
    </div>
</template>