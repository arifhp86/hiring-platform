<template>
    <div>
        <div class="w-full p-6 bg-teal-100 text-right font-bold">Your wallet has: {{ coins }} coins</div>
        <slot />
        <div class="w-full p-6 bg-gray-100 text-right font-bold flex justify-center">Hiring Platform</div>
    </div>
</template>

<script>
export default {
    props: {
        coins: Number
    }
}
</script>