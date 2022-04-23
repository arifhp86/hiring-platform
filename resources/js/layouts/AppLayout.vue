<template>
    <div>
        <div class="w-full p-6 bg-teal-100 font-bold flex justify-between">
            <routerLink to="/" class=" uppercase text-lg text-gray-700">Hiring Platform</routerLink>
            <span class="text-gray-600">Your wallet has: {{ coins }} coins</span>
        </div>
        <slot />
        <div class="w-full p-6 bg-gray-100 text-right font-bold flex justify-center">
            <routerLink to="/" class=" uppercase text-lg text-gray-700">Hiring Platform</routerLink>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        coins: Number
    }
}
</script>