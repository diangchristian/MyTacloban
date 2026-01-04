<script setup>
import { Eye, Target } from 'lucide-vue-next';



</script>


<template>
    <section class=" my-22 ">
        <div class="max-w-7xl mx-auto p-4" data-aos="fade-down" data-aos-delay="500">
            <div class="text-center">
                <h1 class="text-5xl font-semibold mb-2">Our purpose</h1>
                <p>Building a stronger, more connected Tacloban through technology and community engagement</p>
            </div>
            <div class="flex flex-col md:flex-row  max-w-5xl justify-center mx-auto  gap-6 mt-8 ">
                <div class="flex-1 border border-primar  p-4 bg-white rounded-xl shadow-sm ">
                    <div class="size-14  mx-auto mt-4 items-center flex justify-center bg-primary shadow-sm rounded-md   ">
                            <Eye class="size-12 text-white"/>
                    </div>

                    <h2 class="font-semibold text-2xl mt-4">Our Vision</h2>
                    <p class="mt-2 text-gray-500 leading-7">To be the leading digital ecosystem for local governance in the Philippines, where every citizen has a voice, every concern is heard, and every community thrives through technology-enabled civic engagement and sustainable development.</p>

                </div>
                <div class="flex-1 border border-primar  p-4 bg-white rounded-xl shadow-sm ">
                    <div class="size-14  mx-auto mt-4 items-center flex justify-center bg-white shadow-sm rounded-md   ">
                            <Target class="size-12 text-primary"/>
                    </div>

                    <h2 class="font-semibold text-2xl mt-4">Our mission</h2>
                    <p class="mt-2 text-gray-500 leading-7">To provide Taclobanons with a seamless, accessible digital platform that bridges the gap between citizens and government, enabling efficient communication, transparent governance, and community-driven solutions for a better quality of life.</p>

                </div>
            </div>

        </div>
    </section>
</template>