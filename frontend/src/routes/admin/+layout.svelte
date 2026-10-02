<script lang="ts">

	let { children } = $props();
</script>

<svelte:head>
  <title>
    Store Admin Dashboard
  </title>
</svelte:head>


<main class="p-8">

  
  <h1>Welcome to the ADMIN DASHBOARD</h1>

  {@render children()}
  
</main>
