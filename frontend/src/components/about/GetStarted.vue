<script setup>
import Button from '../ui/button/Button.vue';
</script>


<template>
    <section class="  bg-white py-22 mt-12">
        <div class="" data-aos="fade-down" data-aos-delay="500"> 
            <div class="text-center">
                <h1 class="text-5xl font-semibold mb-2">Join the MyTacloban Community</h1>
                <p>Be part of Tacloban's digital transformation. Create your account today and start engaging with your local government.</p>

                <Button class="mt-4 cursor-pointer" asChild>
                <RouterLink :to="{name: 'register' }">Get Started Today</RouterLink>
              </Button>
            </div>
        </div>
    </section>
</template>