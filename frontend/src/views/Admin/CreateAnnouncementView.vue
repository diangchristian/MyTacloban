<script setup>
import TinyMCE from "@/components/TinyMCE.vue";
import Input from "@/components/ui/input/Input.vue";
import Button from "@/components/ui/button/Button.vue";
import Label from "@/components/ui/label/Label.vue";
import { Checkbox } from '@/components/ui/checkbox'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
</script>

<template>
  <main>
    <div class="max-w-7xl mx-auto">
      <div class="grid grid-cols-3 gap-4">
        <div class="col-span-2">
          <div class="">
            <Label class="mb-2">Title</Label>
            <Input placeholder="Enter title " class="bg-white" />
          </div>
          <TinyMCE class="w-full mt-4" />
        </div>
        <div class="bg-white p-4 rounded-lg shadow-sm space-y-4 flex flex-col">
          <div class="">
            <Label for="" class="mb-2">Category</Label>
            <Select>
              <SelectTrigger class="w-full mt-2">
                <SelectValue placeholder="Select a status" class="w-full" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="in_progress"> Emergency </SelectItem>
                <SelectItem value="assigned"> Community Event </SelectItem>
                <SelectItem value="resolved"> Notice </SelectItem>
                
              </SelectContent>
            </Select>
          </div>
          <div class="mt-2">
            <div class="flex items-center gap-3">
                <Checkbox id="highlights" />
                <Label for="highlights" class="font-semibold">Add to Highlights</Label>
            </div>
          </div>
          <div class="">
            <div class="flex items-center justify-center w-full">
                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 bg-neutral-secondary-medium border border-dashed border-default-strong rounded-base cursor-pointer hover:bg-neutral-tertiary-medium">
                    <div class="flex flex-col items-center justify-center text-body pt-5 pb-6">
                        <svg class="w-8 h-8 mb-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h3a3 3 0 0 0 0-6h-.025a5.56 5.56 0 0 0 .025-.5A5.5 5.5 0 0 0 7.207 9.021C7.137 9.017 7.071 9 7 9a4 4 0 1 0 0 8h2.167M12 19v-9m0 0-2 2m2-2 2 2"/></svg>
                        <p class="mb-2 text-sm"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                        <p class="text-xs">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                    </div>
                    <input id="dropzone-file" type="file" class="hidden" />
                </label>
            </div> 
          </div>

          <div class="mt-auto">
            <Button class="mr-4">Publish</Button>
            <Button variant="outline">Save as Draft</Button>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>
