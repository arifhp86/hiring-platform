<template>
    <div class="container min-h-screen mx-auto">
        <div class="flex flex-col justify-center items-center min-h-full">
            <h1 class="text-2xl sm:text-4xl font-bold">Welcome to the hiring platform</h1>
            <div class="flex items-center mt-10">
                <routerLink to="/candidate-list" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow align-middle">
                    Go to candidates list
                </routerLink>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    metaInfo: {
      title: 'Home',
    },
}
</script>
