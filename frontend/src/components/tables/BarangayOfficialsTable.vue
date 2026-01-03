<script setup>
import {
    Table,
    TableBody,
    TableFooter,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'
import UserRow from '../others/UserRow.vue';
import Separator from '../ui/separator/Separator.vue';

const people = [
  {
    id: 1,
    name: "Juan Dela Cruz",
    email: "juan.delacruz@tacloban.gov.ph",
    role: "Barangay Captain",
    contact: "0917 123 4567",
    dateJoined: "2022-01-15",
  },
  {
    id: 2,
    name: "Maria Santos",
    email: "maria.santos@tacloban.gov.ph",
    role: "Barangay Secretary",
    contact: "0920 456 7890",
    dateJoined: "2023-06-10",
  },
  {
    id: 3,
    name: "Pedro Reyes",
    email: "pedro.reyes@tacloban.gov.ph",
    role: "Barangay Treasurer",
    contact: "0918 987 6543",
    dateJoined: "2021-09-05",
  },
  {
    id: 4,
    name: "Ana Lopez",
    email: "ana.lopez@tacloban.gov.ph",
    role: "Barangay Kagawad",
    contact: "0935 321 8899",
    dateJoined: "2024-02-20",
  },
  {
    id: 5,
    name: "Mark Villanueva",
    email: "mark.villanueva@tacloban.gov.ph",
    role: "Barangay Tanod",
    contact: "0906 554 2211",
    dateJoined: "2020-11-30",
  },
];


</script>


<template>
   <div class="bg-white p-4">
        <div class="">
           <h1 class="font-semibold text-xl mb-4 border-s-4 pl-2 border-green-200">Barangay Officials</h1>
        </div>
        <Separator/>
        <Table>
            <TableHeader>
              <TableRow class="row">
                <TableHead>Official Name</TableHead>
                <TableHead>Email</TableHead>
                <TableHead>Position</TableHead>
                <TableHead>Contact Number</TableHead>
                <TableHead>Date Joined</TableHead>
                <TableHead class="w-[70px]">Actions</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <UserRow
                v-for="person in people"
                :key="person.id"
                :person="person"
                mode="officials"
              
              />
            </TableBody>
          </Table>
   </div>
</template>