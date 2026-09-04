
<script lang="ts">
  import ProductCard from "$lib/components/ProductCard.svelte";
  let {data} = $props()
</script>

{#if data.products.length <= 0}
  <div>
    There are no products.
  </div>
  {:else}
  {#each data.products as product}
  <ProductCard product={product} />
{/each}
{/if}



